@extends('merchant.layouts.app')

@section('title', 'تعديل ماركة')
@section('page-title', 'تعديل الماركة')

@section('content')

    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
        <a href="{{ route('merchant.brands.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            الماركات
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">{{ $brand->name }}</span>
    </nav>

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- ═══════════════════════════════════════════ --}}
        {{-- 1. FORM التعديل (منفصل) --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg">
            <div class="p-6 border-b border-stone-200 dark:border-stone-800">
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream">
                    <i class="fa-solid fa-pen text-forest-700 dark:text-gold-400"></i>
                    تعديل: {{ $brand->name }}
                </h3>
            </div>

            <form action="{{ route('merchant.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label">اسم الماركة <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $brand->name) }}" required
                           class="form-input">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">الوصف (اختياري)</label>
                    <textarea name="description" rows="3" maxlength="500"
                              class="form-input h-auto py-3 resize-none">{{ old('description', $brand->description) }}</textarea>
                    @error('description') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">شعار الماركة</label>
                    @if($brand->logo)
                        <div class="mb-3 p-4 bg-stone-50 dark:bg-zinc-800 rounded-md border border-stone-200 dark:border-stone-700">
                            <p class="text-xs text-ink-muted dark:text-cream/50 mb-2">الشعار الحالي:</p>
                            <img src="{{ asset('storage/' . $brand->logo) }}"
                                 class="max-h-24 object-contain">
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*"
                           class="form-input h-auto py-2">
                    <p class="text-xs text-ink-muted dark:text-cream/50 mt-2">
                        اتركها فارغة للإبقاء على الشعار الحالي
                    </p>
                    @error('logo') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-5 border-t border-stone-200 dark:border-stone-800">
                    <a href="{{ route('merchant.brands.index') }}" class="btn-outline">
                        إلغاء
                    </a>
                    <button type="submit" class="btn-solid">
                        <i class="fa-solid fa-check"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- 2. FORM الحذف (منفصل تماماً) --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 rounded-lg">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 rounded shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-display font-bold text-red-800 dark:text-red-300 mb-1">
                            منطقة الخطر
                        </h3>
                        <p class="text-sm text-red-700 dark:text-red-400 mb-4">
                            حذف الماركة نهائياً. لا يمكن التراجع.
                            @if($brand->products_count ?? $brand->products()->count() > 0)
                                <br>
                                <strong>⚠️ هذه الماركة مرتبطة بـ {{ $brand->products()->count() }} منتج — لن يتمكن النظام من حذفها.</strong>
                            @endif
                        </p>

                        <form action="{{ route('merchant.brands.destroy', $brand) }}" method="POST"
                              onsubmit="return confirm('هل أنت متأكد من حذف الماركة نهائياً؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-2 h-12 px-6 text-xs font-semibold uppercase bg-red-600 hover:bg-red-700 text-white rounded transition
                                           {{ ($brand->products()->count() > 0) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ ($brand->products()->count() > 0) ? 'disabled' : '' }}>
                                <i class="fa-solid fa-trash"></i>
                                حذف الماركة نهائياً
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection