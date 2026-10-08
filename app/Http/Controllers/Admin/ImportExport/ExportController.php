<?php

namespace App\Http\Controllers\Admin\ImportExport;

use App\Exports\OrdersExport;
use App\Exports\ProductsExport;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * تصدير المستخدمين
     */
    public function users(Request $request)
    {
        $filters = [
            'role' => $request->input('role'),
            'status' => $request->input('status'),
        ];

        $filename = 'users_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new UsersExport($filters), $filename);
    }

    /**
     * تصدير المنتجات
     */
    public function products(Request $request)
    {
        $filters = [
            'store_id' => $request->input('store_id'),
            'status' => $request->input('status'),
            'category_id' => $request->input('category_id'),
            'gender' => $request->input('gender'),
        ];

        // إذا كان المستخدم تاجراً — نُقيد على متجره فقط
        if (auth()->user()->role === 'merchant') {
            $store = auth()->user()->stores()->first();
            if ($store) {
                $filters['store_id'] = $store->id;
            }
        }

        $filename = 'products_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new ProductsExport($filters), $filename);
    }

    /**
     * تصدير الطلبات
     */
    public function orders(Request $request)
    {
        $filters = [
            'store_id' => $request->input('store_id'),
            'status' => $request->input('status'),
            'payment_status' => $request->input('payment_status'),
        ];

        // إذا كان المستخدم تاجراً — نُقيد على متجره فقط
        if (auth()->user()->role === 'merchant') {
            $store = auth()->user()->stores()->first();
            if ($store) {
                $filters['store_id'] = $store->id;
            }
        }

        $filename = 'orders_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new OrdersExport($filters), $filename);
    }
}