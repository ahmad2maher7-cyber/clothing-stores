<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected function getStore()
    {
        $store = auth()->user()->stores()->first();
        if (!$store) {
            abort(404, 'لا يوجد متجر مرتبط بحسابك');
        }
        return $store;
    }

    public function index(Request $request)
    {
        $store = $this->getStore();

        $query = $store->orders()
            ->with(['customer', 'items', 'paymentMethod'])
            ->withCount('items');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function ($q2) use ($request) {
                      $q2->where('full_name', 'like', '%' . $request->search . '%')
                         ->orWhere('phone', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->latest()->paginate(15);

        $stats = [
            'all' => $store->orders()->count(),
            'pending' => $store->orders()->where('status', 'pending')->count(),
            'processing' => $store->orders()->where('status', 'processing')->count(),
            'shipped' => $store->orders()->where('status', 'shipped')->count(),
            'delivering' => $store->orders()->where('status', 'delivering')->count(),
            'delivered' => $store->orders()->where('status', 'delivered')->count(),
            'cancelled' => $store->orders()->where('status', 'cancelled')->count(),
            'total_revenue' => $store->orders()->where('payment_status', 'paid')->sum('total'),
        ];

        return view('merchant.orders.index', compact('store', 'orders', 'stats'));
    }

    public function show(Order $order)
    {
        $store = $this->getStore();
        if ($order->store_id !== $store->id) {
            abort(403, 'غير مصرح');
        }

        $order->load([
            'customer',
            'items.variant.product',
            'paymentMethod',
            'shippingZone',
            'coupon',
            'statusHistory.changedBy',
            'shipments.shippingCompany',
        ]);

        return view('merchant.orders.show', compact('store', 'order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $store = $this->getStore();
        if ($order->store_id !== $store->id) {
            abort(403, 'غير مصرح');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivering,delivered,cancelled,returned',
            'note' => 'nullable|string|max:500',
        ], [
            'status.required' => 'يجب اختيار حالة',
            'status.in' => 'الحالة غير صحيحة',
        ]);

        if (in_array($order->status, ['cancelled', 'returned'])) {
            return back()->with('error', 'لا يمكن تعديل حالة طلب ملغى أو مُسترجع');
        }

        if ($order->status === $validated['status']) {
            return back()->with('error', 'الحالة الجديدة مطابقة للحالية');
        }

        try {
            DB::beginTransaction();

            $order->update(['status' => $validated['status']]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $validated['status'],
                'note' => $validated['note'] ?? null,
                'changed_by' => auth()->id(),
            ]);

            if ($validated['status'] === 'delivered' && $order->payment_method_id) {
                $paymentMethod = $order->paymentMethod;
                if ($paymentMethod && $paymentMethod->code === 'cod') {
                    $order->update(['payment_status' => 'paid']);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }

        // ═══════════════════════════════════════
        //  الإشعار (باستخدام method جاهزة)
        // ═══════════════════════════════════════
        try {
            \App\Services\NotificationService::orderStatusChanged($order, $validated['status']);
        } catch (\Throwable $e) {
            \Log::error('Order status notification failed: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'status' => $validated['status'],
            ]);
        }

        return redirect()
            ->route('merchant.orders.show', $order)
            ->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}