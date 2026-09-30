@extends('merchant.layouts.app')

@section('title', 'إنشاء متجر')
@section('page-title', 'إنشاء متجرك')

@section('content')

    <div class="max-w-3xl mx-auto">

        {{-- ═══ Welcome Banner ═══ --}}
        <div class="bg-forest-900 dark:bg-forest-950 text-white rounded-lg p-8 mb-6 text-center relative overflow-hidden">
            {{-- Decorative --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative">
                <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-white/10 backdrop-blur rounded-2xl">
                    <svg class="w-8 h-8 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h1 class="font-display text-3xl font-bold mb-2">ابدأ رحلتك التجارية</h1>
                <p class="text-cream/70">أنشئ متجرك الأول وابدأ البيع خلال دقائق</p>
            </div>
        </div>

        {{-- ═══ Steps ═══ --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 mb-6">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 flex items-center justify-center bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 rounded-full font-bold text-xs">
                        1
                    </div>
                    <span class="font-medium text-forest-700 dark:text-gold-400 hidden sm:inline">معلومات المتجر</span>
                </div>
                <div class="flex-1 border-t-2 border-dashed border-stone-300 dark:border-stone-700 mx-3"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 flex items-center justify-center bg-stone-200 dark:bg-zinc-800 text-ink-muted dark:text-cream/50 rounded-full font-bold text-xs">
                        2
                    </div>
                    <span class="text-ink-muted dark:text-cream/50 hidden sm:inline">المراجعة</span>
                </div>
                <div class="flex-1 border-t-2 border-dashed border-stone-300 dark:border-stone-700 mx-3"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 flex items-center justify-center bg-stone-200 dark:bg-zinc-800 text-ink-muted dark:text-cream/50 rounded-full font-bold text-xs">
                        3
                    </div>
                    <span class="text-ink-muted dark:text-cream/50 hidden sm:inline">التفعيل</span>
                </div>
            </div>
        </div>

        {{-- ═══ Form ═══ --}}
        <form action="{{ route('merchant.store.store') }}" method="POST" enctype="multipart/form-data"
              class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
            @csrf

            {{-- Header --}}
            <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-display font-bold text-ink dark:text-cream">معلومات المتجر</h3>
                    <p class="text-xs text-ink-muted dark:text-cream/60">املأ البيانات التالية بدقة</p>
                </div>
            </div>

            {{-- Fields --}}
            <div class="p-6 space-y-5">

                {{-- Name --}}
                <div>
                    <label class="form-label">
                        اسم المتجر <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="مثال: متجر الأناقة"
                           class="form-input">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="form-label">الوصف</label>
                    <textarea name="description" rows="3" maxlength="1000"
                              placeholder="وصف مختصر وجاذب لمتجرك..."
                              class="form-input h-auto py-3 resize-none">{{ old('description') }}</textarea>
                    @error('description') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Phone + Commercial Register --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">
                            رقم الهاتف <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                               placeholder="0599000000" dir="ltr"
                               class="form-input text-left">
                        @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">السجل التجاري</label>
                        <input type="text" name="commercial_register" value="{{ old('commercial_register') }}"
                               placeholder="CR-12345"
                               class="form-input">
                    </div>
                </div>

                {{-- Address --}}
                <div>
                    <label class="form-label">
                        العنوان <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" rows="2" required maxlength="500"
                              placeholder="المدينة - الحي - الشارع"
                              class="form-input h-auto py-3 resize-none">{{ old('address') }}</textarea>
                    @error('address') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Logo --}}
                <div>
                    <label class="form-label">شعار المتجر</label>
                    <input type="file" name="logo" accept="image/*"
                           class="form-input file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-forest-50 dark:file:bg-forest-950/40 file:text-forest-700 dark:file:text-gold-400 hover:file:bg-forest-100 dark:hover:file:bg-forest-900/50">
                    <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">JPG, PNG — أقل من 2MB</p>
                    @error('logo') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Banner --}}
                <div>
                    <label class="form-label">غلاف المتجر</label>
                    <input type="file" name="banner" accept="image/*"
                           class="form-input file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-forest-50 dark:file:bg-forest-950/40 file:text-forest-700 dark:file:text-gold-400 hover:file:bg-forest-100 dark:hover:file:bg-forest-900/50">
                    <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">JPG, PNG — أقل من 3MB — مقاس مقترح 1200×400</p>
                    @error('banner') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- ═══ Notice ═══ --}}
                <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 rounded-lg p-5">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 rounded-full shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="text-sm">
                            <p class="font-bold text-amber-800 dark:text-amber-300 mb-1">مراجعة إلزامية</p>
                            <p class="text-amber-700 dark:text-amber-400/80 leading-relaxed">
                                بعد إرسال الطلب، سيتم مراجعته من قبل الإدارة.
                                لا يمكنك إضافة منتجات أو استقبال طلبات حتى تتم الموافقة.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="p-6 border-t border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-zinc-950 flex justify-end">
                <button type="submit" class="btn-solid">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    إرسال طلب الإنشاء
                </button>
            </div>
        </form>
    </div>

@endsection