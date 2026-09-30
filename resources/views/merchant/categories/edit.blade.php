@extends('merchant.layouts.app')

@section('title', 'تعديل تصنيف')
@section('page-title', 'تعديل التصنيف')

@section('content')

    {{-- ═══ Breadcrumb ═══ --}}
    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
        <a href="{{ route('merchant.dashboard') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            لوحة التحكم
        </a>
        <span class="opacity-40">/</span>
        <a href="{{ route('merchant.categories.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            التصنيفات
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">{{ $category->name }}</span>
    </nav>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">

            {{-- Header --}}
            <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-lg font-bold text-ink dark:text-cream">تعديل: {{ $category->name }}</h2>
                    <p class="text-xs text-ink-muted dark:text-cream/60">حدّث بيانات التصنيف</p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('merchant.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Name --}}
                    <div class="md:col-span-2">
                        <label for="name" class="form-label">
                            اسم التصنيف <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                               class="form-input">
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Parent Category --}}
                    <div>
                        <label for="parent_id" class="form-label">التصنيف الأب (اختياري)</label>
                        <select name="parent_id" id="parent_id" class="form-input">
                            <option value="">— بدون تصنيف أب —</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Sort Order --}}
                    <div>
                        <label for="sort_order" class="form-label">ترتيب العرض</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}"
                               min="0" class="form-input">
                        @error('sort_order') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Image --}}
                    <div class="md:col-span-2">
                        <label for="image" class="form-label">صورة التصنيف</label>

                        @if($category->image)
                            <div class="mb-3 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded flex items-center gap-4">
                                <img src="{{ asset('storage/' . $category->image) }}"
                                     alt="{{ $category->name }}"
                                     class="w-16 h-16 rounded object-cover">
                                <div>
                                    <p class="text-xs font-medium text-ink dark:text-cream">الصورة الحالية</p>
                                    <p class="text-xs text-ink-muted dark:text-cream/50">ارفع صورة جديدة لاستبدالها</p>
                                </div>
                            </div>
                        @endif

                        <input type="file" name="image" id="image" accept="image/*"
                               class="form-input file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-forest-50 dark:file:bg-forest-950/40 file:text-forest-700 dark:file:text-gold-400 hover:file:bg-forest-100 dark:hover:file:bg-forest-900/50">
                        <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">اتركها فارغة للإبقاء على الصورة الحالية</p>
                        @error('image') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2">
                        <label for="status" class="form-label">
                            الحالة <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required class="form-input">
                            <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>
                                ✅ نشط
                            </option>
                            <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>
                                ⛔ معطل
                            </option>
                        </select>
                        @error('status') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse sm:flex-row justify-between gap-3 mt-8 pt-6 border-t border-stone-200 dark:border-stone-800">
                    <button type="submit"
                            form="delete-category-form"
                            class="inline-flex items-center justify-center gap-2 h-12 px-6 text-xs font-semibold tracking-widest uppercase text-red-600 dark:text-red-400 border border-stone-300 dark:border-stone-700 hover:border-red-300 dark:hover:border-red-800 hover:bg-red-50 dark:hover:bg-red-950/20 rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        حذف التصنيف
                    </button>

                    <div class="flex gap-3">
                        <a href="{{ route('merchant.categories.index') }}" class="btn-outline justify-center">
                            إلغاء
                        </a>
                        <button type="submit" class="btn-solid justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            حفظ التعديلات
                        </button>
                    </div>
                </div>
            </form>

            {{-- Delete Form (separate) --}}
            <form id="delete-category-form"
                  action="{{ route('merchant.categories.destroy', $category) }}"
                  method="POST"
                  onsubmit="return confirm('هل أنت متأكد من حذف التصنيف؟ لا يمكن التراجع عن هذا الإجراء.')"
                  class="hidden">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>

@endsection