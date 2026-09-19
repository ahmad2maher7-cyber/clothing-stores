<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;

class DashboardController extends Controller
{
    public function index()
    {
        $store = auth()->user()->stores()->first();

        if (!$store) {
            return redirect()->route('merchant.store.create')
                ->with('error', 'يجب إنشاء متجر أولاً');
        }

        $stats = [
            'products' => $store->products()->count(),
            'orders' => $store->orders()->count(),
            'pending_orders' => $store->orders()->where('status', 'pending')->count(),
            'revenue' => $store->orders()->where('payment_status', 'paid')->sum('total'),
            'low_stock' => $store->products()
                ->whereHas('variants', fn($q) => $q->whereColumn('stock_quantity', '<=', 'low_stock_threshold'))
                ->count(),
        ];

        return view('merchant.dashboard', compact('store', 'stats'));
    }
}