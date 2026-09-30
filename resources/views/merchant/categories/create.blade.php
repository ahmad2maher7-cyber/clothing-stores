@extends('merchant.layouts.app')

@section('title', 'إضافة تصنيف')
@section('page-title', 'إضافة تصنيف جديد')

@section('content')

    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
        <a href="{{ route('merchant.dashboard') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            لوحة التحكم
        </a>
        <span class="opacity-40">/</span>
        <a href="{{ route('merchant.categories.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            التصنيفات
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">إضافة جديد</span>
    </nav>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">

            <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <i class="fa-solid fa-folder-plus text-lg"></i>
                </div>
                <div>
                    <h2 class="font-display text-lg font-bold text-ink dark:text-cream">معلومات التصنيف</h2>
                    <p class="text-xs text-ink-muted dark:text-cream/60">أدخل بيانات التصنيف الجديد</p>
                </div>
            </div>

            <form action="{{ route('merchant.categories.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="md:col-span-2">
                        <label for="name" class="form-label">
                            اسم التصنيف <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="form-input"
                               placeholder="مثال: قمصان، فساتين، أحذية">
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="parent_id" class="form-label">التصنيف الأب (اختياري)</label>
                        <select name="parent_id" id="parent_id" class="form-input">
                            <option value="">— بدون تصنيف أب —</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sort_order" class="form-label">ترتيب العرض</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}"
                               min="0" class="form-input">
                        @error('sort_order') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="image" class="form-label">صورة التصنيف</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="form-input file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-forest-50 dark:file:bg-forest-950/40 file:text-forest-700 dark:file:text-gold-400 hover:file:bg-forest-100 dark:hover:file:bg-forest-900/50">
                        <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">JPG, PNG, WEBP — أقل من 2MB</p>
                        @error('image') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="status" class="form-label">
                            الحالة <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required class="form-input">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                ✅ نشط
                            </option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                ⛔ معطل
                            </option>
                        </select>
                        @error('status') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-8 pt-6 border-t border-stone-200 dark:border-stone-800">
                    <a href="{{ route('merchant.categories.index') }}" class="btn-outline justify-center">
                        إلغاء
                    </a>
                    <button type="submit" class="btn-solid justify-center">
                        <i class="fa-solid fa-check"></i>
                        حفظ التصنيف
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection