<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Store;

class HomeController extends Controller
{
    public function index()
    {
        // المتاجر النشطة
        $stores = Store::where('status', 'active')
            ->withCount('products')
            ->take(6)
            ->get();

        // المنتجات المميزة (الأحدث)
        $featuredProducts = Product::where('status', 'active')
            ->with(['primaryImage', 'store', 'category'])
            ->latest()
            ->take(8)
            ->get();

        // منتجات عليها خصم
        $discountedProducts = Product::where('status', 'active')
            ->whereNotNull('discount_price')
            ->with(['primaryImage', 'store'])
            ->latest()
            ->take(4)
            ->get();

        // العروض النشطة
        $activeOffers = Offer::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->with('store')
            ->take(3)
            ->get();

        // التصنيفات الرئيسية (التي تحتوي على منتجات)
        $mainCategories = Category::whereNull('parent_id')
            ->where('status', 'active')
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->take(6)
            ->get();

        return view('home', compact(
            'stores',
            'featuredProducts',
            'discountedProducts',
            'activeOffers',
            'mainCategories'
        ));
    }
}