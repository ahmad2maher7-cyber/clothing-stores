@extends('merchant.layouts.app')

@section('title', 'الهوية البصرية')
@section('page-title', 'الهوية البصرية')

@section('content')

    {{-- ═══ Tabs ═══ --}}
    @include('merchant.settings.partials.tabs', ['activeTab' => 'appearance'])

    <form action="{{ route('merchant.settings.appearance.update') }}" method="POST" x-data="appearanceForm()">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ═══ Colors ═══ --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Primary --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-ink dark:text-cream">اللون الأساسي</h3>
                            <p class="text-xs text-ink-muted dark:text-cream/50">للأزرار والروابط والعناصر النشطة</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">اختر اللون</label>
                            <input type="color" name="primary_color" x-model="primaryColor"
                                   value="{{ old('primary_color', $store->primary_color) }}"
                                   class="w-full h-16 border border-stone-300 dark:border-stone-700 rounded cursor-pointer">
                        </div>
                        <div>
                            <label class="form-label">أو أدخل HEX</label>
                            <input type="text" x-model="primaryColor" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$"
                                   class="form-input font-mono text-center"
                                   placeholder="#4A4A9D">
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded">
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-3">معاينة</p>
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" :style="'background-color: ' + primaryColor"
                                    class="text-white px-5 py-2 rounded text-sm font-medium">
                                زر أساسي
                            </button>
                            <a href="#" :style="'color: ' + primaryColor" class="text-sm underline">رابط أساسي</a>
                        </div>
                    </div>
                </div>

                {{-- Secondary --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 text-ink-muted dark:text-cream/60 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-ink dark:text-cream">اللون الثانوي</h3>
                            <p class="text-xs text-ink-muted dark:text-cream/50">للأزرار الثانوية والعناصر الأقل أهمية</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">اختر اللون</label>
                            <input type="color" name="secondary_color" x-model="secondaryColor"
                                   value="{{ old('secondary_color', $store->secondary_color) }}"
                                   class="w-full h-16 border border-stone-300 dark:border-stone-700 rounded cursor-pointer">
                        </div>
                        <div>
                            <label class="form-label">أو أدخل HEX</label>
                            <input type="text" x-model="secondaryColor" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$"
                                   class="form-input font-mono text-center"
                                   placeholder="#1E293B">
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded">
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-3">معاينة</p>
                        <button type="button" :style="'background-color: ' + secondaryColor"
                                class="text-white px-5 py-2 rounded text-sm font-medium">
                            زر ثانوي
                        </button>
                    </div>
                </div>

                {{-- Accent --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 flex items-center justify-center bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-ink dark:text-cream">لون التمييز</h3>
                            <p class="text-xs text-ink-muted dark:text-cream/50">للشارات المهمة (تخفيضات، جديد، مميز)</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">اختر اللون</label>
                            <input type="color" name="accent_color" x-model="accentColor"
                                   value="{{ old('accent_color', $store->accent_color) }}"
                                   class="w-full h-16 border border-stone-300 dark:border-stone-700 rounded cursor-pointer">
                        </div>
                        <div>
                            <label class="form-label">أو أدخل HEX</label>
                            <input type="text" x-model="accentColor" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$"
                                   class="form-input font-mono text-center"
                                   placeholder="#EF4444">
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded">
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-3">معاينة</p>
                        <span :style="'background-color: ' + accentColor"
                              class="text-white text-xs px-3 py-1.5 rounded-full font-bold">
                            -30%
                        </span>
                    </div>
                </div>

                {{-- Theme Mode --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 flex items-center justify-center bg-violet-50 dark:bg-violet-950/30 text-violet-600 dark:text-violet-400 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-ink dark:text-cream">وضع الثيم</h3>
                            <p class="text-xs text-ink-muted dark:text-cream/50">الوضع الافتراضي لصفحة متجرك</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="theme_mode" value="light" class="peer sr-only"
                                   {{ old('theme_mode', $store->theme_mode) === 'light' ? 'checked' : '' }}>
                            <div class="border-2 border-stone-300 dark:border-stone-700 rounded-lg p-4 text-center peer-checked:border-forest-700 dark:peer-checked:border-gold-500 peer-checked:bg-forest-50 dark:peer-checked:bg-forest-950/40 transition">
                                <div class="text-3xl mb-2">☀️</div>
                                <div class="font-medium text-sm text-ink dark:text-cream">فاتح</div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="theme_mode" value="dark" class="peer sr-only"
                                   {{ old('theme_mode', $store->theme_mode) === 'dark' ? 'checked' : '' }}>
                            <div class="border-2 border-stone-300 dark:border-stone-700 rounded-lg p-4 text-center peer-checked:border-forest-700 dark:peer-checked:border-gold-500 peer-checked:bg-forest-50 dark:peer-checked:bg-forest-950/40 transition">
                                <div class="text-3xl mb-2">🌙</div>
                                <div class="font-medium text-sm text-ink dark:text-cream">داكن</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- ═══ Live Preview ═══ --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6 sticky top-24">
                    <h3 class="font-display font-bold text-ink dark:text-cream mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        معاينة مباشرة
                    </h3>

                    <div class="border border-stone-200 dark:border-stone-800 rounded overflow-hidden">
                        {{-- Header --}}
                        <div :style="'background-color: ' + primaryColor" class="p-4 text-white">
                            <div class="flex items-center justify-between">
                                <span class="font-display font-bold">متجري</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="p-4 bg-white dark:bg-zinc-900">
                            <div class="border border-stone-200 dark:border-stone-800 rounded p-3 mb-3">
                                <div class="h-20 rounded mb-2" :style="'background-color: ' + primaryColor + '20'"></div>
                                <p class="font-medium text-sm text-ink dark:text-cream">اسم المنتج</p>
                                <p :style="'color: ' + primaryColor" class="font-bold">150 ₪</p>
                            </div>

                            <button :style="'background-color: ' + primaryColor"
                                    class="w-full text-white py-2 rounded text-sm font-medium mb-2">
                                أضف للسلة
                            </button>

                            <button :style="'background-color: ' + secondaryColor"
                                    class="w-full text-white py-2 rounded text-sm font-medium">
                                زر ثانوي
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ Actions ═══ --}}
        <div class="flex justify-end mt-6">
            <button type="submit" class="btn-solid">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                حفظ الهوية البصرية
            </button>
        </div>
    </form>

@endsection

@push('scripts')
<script>
function appearanceForm() {
    return {
        primaryColor: '{{ old('primary_color', $store->primary_color) }}',
        secondaryColor: '{{ old('secondary_color', $store->secondary_color) }}',
        accentColor: '{{ old('accent_color', $store->accent_color) }}',
    }
}
</script>
@endpush