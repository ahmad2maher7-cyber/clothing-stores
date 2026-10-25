@extends('merchant.layouts.app')

@section('title', 'إضافة ماركة')
@section('page-title', 'إضافة ماركة جديدة')

@section('content')

    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
        <a href="{{ route('merchant.brands.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            الماركات
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">إضافة جديدة</span>
    </nav>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg">
            <div class="p-6 border-b border-stone-200 dark:border-stone-800">
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream">
                    <i class="fa-solid fa-tag text-forest-700 dark:text-gold-400"></i>
                    معلومات الماركة
                </h3>
            </div>

            <form action="{{ route('merchant.brands.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                <div>
                    <label class="form-label">اسم الماركة <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="مثال: Nike, Adidas, Zara"
                           class="form-input">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">الوصف (اختياري)</label>
                    <textarea name="description" rows="3" maxlength="500"
                              placeholder="وصف مختصر للماركة..."
                              class="form-input h-auto py-3 resize-none">{{ old('description') }}</textarea>
                    @error('description') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">شعار الماركة (اختياري)</label>
                    <input type="file" name="logo" accept="image/*"
                           class="form-input h-auto py-2">
                    <p class="text-xs text-ink-muted dark:text-cream/50 mt-2">
                        <i class="fa-solid fa-info-circle"></i>
                        JPG, PNG, SVG — أقل من 2MB
                    </p>
                    @error('logo') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-5 border-t border-stone-200 dark:border-stone-800">
                    <a href="{{ route('merchant.brands.index') }}" class="btn-outline">
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