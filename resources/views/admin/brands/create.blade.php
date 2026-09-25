@extends('admin.layouts.app')

@section('title', 'إضافة ماركة')
@section('page-title', 'إضافة ماركة جديدة')

@section('content')

    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('admin.brands.index') }}" class="hover:text-gray-900">الماركات</a>
        <span class="mx-2 text-gray-300">/</span>
        <span class="text-gray-900">إضافة جديدة</span>
    </nav>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">معلومات الماركة</h2>
            </div>

            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                <div>
                    <label class="form-label">اسم الماركة *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="مثال: Nike, Adidas, Zara"
                           class="form-input">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">الوصف (اختياري)</label>
                    <textarea name="description" rows="3" maxlength="500"
                              placeholder="وصف مختصر للماركة..."
                              class="form-input">{{ old('description') }}</textarea>
                    @error('description') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">شعار الماركة (اختياري)</label>
                    <input type="file" name="logo" accept="image/*"
                           class="form-input">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, SVG — أقل من 1MB</p>
                    @error('logo') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-5 border-t border-gray-200">
                    <a href="{{ route('admin.brands.index') }}" class="btn-secondary">
                        إلغاء
                    </a>
                    <button type="submit" class="btn-primary">
                        💾 حفظ الماركة
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection