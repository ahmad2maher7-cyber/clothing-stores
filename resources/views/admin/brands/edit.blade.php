@extends('admin.layouts.app')

@section('title', 'تعديل ماركة')
@section('page-title', 'تعديل الماركة')

@section('content')

    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">
        <a href="{{ route('admin.brands.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            الماركات
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">{{ $brand->name }}</span>
    </nav>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg">

            <div class="p-6 border-b border-stone-200 dark:border-stone-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <i class="fa-solid fa-tag text-lg"></i>
                    </div>
                    <div>
                        <h2 class="font-display text-lg font-bold text-ink dark:text-cream">تعديل: {{ $brand->name }}</h2>
                        <p class="text-xs text-ink-muted dark:text-cream/60">حدّث بيانات الماركة</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label">اسم الماركة <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $brand->name) }}" required
                           class="form-input">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">الوصف <span class="text-ink-faint dark:text-cream/30">(اختياري)</span></label>
                    <textarea name="description" rows="3" maxlength="500"
                              class="form-input h-auto py-3 resize-none">{{ old('description', $brand->description) }}</textarea>
                    @error('description') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">شعار الماركة <span class="text-ink-faint dark:text-cream/30">(اختياري)</span></label>

                    @if($brand->logo)
                        <div class="mb-3 p-4 bg-stone-50 dark:bg-zinc-950 rounded border border-stone-200 dark:border-stone-800 flex items-center gap-4">
                            <img src="{{ asset('storage/' . $brand->logo) }}"
                                 alt="{{ $brand->name }}"
                                 class="h-16 w-16 object-contain">
                            <div>
                                <p class="text-xs font-medium text-ink dark:text-cream">الشعار الحالي</p>
                                <p class="text-xs text-ink-muted dark:text-cream/50">ارفع شعاراً جديداً لاستبداله</p>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="logo" accept="image/*"
                           class="form-input file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-forest-50 dark:file:bg-forest-950/40 file:text-forest-700 dark:file:text-gold-400 hover:file:bg-forest-100 dark:hover:file:bg-forest-900/50">
                    <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">اتركها فارغة للإبقاء على الشعار الحالي</p>
                    @error('logo') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-between gap-3 pt-5 border-t border-stone-200 dark:border-stone-800">
                    <button type="submit"
                            form="delete-brand-form"
                            class="inline-flex items-center justify-center gap-2 h-12 px-6 text-xs font-semibold tracking-widest uppercase text-red-600 dark:text-red-400 border border-stone-300 dark:border-stone-700 hover:border-red-300 dark:hover:border-red-800 hover:bg-red-50 dark:hover:bg-red-950/20 rounded transition-colors">
                        <i class="fa-solid fa-trash"></i>
                        حذف
                    </button>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.brands.index') }}" class="btn-outline">
                            إلغاء
                        </a>
                        <button type="submit" class="btn-solid">
                            <i class="fa-solid fa-check"></i>
                            حفظ التعديلات
                        </button>
                    </div>
                </div>
            </form>

            <form id="delete-brand-form"
                  action="{{ route('admin.brands.destroy', $brand) }}"
                  method="POST"
                  onsubmit="return confirm('هل أنت متأكد من حذف الماركة؟ سيتم حذف جميع المنتجات المرتبطة بها إذا لم يكن هناك حماية.')"
                  class="hidden">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>

@endsection