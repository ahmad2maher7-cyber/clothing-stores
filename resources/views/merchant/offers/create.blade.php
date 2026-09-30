@extends('merchant.layouts.app')

@section('title', 'إضافة عرض جديد')
@section('page-title', 'إضافة عرض جديد')

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
        <span class="text-ink dark:text-cream">إضافة جديد</span>
    </nav>

    <div x-data="offerForm()">

        <form action="{{ route('merchant.offers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ═══ Main Column ═══ --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Info Card --}}
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                        <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                            <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display text-lg font-bold text-ink dark:text-cream">معلومات العرض</h3>
                                <p class="text-xs text-ink-muted dark:text-cream/60">أدخل تفاصيل العرض الترويجي</p>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">

                            {{-- Title --}}
                            <div>
                                <label class="form-label">
                                    عنوان العرض <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" x-model="title" required maxlength="255"
                                       placeholder="مثال: تخفيضات نهاية الموسم"
                                       class="form-input">
                                @error('title') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label class="form-label">الوصف</label>
                                <textarea name="description" x-model="description" rows="3" maxlength="1000"
                                          placeholder="اكتب وصفاً جاذباً للعرض..."
                                          class="form-input h-auto py-3 resize-none"></textarea>
                                <p class="text-xs text-ink-muted dark:text-cream/40 mt-2 text-left">
                                    <span x-text="description.length"></span> / 1000
                                </p>
                            </div>

                            {{-- Discount Percent --}}
                            <div>
                                <label class="form-label">
                                    نسبة الخصم <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <input type="range" name="discount_percent" x-model="discount_percent"
                                           min="1" max="100" step="1"
                                           class="flex-1 h-2 bg-stone-200 dark:bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-forest-700 dark:accent-gold-500">
                                    <div class="flex items-center gap-1 bg-forest-50 dark:bg-forest-950/40 rounded px-4 py-2 min-w-[100px] justify-center">
                                        <span class="font-display text-2xl font-bold text-forest-700 dark:text-gold-400" x-text="discount_percent"></span>
                                        <span class="text-forest-700 dark:text-gold-400 font-bold">%</span>
                                    </div>
                                </div>

                                {{-- Quick Buttons --}}
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <template x-for="percent in [10, 15, 20, 25, 30, 40, 50]" :key="percent">
                                        <button type="button" @click="discount_percent = percent"
                                                :class="discount_percent == percent 
                                                        ? 'bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950' 
                                                        : 'bg-stone-100 dark:bg-zinc-800 text-ink-soft dark:text-cream/70 hover:bg-forest-50 dark:hover:bg-forest-950/40 hover:text-forest-700 dark:hover:text-gold-400'"
                                                class="text-xs font-medium px-3 py-1.5 rounded transition-colors">
                                            <span x-text="percent"></span>%
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Date Range Card --}}
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                        <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                            <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display text-lg font-bold text-ink dark:text-cream">فترة العرض</h3>
                            </div>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">
                                    تاريخ البداية <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="start_date" x-ref="startDate"
                                       value="{{ date('Y-m-d') }}" required
                                       class="form-input">
                            </div>

                            <div>
                                <label class="form-label">
                                    تاريخ الانتهاء <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="end_date" x-ref="endDate"
                                       value="{{ date('Y-m-d', strtotime('+30 days')) }}" required
                                       class="form-input">
                            </div>

                            {{-- Quick Duration --}}
                            <div class="md:col-span-2">
                                <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-3">
                                    مدة سريعة
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach([3 => '3 أيام', 7 => 'أسبوع', 14 => 'أسبوعان', 30 => 'شهر', 90 => '3 أشهر'] as $days => $label)
                                        <button type="button" @click="setDuration({{ $days }})"
                                                class="bg-stone-100 dark:bg-zinc-800 hover:bg-forest-50 dark:hover:bg-forest-950/40 hover:text-forest-700 dark:hover:text-gold-400 text-ink-soft dark:text-cream/70 text-xs font-medium px-3 py-2 rounded transition-colors">
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ Sidebar ═══ --}}
                <div class="space-y-6">

                    {{-- Banner Upload --}}
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-display font-bold text-ink dark:text-cream">صورة العرض</h3>
                        </div>

                        <div @click="$refs.bannerInput.click()"
                             class="border-2 border-dashed border-stone-300 dark:border-stone-700 rounded-lg p-6 text-center cursor-pointer hover:border-forest-500 dark:hover:border-gold-500 transition-colors"
                             :class="bannerPreview ? 'border-solid' : ''">
                            <template x-if="!bannerPreview">
                                <div>
                                    <svg class="w-12 h-12 mx-auto mb-3 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-ink dark:text-cream mb-1">اضغط لاختيار صورة</p>
                                    <p class="text-xs text-ink-muted dark:text-cream/50">JPG, PNG — أقل من 3MB</p>
                                </div>
                            </template>
                            <template x-if="bannerPreview">
                                <img :src="bannerPreview" class="w-full h-40 object-cover rounded">
                            </template>
                        </div>

                        <input type="file" name="banner" x-ref="bannerInput"
                               @change="handleBanner($event)"
                               accept="image/*" class="hidden">

                        <button type="button" x-show="bannerPreview" x-cloak
                                @click="removeBanner()"
                                class="mt-3 w-full inline-flex items-center justify-center gap-2 h-10 px-3 text-xs font-medium text-red-600 dark:text-red-400 border border-stone-300 dark:border-stone-700 hover:border-red-300 dark:hover:border-red-800 hover:bg-red-50 dark:hover:bg-red-950/20 rounded transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            حذف الصورة
                        </button>

                        @error('banner') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Live Preview --}}
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6 sticky top-4">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <h3 class="font-display font-bold text-ink dark:text-cream">معاينة مباشرة</h3>
                        </div>

                        <div class="border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                            {{-- Banner --}}
                            <template x-if="bannerPreview">
                                <img :src="bannerPreview" class="w-full h-32 object-cover">
                            </template>
                            <template x-if="!bannerPreview">
                                <div class="w-full h-32 bg-gradient-to-br from-forest-700 to-gold-500 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                    </svg>
                                </div>
                            </template>

                            {{-- Content --}}
                            <div class="p-3">
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <h4 class="font-display font-bold text-sm text-ink dark:text-cream line-clamp-2" 
                                        x-text="title || 'عنوان العرض'"></h4>
                                    <span class="badge badge-dark text-xs whitespace-nowrap"
                                          x-text="'-' + discount_percent + '%'"></span>
                                </div>
                                <p class="text-xs text-ink-muted dark:text-cream/60 line-clamp-2 mb-2" 
                                   x-text="description || 'وصف العرض سيظهر هنا...'"></p>
                                <div class="pt-2 border-t border-stone-100 dark:border-stone-800">
                                    <span class="badge badge-forest">🔥 نشط</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ Actions ═══ --}}
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-6">
                <a href="{{ route('merchant.offers.index') }}" class="btn-outline justify-center">
                    إلغاء
                </a>
                <button type="submit" class="btn-solid justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    إنشاء العرض
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
function offerForm() {
    return {
        title: '',
        description: '',
        discount_percent: 20,
        bannerPreview: null,

        handleBanner(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                this.bannerPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        removeBanner() {
            this.bannerPreview = null;
            this.$refs.bannerInput.value = '';
        },

        setDuration(days) {
            const start = new Date();
            const end = new Date();
            end.setDate(end.getDate() + days);

            const formatDate = (date) => date.toISOString().split('T')[0];

            this.$refs.startDate.value = formatDate(start);
            this.$refs.endDate.value = formatDate(end);
        }
    }
}
</script>
@endpush