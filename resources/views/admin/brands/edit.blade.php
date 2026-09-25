@extends('admin.layouts.app')

@section('title', 'تعديل ماركة')
@section('page-title', 'تعديل الماركة')

@section('content')

    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('admin.brands.index') }}" class="hover:text-gray-900">الماركات</a>
        <span class="mx-2 text-gray-300">/</span>
        <span class="text-gray-900">{{ $brand->name }}</span>
    </nav>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">تعديل: {{ $brand->name }}</h2>
            </div>

            <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label">اسم الماركة *</label>
                    <input type="text" name="name" value="{{ old('name', $brand->name) }}" required
                           class="form-input">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">الوصف (اختياري)</label>
                    <textarea name="description" rows="3" maxlength="500"
                              class="form-input">{{ old('description', $brand->description) }}</textarea>
                    @error('description') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">شعار الماركة (اختياري)</label>

                    @if($brand->logo)
                        <div class="mb-3 p-4 bg-gray-50 rounded-md border border-gray-200">
                            <p class="text-xs text-gray-500 mb-2">الشعار الحالي:</p>
                            <img src="{{ asset('storage/' . $brand->logo) }}" 
                                 class="max-h-24 object-contain">
                        </div>
                    @endif

                    <input type="file" name="logo" accept="image/*" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">اتركها فارغة للإبقاء على الشعار الحالي</p>
                    @error('logo') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-between gap-3 pt-5 border-t border-gray-200">
                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST"
                          onsubmit="return confirm('هل أنت متأكد من حذف الماركة؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">
                            🗑️ حذف
                        </button>
                    </form>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.brands.index') }}" class="btn-secondary">
                            إلغاء
                        </a>
                        <button type="submit" class="btn-primary">
                            💾 حفظ التعديلات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
