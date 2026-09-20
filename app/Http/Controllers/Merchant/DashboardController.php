<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $store = $user->stores()->first();

        // لا يوجد متجر → صفحة إنشاء
        if (!$store) {
            return redirect()->route('merchant.store.create');
        }

        // المتجر غير معتمد → صفحة الانتظار
        if ($store->status !== 'active') {
            return redirect()->route('merchant.store.pending');
        }

        // المتجر نشط → لوحة التحكم
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