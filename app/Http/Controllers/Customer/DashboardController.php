<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'orders' => $user->orders()->count(),
            'pending' => $user->orders()->whereIn('status', ['pending', 'processing'])->count(),
            'delivered' => $user->orders()->where('status', 'delivered')->count(),
            'wishlist' => $user->wishlists()->count(),
            'total_spent' => $user->orders()->where('payment_status', 'paid')->sum('total'),
        ];

        $recentOrders = $user->orders()
            ->with('store')
            ->latest()
            ->take(5)
            ->get();

        $recentWishlist = $user->wishlists()
            ->with('product.primaryImage', 'product.store')
            ->latest()
            ->take(4)
            ->get();

        return view('customer.dashboard', compact('user', 'stats', 'recentOrders', 'recentWishlist'));
    }
}