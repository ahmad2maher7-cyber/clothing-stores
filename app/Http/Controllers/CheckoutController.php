<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\PaymentMethod;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // تجميع حسب المتجر
        $itemsByStore = $cart->items->groupBy(fn($item) => $item->variant->product->store_id);

        // مناطق الشحن (من أول متجر)
        $firstStore = $cart->items->first()->variant->product->store;
        $shippingZones = $firstStore->shippingZones()->where('is_active', true)->get();

        // طرق الدفع
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

        try {
            DB::beginTransaction();

            // تجميع العناصر حسب المتجر (كل متجر = طلب منفصل)
            $itemsByStore = $cart->items->groupBy(fn($item) => $item->variant->product->store_id);

            $createdOrders = [];

            foreach ($itemsByStore as $storeId => $items) {
                $subtotal = $items->sum(fn($item) => $item->price * $item->quantity);

                // تطبيق الكوبون (إن وُجد)
                $discount = 0;
                $couponId = null;
                if ($validated['coupon_code']) {
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

                // تكلفة الشحن
                $shippingZone = ShippingZone::where('id', $validated['shipping_zone_id'])
                    ->where('store_id', $storeId)
                    ->first();
                $shippingCost = $shippingZone ? $shippingZone->cost : 0;

                $total = $subtotal - $discount + $shippingCost;

                // إنشاء الطلب
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

                // إضافة عناصر الطلب + خصم المخزون
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

                    // خصم من المخزون
                    $item->variant->decrement('stock_quantity', $item->quantity);

                    // تسجيل حركة المخزون
                    \App\Models\InventoryLog::create([
                        'variant_id' => $item->variant_id,
                        'change_type' => 'out',
                        'quantity' => $item->quantity,
                        'reason' => 'بيع - طلب ' . $order->order_number,
                        'created_by' => auth()->id(),
                    ]);
                }

                // سجل الحالة الأولى
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => 'pending',
                    'note' => 'تم استلام الطلب',
                    'changed_by' => auth()->id(),
                ]);

                $createdOrders[] = $order;
            }

            // حذف السلة
            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            // التوجيه لصفحة النجاح (أول طلب)
            return redirect()->route('checkout.success', $createdOrders[0])
                ->with('success', 'تم إرسال طلبك بنجاح!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage())->withInput();
        }
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

        // حساب الخصم
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