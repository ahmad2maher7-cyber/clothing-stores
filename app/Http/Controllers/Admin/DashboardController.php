<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'merchants' => User::where('role', 'merchant')->count(),
            'customers' => User::where('role', 'customer')->count(),
            'stores' => Store::count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'revenue' => Order::where('payment_status', 'paid')->sum('total'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}