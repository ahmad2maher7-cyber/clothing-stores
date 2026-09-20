<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'store', 'paymentMethod'])->withCount('items');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->store_id) {
            $query->where('store_id', $request->store_id);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', fn($q2) => $q2->where('full_name', 'like', '%' . $request->search . '%'));
            });
        }

        $orders = $query->latest()->paginate(20);

        $stores = Store::orderBy('name')->get();

        $stats = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'revenue' => Order::where('payment_status', 'paid')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'stores', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load([
            'customer',
            'store',
            'items.variant.product',
            'paymentMethod',
            'shippingZone',
            'statusHistory.changedBy',
        ]);

        return view('admin.orders.show', compact('order'));
    }
}