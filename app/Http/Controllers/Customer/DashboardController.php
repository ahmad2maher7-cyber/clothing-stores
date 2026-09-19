<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $customer = auth()->user();

        $stats = [
            'orders' => $customer->orders()->count(),
            'pending' => $customer->orders()->where('status', 'pending')->count(),
            'delivered' => $customer->orders()->where('status', 'delivered')->count(),
            'wishlist' => $customer->wishlists()->count(),
        ];

        $recentOrders = $customer->orders()->latest()->take(5)->get();

        return view('customer.dashboard', compact('stats', 'recentOrders'));
    }
}