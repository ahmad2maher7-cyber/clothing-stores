<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\PaymentMethod;
use App\Models\ShippingZone;
use App\Services\LahzaService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * عرض صفحة إتمام الطلب
     */
    public function index()
    {
        $cart = auth()->user()->carts()->latest()->first();

        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }

        $cart->load('items.variant.product.store', 'items.variant.product.primaryImage');

        $itemsByStore = $cart->items->groupBy(fn($item) => $item->variant->product->store_id);

        $firstStore = $cart->items->first()->variant->product->store;
        $shippingZones = $firstStore->shippingZones()->where('is_active', true)->get();

        $paymentMethods = $firstStore->paymentMethods()->where('is_active', true)->get();

        $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);

        return view('checkout.index', compact(
            'cart',
            'itemsByStore',
            'shippingZones',
            'paymentMethods',
            'subtotal'
        ));
    }

    /**
     * معالجة الطلب
     */
    public function store(Request $request)
    {
        $cart = auth()->user()->carts()->latest()->first();

        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }

        $validated = $request->validate([
            'shipping_address' => 'required|string|max:500',
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
            'coupon_code' => 'nullable|string',
        ], [
            'shipping_address.required' => 'عنوان التوصيل مطلوب',
            'shipping_zone_id.required' => 'يجب اختيار منطقة الشحن',
            'payment_method_id.required' => 'يجب اختيار طريقة الدفع',
            'phone.required' => 'رقم الهاتف مطلوب',
        ]);

        $cart->load('items.variant.product');

        $createdOrders = [];

        try {
            DB::beginTransaction();

            $itemsByStore = $cart->items->groupBy(fn($item) => $item->variant->product->store_id);

            foreach ($itemsByStore as $storeId => $items) {
                $subtotal = $items->sum(fn($item) => $item->price * $item->quantity);

                $discount = 0;
                $couponId = null;
                if (!empty($validated['coupon_code'])) {
                    $coupon = Coupon::where('store_id', $storeId)
                        ->where('code', strtoupper($validated['coupon_code']))
                        ->first();

                    if ($coupon && $coupon->isValid() && $subtotal >= ($coupon->min_order_amount ?? 0)) {
                        if ($coupon->type === 'percentage') {
                            $discount = min(
                                $subtotal * ($coupon->value / 100),
                                $coupon->max_discount ?? PHP_FLOAT_MAX
                            );
                        } else {
                            $discount = min($coupon->value, $subtotal);
                        }
                        $couponId = $coupon->id;
                        $coupon->increment('used_count');
                    }
                }

                $shippingZone = ShippingZone::where('id', $validated['shipping_zone_id'])
                    ->where('store_id', $storeId)
                    ->first();
                $shippingCost = $shippingZone ? $shippingZone->cost : 0;

                $total = $subtotal - $discount + $shippingCost;

                $order = Order::create([
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'customer_id' => auth()->id(),
                    'store_id' => $storeId,
                    'shipping_zone_id' => $shippingZone?->id,
                    'payment_method_id' => $validated['payment_method_id'],
                    'coupon_id' => $couponId,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'shipping_cost' => $shippingCost,
                    'total' => $total,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'shipping_address' => $validated['shipping_address'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'variant_id' => $item->variant_id,
                        'product_name' => $item->variant->product->name,
                        'size' => $item->variant->size,
                        'color' => $item->variant->color,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->price,
                        'total_price' => $item->price * $item->quantity,
                    ]);

                    $item->variant->decrement('stock_quantity', $item->quantity);

                    \App\Models\InventoryLog::create([
                        'variant_id' => $item->variant_id,
                        'change_type' => 'out',
                        'quantity' => $item->quantity,
                        'reason' => 'بيع - طلب ' . $order->order_number,
                        'created_by' => auth()->id(),
                    ]);
                }

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => 'pending',
                    'note' => 'تم استلام الطلب',
                    'changed_by' => auth()->id(),
                ]);

                $createdOrders[] = $order;
            }

            $cart->items()->delete();
            $cart->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage())->withInput();
        }

        // ═══════════════════════════════════════
        //  💳 Lahza Payment — إذا اختار الزبون بطاقة/محفظة
        // ═══════════════════════════════════════
        $paymentMethod = PaymentMethod::find($validated['payment_method_id']);
        $useLahza = $paymentMethod
            && in_array($paymentMethod->code, ['card', 'wallet'])
            && LahzaService::isEnabled();

        if ($useLahza) {
            // نجمع كل الطلبات في معاملة Lahza واحدة (نستخدم أول طلب كمرجع)
            $firstOrder = $createdOrders[0];
            $lahza = LahzaService::initializeTransaction($firstOrder);

            if ($lahza['success']) {
                // نحفظ معرّفات كل الطلبات في الجلسة
                session()->put('lahza_order_ids', collect($createdOrders)->pluck('id')->toArray());
                session()->put('lahza_reference', $lahza['reference'] ?? $firstOrder->order_number);

                return redirect($lahza['authorization_url']);
            }

            // فشل Lahza → نُبقي الطلبات "unpaid" ونُخبر الزبون
            Log::error('Lahza initialization failed', [
                'order_ids' => collect($createdOrders)->pluck('id')->toArray(),
                'message' => $lahza['message'] ?? 'unknown',
            ]);

            return redirect()
                ->route('customer.orders.index')
                ->with('error', 'تم إنشاء طلبك، لكن فشل الاتصال ببوابة الدفع. يرجى المحاولة من صفحة الطلبات: ' . ($lahza['message'] ?? ''));
        }

        // ═══════════════════════════════════════
        //  الدفع عند الاستلام (COD) — تدفق عادي
        // ═══════════════════════════════════════
        foreach ($createdOrders as $order) {
            try {
                $merchant = $order->store->merchant;
                if ($merchant) {
                    NotificationService::send(
                        user: $merchant,
                        type: 'order',
                        title: '🛒 طلب جديد',
                        body: "لديك طلب جديد رقم {$order->order_number} بقيمة " . number_format($order->total, 0) . ' ₪',
                        actionUrl: route('merchant.orders.show', $order)
                    );
                }

                NotificationService::send(
                    user: auth()->user(),
                    type: 'order',
                    title: '✅ تم استلام طلبك',
                    body: "تم استلام طلبك رقم {$order->order_number} بنجاح وسيتم تجهيزه قريباً",
                    actionUrl: route('customer.orders.show', $order)
                );

            } catch (\Throwable $e) {
                Log::error('Order notification failed: ' . $e->getMessage(), [
                    'order_id' => $order->id,
                ]);
            }
        }

        return redirect()->route('checkout.success', $createdOrders[0])
            ->with('success', 'تم إرسال طلبك بنجاح!');
    }

    /**
     * 🌐 Callback من Lahza بعد الدفع
     */
    public function lahzaCallback(Request $request, Order $order)
    {
        // تحقق من ملكية الطلب
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        // استخراج المرجع من الـ callback أو الجلسة
        $reference = $request->input('reference')
            ?? $request->input('trxref')
            ?? session('lahza_reference')
            ?? $order->order_number;

        // التحقق من الدفع
        $verify = LahzaService::verifyTransaction($reference);

        if (!$verify['success']) {
            Log::error('Lahza verify failed', [
                'order' => $order->order_number,
                'reference' => $reference,
                'message' => $verify['message'] ?? '',
            ]);

            return redirect()
                ->route('customer.orders.index')
                ->with('error', 'فشل التحقق من الدفع: ' . ($verify['message'] ?? 'خطأ غير معروف'));
        }

        $status = $verify['status'];

        // نحدّث كل الطلبات المرتبطة بالمعاملة
        $orderIds = session('lahza_order_ids', [$order->id]);
        $orders = Order::whereIn('id', $orderIds)->get();

        if ($status === 'success') {
            foreach ($orders as $o) {
                $o->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);

                OrderStatusHistory::create([
                    'order_id' => $o->id,
                    'status' => 'processing',
                    'note' => 'تم الدفع بنجاح عبر Lahza — رقم المرجع: ' . $reference,
                    'changed_by' => $o->customer_id,
                ]);

                // إشعار للتاجر
                try {
                    $merchant = $o->store->merchant;
                    if ($merchant) {
                        NotificationService::send(
                            user: $merchant,
                            type: 'order',
                            title: '💰 تم استلام الدفع',
                            body: "الطلب {$o->order_number} مدفوع بالكامل — جاهز للتجهيز",
                            actionUrl: route('merchant.orders.show', $o)
                        );
                    }
                } catch (\Throwable $e) {
                    Log::error('Merchant paid notification failed: ' . $e->getMessage());
                }
            }

            session()->forget(['lahza_order_ids', 'lahza_reference']);

            return redirect()
                ->route('checkout.success', $order)
                ->with('success', '✅ تم استلام دفعتك بنجاح!');
        }

        // فشل الدفع
        foreach ($orders as $o) {
            $o->update(['payment_status' => 'unpaid']);
        }

        return redirect()
            ->route('customer.orders.index')
            ->with('error', 'لم يتم إتمام الدفع. حالة العملية: ' . $status);
    }

    /**
     * 🔔 Webhook من Lahza (يُستدعى تلقائياً من Lahza)
     */
    public function lahzaWebhook(Request $request)
    {
        Log::info('Lahza Webhook received', $request->all());

        $payload = $request->all();
        $reference = LahzaService::extractReferenceFromWebhook($payload);
        $status = LahzaService::extractStatusFromWebhook($payload);

        if (!$reference || $status !== 'success') {
            return response()->json(['status' => 'ignored'], 200);
        }

        // ابحث عن الطلب برقم مرجع Lahza
        // نحفظ الـ reference في الـ notes
        $order = Order::where('notes', 'LIKE', "%{$reference}%")
            ->orWhere('order_number', $reference)
            ->first();

        if (!$order) {
            Log::warning('Lahza Webhook: order not found', ['reference' => $reference]);
            return response()->json(['status' => 'not_found'], 200);
        }

        // تحديث حالة الدفع
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'processing',
            'note' => 'تم الدفع عبر Webhook من Lahza — مرجع: ' . $reference,
            'changed_by' => $order->customer_id,
        ]);

        // إشعار للتاجر
        try {
            $merchant = $order->store->merchant;
            if ($merchant) {
                NotificationService::send(
                    user: $merchant,
                    type: 'order',
                    title: '💰 تم استلام الدفع (Webhook)',
                    body: "الطلب {$order->order_number} مدفوع بالكامل",
                    actionUrl: route('merchant.orders.show', $order)
                );
            }
        } catch (\Throwable $e) {
            Log::error('Webhook notification failed: ' . $e->getMessage());
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * صفحة نجاح الطلب
     */
    public function success(Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items', 'store', 'paymentMethod', 'shippingZone');

        return view('checkout.success', compact('order'));
    }

    /**
     * تطبيق الكوبون (AJAX)
     */
    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric',
        ]);

        $coupon = Coupon::where('code', strtoupper($validated['code']))->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'الكوبون غير موجود']);
        }

        if (!$coupon->isValid()) {
            return response()->json(['success' => false, 'message' => 'الكوبون منتهي أو مستنفد']);
        }

        if ($validated['subtotal'] < ($coupon->min_order_amount ?? 0)) {
            return response()->json([
                'success' => false,
                'message' => 'الحد الأدنى للطلب: ' . $coupon->min_order_amount . ' ₪',
            ]);
        }

        if ($coupon->type === 'percentage') {
            $discount = min(
                $validated['subtotal'] * ($coupon->value / 100),
                $coupon->max_discount ?? PHP_FLOAT_MAX
            );
        } else {
            $discount = min($coupon->value, $validated['subtotal']);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تطبيق الكوبون!',
            'discount' => round($discount, 2),
            'coupon_id' => $coupon->id,
        ]);
    }
}