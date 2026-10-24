<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * عرض جميع الماركات
     */
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        // بحث
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $brands = $query->latest()->paginate(15);

        return view('merchant.brands.index', compact('brands'));
    }

    /**
     * نموذج إضافة ماركة
     */
    public function create()
    {
        return view('merchant.brands.create');
    }

    /**
     * حفظ ماركة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:1024',
        ], [
            'name.required' => 'اسم الماركة مطلوب',
            'name.unique' => 'هذه الماركة موجودة بالفعل',
            'name.max' => 'اسم الماركة طويل جداً',
            'logo.image' => 'الملف يجب أن يكون صورة',
            'logo.max' => 'حجم الصورة أقل من 1MB',
            'logo.mimes' => 'الصيغ المدعومة: JPG, PNG, WEBP, SVG',
        ]);

        // رفع الشعار
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($validated);

        return redirect()
            ->route('merchant.brands.index')
            ->with('success', 'تم إضافة الماركة بنجاح');
    }
}