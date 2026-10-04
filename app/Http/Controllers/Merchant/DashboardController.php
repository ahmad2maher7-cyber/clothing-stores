<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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

        // ═══════════════════════════════════════
        //  حساب المخزون المنخفض (متوافق مع PostgreSQL + MySQL)
        // ═══════════════════════════════════════
        $lowStockCount = 0;
        try {
            $lowStockCount = DB::table('product_variants')
                ->join('products', 'product_variants.product_id', '=', 'products.id')
                ->where('products.store_id', $store->id)
                ->where('product_variants.status', 'active')
                ->whereRaw('product_variants.stock_quantity <= product_variants.low_stock_threshold')
                ->distinct('product_variants.product_id')
                ->count('product_variants.product_id');
        } catch (\Throwable $e) {
            \Log::warning('Low stock count failed: ' . $e->getMessage());
            $lowStockCount = 0;
        }

        // المتجر نشط → لوحة التحكم
        $stats = [
            'products' => $store->products()->count(),
            'orders' => $store->orders()->count(),
            'pending_orders' => $store->orders()->where('status', 'pending')->count(),
            'revenue' => $store->orders()->where('payment_status', 'paid')->sum('total'),
            'low_stock' => $lowStockCount,
        ];

        return view('merchant.dashboard', compact('store', 'stats'));
    }
}