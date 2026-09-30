@extends('admin.layouts.app')

@section('title', 'إضافة ماركة')
@section('page-title', 'إضافة ماركة جديدة')

@section('content')

    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">
        <a href="{{ route('admin.brands.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            الماركات
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">إضافة جديدة</span>
    </nav>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg">

            <div class="p-6 border-b border-stone-200 dark:border-stone-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <i class="fa-solid fa-tag text-lg"></i>
                    </div>
                    <div>
                        <h2 class="font-display text-lg font-bold text-ink dark:text-cream">معلومات الماركة</h2>
                        <p class="text-xs text-ink-muted dark:text-cream/60">أدخل بيانات الماركة الجديدة</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                <div>
                    <label class="form-label">اسم الماركة <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="مثال: Nike, Adidas, Zara"
                           class="form-input">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">الوصف <span class="text-ink-faint dark:text-cream/30">(اختياري)</span></label>
                    <textarea name="description" rows="3" maxlength="500"
                              placeholder="وصف مختصر للماركة..."
                              class="form-input h-auto py-3 resize-none">{{ old('description') }}</textarea>
                    @error('description') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">شعار الماركة <span class="text-ink-faint dark:text-cream/30">(اختياري)</span></label>
                    <input type="file" name="logo" accept="image/*"
                           class="form-input file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-forest-50 dark:file:bg-forest-950/40 file:text-forest-700 dark:file:text-gold-400 hover:file:bg-forest-100 dark:hover:file:bg-forest-900/50">
                    <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">JPG, PNG, SVG — أقل من 1MB</p>
                    @error('logo') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-5 border-t border-stone-200 dark:border-stone-800">
                    <a href="{{ route('admin.brands.index') }}" class="btn-outline">
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