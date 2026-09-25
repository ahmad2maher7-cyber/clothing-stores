<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $brands = $query->latest()->paginate(15);

        $stats = [
            'total' => Brand::count(),
            'with_products' => Brand::has('products')->count(),
            'empty' => Brand::doesntHave('products')->count(),
        ];

        return view('admin.brands.index', compact('brands', 'stats'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:1024',
        ], [
            'name.required' => 'اسم الماركة مطلوب',
            'name.unique' => 'هذا الاسم موجود بالفعل',
            'logo.image' => 'الملف يجب أن يكون صورة',
            'logo.max' => 'حجم الصورة أقل من 1MB',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($validated);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'تم إضافة الماركة بنجاح');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($validated);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'تم تحديث الماركة بنجاح');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف ماركة مرتبطة بمنتجات');
        }

        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'تم حذف الماركة بنجاح');
    }
}
