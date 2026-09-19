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

    /**
     * عرض جميع الطلبات
     */
    public function index(Request $request)
    {
        $store = $this->getStore();

        $query = $store->orders()
            ->with(['customer', 'items', 'paymentMethod'])
            ->withCount('items');

        // فلترة حسب الحالة
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // فلترة حسب حالة الدفع
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // بحث
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

        // إحصائيات لكل حالة
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

    /**
     * عرض تفاصيل طلب
     */
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

    /**
     * تحديث حالة الطلب
     */
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

        // لا يمكن تغيير طلب ملغى أو مُسترجع
        if (in_array($order->status, ['cancelled', 'returned'])) {
            return back()->with('error', 'لا يمكن تعديل حالة طلب ملغى أو مُسترجع');
        }

        // لا تغيير إذا كانت نفس الحالة
        if ($order->status === $validated['status']) {
            return back()->with('error', 'الحالة الجديدة مطابقة للحالية');
        }

        try {
            DB::beginTransaction();

            // تحديث حالة الطلب
            $order->update(['status' => $validated['status']]);

            // إضافة سجل الحالة
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $validated['status'],
                'note' => $validated['note'] ?? null,
                'changed_by' => auth()->id(),
            ]);

            // إذا تم التسليم، حدّث حالة الدفع
            if ($validated['status'] === 'delivered' && $order->payment_method_id) {
                // فقط إذا كان الدفع عند الاستلام
                $paymentMethod = $order->paymentMethod;
                if ($paymentMethod && $paymentMethod->code === 'cod') {
                    $order->update(['payment_status' => 'paid']);
                }
            }

            DB::commit();

            return redirect()
                ->route('merchant.orders.show', $order)
                ->with('success', 'تم تحديث حالة الطلب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }
}