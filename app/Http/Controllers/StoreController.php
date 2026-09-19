<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::where('status', 'active')
            ->withCount('products');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $stores = $query->orderByDesc('products_count')->paginate(12);

        return view('stores.index', compact('stores'));
    }

    public function show(Store $store, Request $request)
    {
        if ($store->status !== 'active') {
            abort(404);
        }

        $query = $store->products()
            ->where('status', 'active')
            ->with('primaryImage', 'category');

        // فلترة حسب التصنيف
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // فلترة حسب الجنس
        if ($request->gender) {
            $query->where('gender', $request->gender);
        }

        // البحث
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // الترتيب
        switch ($request->sort) {
            case 'price_low':
                $query->orderByRaw('COALESCE(discount_price, base_price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(discount_price, base_price) DESC');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        // تصنيفات المتجر
        $categories = $store->categories()
            ->whereNull('parent_id')
            ->where('status', 'active')
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->get();

        return view('stores.show', compact('store', 'products', 'categories'));
    }
}