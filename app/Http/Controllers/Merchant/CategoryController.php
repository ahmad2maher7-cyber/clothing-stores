<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * الحصول على المتجر الحالي
     */
    protected function getStore()
    {
        $store = auth()->user()->stores()->first();
        if (!$store) {
            abort(404, 'لا يوجد متجر مرتبط بحسابك');
        }
        return $store;
    }

    /**
     * عرض جميع التصنيفات
     */
    public function index(Request $request)
    {
        $store = $this->getStore();

        $query = $store->categories()->with('parent')->withCount('products');

        // البحث
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // الفلترة حسب الحالة
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $categories = $query->orderBy('sort_order')->paginate(15);

        return view('merchant.categories.index', compact('store', 'categories'));
    }

    /**
     * نموذج إنشاء تصنيف جديد
     */
    public function create()
    {
        $store = $this->getStore();
        $parents = $store->categories()->whereNull('parent_id')->get();

        return view('merchant.categories.create', compact('store', 'parents'));
    }

    /**
     * حفظ تصنيف جديد
     */
    public function store(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'اسم التصنيف مطلوب',
            'name.max' => 'اسم التصنيف طويل جداً',
            'parent_id.exists' => 'التصنيف الأب غير موجود',
            'image.image' => 'الملف يجب أن يكون صورة',
            'image.max' => 'حجم الصورة يجب أن يكون أقل من 2MB',
            'status.required' => 'حالة التصنيف مطلوبة',
        ]);

        // رفع الصورة
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // توليد slug فريد
        $validated['slug'] = Str::slug($validated['name']) . '-' . $store->id . '-' . uniqid();
        $validated['store_id'] = $store->id;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Category::create($validated);

        return redirect()
            ->route('merchant.categories.index')
            ->with('success', 'تم إضافة التصنيف بنجاح');
    }

    /**
     * عرض تفاصيل تصنيف
     */
    public function show(Category $category)
    {
        $store = $this->getStore();
        
        // التأكد أن التصنيف يخص المتجر
        if ($category->store_id !== $store->id) {
            abort(403, 'غير مصرح');
        }

        $category->load('parent', 'children', 'products');

        return view('merchant.categories.show', compact('store', 'category'));
    }

    /**
     * نموذج تعديل تصنيف
     */
    public function edit(Category $category)
    {
        $store = $this->getStore();
        
        if ($category->store_id !== $store->id) {
            abort(403, 'غير مصرح');
        }

        // التصنيفات الأب المتاحة (بدون التصنيف الحالي وأبنائه)
        $parents = $store->categories()
            ->whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('merchant.categories.edit', compact('store', 'category', 'parents'));
    }

    /**
     * تحديث تصنيف
     */
    public function update(Request $request, Category $category)
    {
        $store = $this->getStore();
        
        if ($category->store_id !== $store->id) {
            abort(403, 'غير مصرح');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        // منع التصنيف من أن يكون أباً لنفسه
        if (isset($validated['parent_id']) && $validated['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => 'لا يمكن للتصنيف أن يكون أباً لنفسه']);
        }

        // رفع صورة جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // تحديث slug إذا تغيّر الاسم
        if ($category->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . $store->id . '-' . uniqid();
        }

        $category->update($validated);

        return redirect()
            ->route('merchant.categories.index')
            ->with('success', 'تم تحديث التصنيف بنجاح');
    }

    /**
     * حذف تصنيف
     */
    public function destroy(Category $category)
    {
        $store = $this->getStore();
        
        if ($category->store_id !== $store->id) {
            abort(403, 'غير مصرح');
        }

        // التحقق من عدم وجود منتجات مرتبطة
        if ($category->products()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف التصنيف لوجود منتجات مرتبطة به');
        }

        // حذف الصورة
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // تحديث التصنيفات الفرعية لتصبح بدون أب
        $category->children()->update(['parent_id' => null]);

        $category->delete();

        return redirect()
            ->route('merchant.categories.index')
            ->with('success', 'تم حذف التصنيف بنجاح');
    }
}