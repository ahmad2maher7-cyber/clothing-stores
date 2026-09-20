<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // إحصائيات أساسية
        $stats = [
            'users' => User::count(),
            'merchants' => User::where('role', 'merchant')->count(),
            'customers' => User::where('role', 'customer')->count(),
            'stores' => Store::count(),
            'active_stores' => Store::where('status', 'active')->count(),
            'pending_stores' => Store::where('status', 'pending')->count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'reviews' => Review::count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
        ];

        // آخر المستخدمين
        $recentUsers = User::latest()->take(5)->get();

        // آخر الطلبات
        $recentOrders = Order::with('customer', 'store')->latest()->take(5)->get();

        // المتاجر المعلقة (تحتاج موافقة)
        $pendingStores = Store::where('status', 'pending')->with('merchant')->take(5)->get();

        // مبيعات آخر 7 أيام
        $salesData = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentOrders',
            'pendingStores',
            'salesData'
        ));
    }
}