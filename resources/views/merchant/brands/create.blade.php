@extends('merchant.layouts.app')

@section('title', 'إضافة ماركة')
@section('page-title', 'إضافة ماركة جديدة')

@section('content')

    {{-- Breadcrumb --}}
    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
        <a href="{{ route('merchant.brands.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            الماركات
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">إضافة جديدة</span>
    </nav>

    {{-- Form --}}
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
            
            {{-- Header --}}
            <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <i class="fa-solid fa-tag"></i>
                </div>
                <div>
                    <h3 class="font-display text-lg font-bold text-ink dark:text-cream">معلومات الماركة</h3>
                    <p class="text-xs text-ink-muted dark:text-cream/60">أضف ماركة جديدة للمنصة</p>
                </div>
            </div>

            {{-- Form Body --}}
            <form action="{{ route('merchant.brands.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label class="form-label">
                        اسم الماركة <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="مثال: Nike, Adidas, Zara"
                           class="form-input">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="form-label">الوصف (اختياري)</label>
                    <textarea name="description" rows="3" maxlength="500"
                              placeholder="وصف مختصر للماركة..."
                              class="form-input h-auto py-3 resize-none">{{ old('description') }}</textarea>
                    <p class="text-xs text-ink-muted dark:text-cream/40 mt-1">
                        <i class="fa-solid fa-info-circle text-[10px]"></i>
                        الحد الأقصى 500 حرف
                    </p>
                    @error('description') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Logo --}}
                <div>
                    <label class="form-label">شعار الماركة (اختياري)</label>
                    <input type="file" name="logo" accept="image/*"
                           class="form-input h-auto py-2">
                    <p class="text-xs text-ink-muted dark:text-cream/40 mt-1">
                        <i class="fa-solid fa-image text-[10px]"></i>
                        JPG, PNG, SVG, WEBP — أقل من 1MB
                    </p>
                    @error('logo') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Info Alert --}}
                <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/50 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5"></i>
                        <div class="text-xs text-blue-700 dark:text-blue-300 leading-relaxed">
                            <p class="font-medium mb-1">ملاحظة</p>
                            <p>الماركة ستكون متاحة لجميع التجار والمنتجات في المنصة بعد إضافتها.</p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-stone-200 dark:border-stone-800">
                    <a href="{{ route('merchant.brands.index') }}" class="btn-outline">
                        <i class="fa-solid fa-xmark"></i>
                        إلغاء
                    </a>
                    <button type="submit" class="btn-solid">
                        <i class="fa-solid fa-check"></i>
                        حفظ الماركة
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection