@extends('merchant.layouts.app')

@section('title', 'تعديل عرض')
@section('page-title', 'تعديل العرض')

@section('content')

    {{-- ═══ Breadcrumb ═══ --}}
    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
        <a href="{{ route('merchant.dashboard') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            لوحة التحكم
        </a>
        <span class="opacity-40">/</span>
        <a href="{{ route('merchant.offers.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            العروض
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">{{ $offer->title }}</span>
    </nav>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">

            {{-- Header --}}
            <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-lg font-bold text-ink dark:text-cream">تعديل العرض</h2>
                    <p class="text-xs text-ink-muted dark:text-cream/60">{{ $offer->title }}</p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('merchant.offers.update', $offer) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label class="form-label">
                            عنوان العرض <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $offer->title) }}" required
                               placeholder="مثال: تخفيضات نهاية الموسم"
                               class="form-input">
                        @error('title') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" rows="3" maxlength="1000"
                                  placeholder="اكتب وصفاً جاذباً للعرض..."
                                  class="form-input h-auto py-3 resize-none">{{ old('description', $offer->description) }}</textarea>
                        @error('description') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Discount --}}
                    <div>
                        <label class="form-label">
                            نسبة الخصم (%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="discount_percent" 
                               value="{{ old('discount_percent', $offer->discount_percent) }}" 
                               required min="1" max="100"
                               class="form-input">
                        @error('discount_percent') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Banner --}}
                    <div>
                        <label class="form-label">صورة العرض (Banner)</label>

                        @if($offer->banner)
                            <div class="mb-3 p-3 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded">
                                <img src="{{ asset('storage/' . $offer->banner) }}"
                                     alt="{{ $offer->title }}"
                                     class="w-full h-24 object-cover rounded">
                                <p class="text-xs text-ink-muted dark:text-cream/50 mt-2">ارفع صورة جديدة لاستبدالها</p>
                            </div>
                        @endif

                        <input type="file" name="banner" accept="image/*"
                               class="form-input file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-forest-50 dark:file:bg-forest-950/40 file:text-forest-700 dark:file:text-gold-400 hover:file:bg-forest-100 dark:hover:file:bg-forest-900/50">
                        @error('banner') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Start Date --}}
                    <div>
                        <label class="form-label">
                            تاريخ البداية <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" 
                               value="{{ old('start_date', $offer->start_date->format('Y-m-d')) }}" required
                               class="form-input">
                        @error('start_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- End Date --}}
                    <div>
                        <label class="form-label">
                            تاريخ الانتهاء <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date" 
                               value="{{ old('end_date', $offer->end_date->format('Y-m-d')) }}" required
                               class="form-input">
                        @error('end_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse sm:flex-row justify-between gap-3 mt-8 pt-6 border-t border-stone-200 dark:border-stone-800">
                    <button type="submit"
                            form="delete-offer-form"
                            class="inline-flex items-center justify-center gap-2 h-12 px-6 text-xs font-semibold tracking-widest uppercase text-red-600 dark:text-red-400 border border-stone-300 dark:border-stone-700 hover:border-red-300 dark:hover:border-red-800 hover:bg-red-50 dark:hover:bg-red-950/20 rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        حذف العرض
                    </button>

                    <div class="flex gap-3">
                        <a href="{{ route('merchant.offers.index') }}" class="btn-outline justify-center">
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
            <form id="delete-offer-form"
                  action="{{ route('merchant.offers.destroy', $offer) }}"
                  method="POST"
                  onsubmit="return confirm('هل أنت متأكد من حذف العرض؟ لا يمكن التراجع.')"
                  class="hidden">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>

@endsection