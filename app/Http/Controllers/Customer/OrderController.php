<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * قائمة الطلبات
     */
    public function index(Request $request)
    {
        $query = auth()->user()->orders()
            ->with(['store', 'items', 'paymentMethod'])
            ->withCount('items');

        // فلترة حسب الحالة
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // بحث برقم الطلب
        if ($request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->latest()->paginate(10);

        // إحصائيات
        $stats = [
            'all' => auth()->user()->orders()->count(),
            'pending' => auth()->user()->orders()->whereIn('status', ['pending', 'processing'])->count(),
            'delivering' => auth()->user()->orders()->whereIn('status', ['shipped', 'delivering'])->count(),
            'delivered' => auth()->user()->orders()->where('status', 'delivered')->count(),
        ];

        return view('customer.orders.index', compact('orders', 'stats'));
    }

    /**
     * تفاصيل طلب
     */
    public function show(Order $order)
    {
        // التحقق من ملكية الطلب
        if ($order->customer_id !== auth()->id()) {
            abort(403, 'غير مصرح');
        }

        $order->load([
            'items.variant.product',
            'store',
            'paymentMethod',
            'shippingZone',
            'coupon',
            'statusHistory.changedBy',
        ]);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * إلغاء الطلب (إذا كان pending)
     */
    public function cancel(Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'لا يمكن إلغاء الطلب في هذه المرحلة');
        }

        $order->update(['status' => 'cancelled']);

        \App\Models\OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'cancelled',
            'note' => 'تم الإلغاء بواسطة الزبون',
            'changed_by' => auth()->id(),
        ]);

        // إعادة المخزون
        foreach ($order->items as $item) {
            $item->variant->increment('stock_quantity', $item->quantity);
        }

        return redirect()
            ->route('customer.orders.show', $order)
            ->with('success', 'تم إلغاء الطلب بنجاح');
    }
}