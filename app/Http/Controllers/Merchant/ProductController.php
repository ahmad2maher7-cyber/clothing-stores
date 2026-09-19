<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected function getStore()
    {
        $store = auth()->user()->stores()->first();
        if (!$store) {
            abort(404, 'لا يوجد متجر مرتبط بحسابك');
        }
        return $store;
    }

    /**
     * عرض جميع المنتجات
     */
    public function index(Request $request)
    {
        $store = $this->getStore();

        $query = $store->products()
            ->with(['category', 'brand', 'primaryImage'])
            ->withCount('variants');

        // بحث
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // فلترة
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->gender) {
            $query->where('gender', $request->gender);
        }

        $products = $query->latest()->paginate(12);
        $categories = $store->categories()->whereNull('parent_id')->get();

        return view('merchant.products.index', compact('store', 'products', 'categories'));
    }

    /**
     * نموذج إضافة منتج
     */
    public function create()
    {
        $store = $this->getStore();
        $categories = $store->categories()->where('status', 'active')->get();
        $brands = Brand::all();

        return view('merchant.products.create', compact('store', 'categories', 'brands'));
    }

    /**
     * حفظ منتج جديد
     */
    public function store(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            // بيانات المنتج
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'gender' => 'required|in:men,women,kids,unisex',
            'base_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:base_price',
            'status' => 'required|in:active,draft,out_of_stock',

            // صور المنتج
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:3072',

            // المتغيرات
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|in:S,M,L,XL,XXL',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.fabric_type' => 'nullable|string|max:50',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.low_stock_threshold' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'اسم المنتج مطلوب',
            'category_id.required' => 'يجب اختيار تصنيف',
            'base_price.required' => 'السعر الأساسي مطلوب',
            'discount_price.lt' => 'سعر الخصم يجب أن يكون أقل من السعر الأساسي',
            'images.required' => 'يجب رفع صورة واحدة على الأقل',
            'images.min' => 'يجب رفع صورة واحدة على الأقل',
            'images.max' => 'الحد الأقصى 5 صور',
            'variants.required' => 'يجب إضافة متغير واحد على الأقل',
            'variants.*.size.required' => 'المقاس مطلوب',
            'variants.*.color.required' => 'اللون مطلوب',
            'variants.*.price.required' => 'السعر مطلوب',
        ]);

        try {
            DB::beginTransaction();

            // 1. إنشاء المنتج
            $product = Product::create([
                'store_id' => $store->id,
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'] ?? null,
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']) . '-' . uniqid(),
                'description' => $validated['description'] ?? null,
                'gender' => $validated['gender'],
                'base_price' => $validated['base_price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'currency' => 'ILS',
                'status' => $validated['status'],
            ]);

            // 2. رفع الصور
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $path,
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            // 3. إنشاء المتغيرات
            foreach ($validated['variants'] as $index => $variantData) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $variantData['size'],
                    'color' => $variantData['color'],
                    'fabric_type' => $variantData['fabric_type'] ?? null,
                    'sku' => 'SKU-' . $product->id . '-' . strtoupper(Str::random(6)) . '-' . $index,
                    'price' => $variantData['price'],
                    'stock_quantity' => $variantData['stock_quantity'],
                    'low_stock_threshold' => $variantData['low_stock_threshold'] ?? 5,
                    'status' => 'active',
                ]);
            }

            DB::commit();

            return redirect()
                ->route('merchant.products.index')
                ->with('success', 'تم إضافة المنتج بنجاح مع ' . count($validated['variants']) . ' متغير');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * عرض تفاصيل منتج
     */
    public function show(Product $product)
    {
        $store = $this->getStore();
        if ($product->store_id !== $store->id) {
            abort(403);
        }

        $product->load(['category', 'brand', 'images', 'variants', 'reviews']);

        return view('merchant.products.show', compact('store', 'product'));
    }

    /**
     * نموذج تعديل
     */
    public function edit(Product $product)
    {
        $store = $this->getStore();
        if ($product->store_id !== $store->id) {
            abort(403);
        }

        $product->load(['images', 'variants']);
        $categories = $store->categories()->where('status', 'active')->get();
        $brands = Brand::all();

        return view('merchant.products.edit', compact('store', 'product', 'categories', 'brands'));
    }

    /**
     * تحديث منتج
     */
    public function update(Request $request, Product $product)
    {
        $store = $this->getStore();
        if ($product->store_id !== $store->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'gender' => 'required|in:men,women,kids,unisex',
            'base_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:base_price',
            'status' => 'required|in:active,draft,out_of_stock',
        ]);

        $product->update([
            ...$validated,
            'slug' => $product->name !== $validated['name']
                ? Str::slug($validated['name']) . '-' . uniqid()
                : $product->slug,
        ]);

        return redirect()
            ->route('merchant.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * حذف منتج
     */
    public function destroy(Product $product)
    {
        $store = $this->getStore();
        if ($product->store_id !== $store->id) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            // حذف الصور من التخزين
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_url);
            }
            $product->images()->delete();

            // حذف المتغيرات
            $product->variants()->delete();

            // حذف المنتج
            $product->delete();

            DB::commit();

            return redirect()
                ->route('merchant.products.index')
                ->with('success', 'تم حذف المنتج بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }

    /**
     * حذف صورة منتج (AJAX)
     */
    public function deleteImage(ProductImage $image)
    {
        $store = $this->getStore();
        if ($image->product->store_id !== $store->id) {
            abort(403);
        }

        Storage::disk('public')->delete($image->image_url);
        $image->delete();

        return response()->json(['success' => true]);
    }
}