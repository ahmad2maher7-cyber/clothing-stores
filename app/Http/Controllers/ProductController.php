<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * قائمة المنتجات مع الفلاتر
     */
    public function index(Request $request)
    {
        $query = Product::where('status', 'active')
            ->with(['primaryImage', 'store', 'category', 'brand']);

        // ============ الفلاتر ============

        // البحث بالاسم
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // التصنيف
        if ($request->category) {
            $categoryIds = [$request->category];
            // إضافة التصنيفات الفرعية
            $children = Category::where('parent_id', $request->category)->pluck('id')->toArray();
            $categoryIds = array_merge($categoryIds, $children);
            $query->whereIn('category_id', $categoryIds);
        }

        // الجنس
        if ($request->gender) {
            $query->where('gender', $request->gender);
        }

        // الماركة
        if ($request->brand) {
            $query->where('brand_id', $request->brand);
        }

        // المتجر
        if ($request->store) {
            $query->where('store_id', $request->store);
        }

        // السعر
        if ($request->min_price) {
            $query->where(function ($q) use ($request) {
                $q->where('discount_price', '>=', $request->min_price)
                  ->orWhere(function ($q2) use ($request) {
                      $q2->whereNull('discount_price')
                         ->where('base_price', '>=', $request->min_price);
                  });
            });
        }

        if ($request->max_price) {
            $query->where(function ($q) use ($request) {
                $q->where('discount_price', '<=', $request->max_price)
                  ->orWhere(function ($q2) use ($request) {
                      $q2->whereNull('discount_price')
                         ->where('base_price', '<=', $request->max_price);
                  });
            });
        }

        // عليها خصم فقط
        if ($request->has_discount) {
            $query->whereNotNull('discount_price');
        }

        // ============ الترتيب ============
        switch ($request->sort) {
            case 'price_low':
                $query->orderByRaw('COALESCE(discount_price, base_price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(discount_price, base_price) DESC');
                break;
            case 'rating':
                $query->orderByDesc('rating_avg');
                break;
            case 'popular':
                $query->withCount('orderItems')->orderByDesc('order_items_count');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        // ============ بيانات الفلاتر ============
        $categories = Category::whereNull('parent_id')
            ->where('status', 'active')
            ->with('children')
            ->get();

        $brands = Brand::orderBy('name')->get();

        $stores = Store::where('status', 'active')->orderBy('name')->get();

        $priceRange = [
            'min' => floor(Product::where('status', 'active')->min('base_price') ?? 0),
            'max' => ceil(Product::where('status', 'active')->max('base_price') ?? 1000),
        ];

        return view('products.index', compact(
            'products',
            'categories',
            'brands',
            'stores',
            'priceRange'
        ));
    }

    /**
     * البحث
     */
    public function search(Request $request)
    {
        $request->merge(['search' => $request->q]);
        return $this->index($request);
    }

    /**
     * تفاصيل المنتج
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->with([
                'images',
                'variants' => fn($q) => $q->where('status', 'active'),
                'store',
                'category',
                'brand',
                'reviews' => fn($q) => $q->where('status', 'approved')->with('customer', 'images'),
            ])
            ->firstOrFail();

        // منتجات مشابهة (نفس التصنيف)
        $relatedProducts = Product::where('status', 'active')
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with('primaryImage', 'store')
            ->take(4)
            ->get();

        // المقاسات والألوان المتاحة
        $sizes = $product->variants->pluck('size')->unique()->values();
        $colors = $product->variants->pluck('color')->unique()->values();

        // متوسط التقييم
        $avgRating = $product->reviews->avg('rating') ?? 0;
        $reviewsCount = $product->reviews->count();

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'sizes',
            'colors',
            'avgRating',
            'reviewsCount'
        ));
    }
}