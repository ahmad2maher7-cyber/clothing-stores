<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductImage;

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
    ->whereHas('products')
    ->take(6)
    ->get();
    // ═══════════════════════════════════════
//  صور السلايدر (صور المنتجات المميزة)
// ═══════════════════════════════════════
$heroImages = ProductImage::whereHas('product', function ($q) {
        $q->where('status', 'active');
    })
    ->where('is_primary', true)
    ->inRandomOrder()
    ->take(6)
    ->get()
    ->map(function ($img) {
        $url = $img->image_url;
        
        // ✅ إصلاح الروابط القديمة المعطلة
        if (str_contains($url, 'via.placeholder.com')) {
            $url = str_replace(
                ['via.placeholder.com/600x800', 'via.placeholder.com'], 
                ['placehold.co/600x800/166534/FFFFFF', 'placehold.co/600x800/166534/FFFFFF'], 
                $url
            );
        }

        // ✅ معالجة الصور المحلية (المرفوعة من التاجر)
        return str_starts_with($url, 'http') 
            ? $url 
            : asset('storage/' . $url);
    })
    ->toArray();

// ⚠️ احتياطي: إذا لم توجد صور
if (empty($heroImages)) {
    $heroImages = [
        'https://placehold.co/600x600/166534/FFFFFF?text=Clothing+Store',
    ];
}

        return view('home', compact(
            'stores',
            'featuredProducts',
            'discountedProducts',
            'activeOffers',
            'mainCategories',
            'heroImages'
        ));
    }
}