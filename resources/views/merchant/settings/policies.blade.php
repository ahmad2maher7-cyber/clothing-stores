@extends('merchant.layouts.app')

@section('title', 'سياسات المتجر')
@section('page-title', 'سياسات المتجر')

@section('content')

    {{-- ═══ Tabs ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden mb-6">
        <div class="flex overflow-x-auto">
            @php
                $tabs = [
                    ['route' => 'merchant.settings.index',       'label' => 'المعلومات الأساسية', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['route' => 'merchant.settings.appearance',  'label' => 'الهوية البصرية',    'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
                    ['route' => 'merchant.settings.branches',    'label' => 'الفروع',             'icon' => 'M3 21h18M5 21V7l8-4v18M19 21V11l-6-4M9 9v.01M9 12v.01M9 15v.01M9 18v.01'],
                    ['route' => 'merchant.settings.shipping',    'label' => 'مناطق الشحن',        'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                    ['route' => 'merchant.settings.policies',    'label' => 'السياسات',           'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ];
            @endphp

            @foreach($tabs as $tab)
                @php $isActive = request()->routeIs($tab['route']); @endphp
                <a href="{{ route($tab['route']) }}"
                   class="flex items-center gap-2 px-5 py-4 whitespace-nowrap border-b-2 text-sm font-medium transition-colors
                          {{ $isActive 
                             ? 'border-forest-700 dark:border-gold-400 text-forest-700 dark:text-gold-400' 
                             : 'border-transparent text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}"/>
                    </svg>
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <form action="{{ route('merchant.settings.policies.update') }}" method="POST">
        @csrf @method('PUT')

        <div class="space-y-6">

            {{-- Privacy Policy --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-display text-lg font-bold text-ink dark:text-cream">سياسة الخصوصية</h3>
                        <p class="text-xs text-ink-muted dark:text-cream/60">وضّح للزبائن كيف تحمي بياناتهم</p>
                    </div>
                </div>

                <div class="p-6">
                    <textarea name="privacy_policy" rows="10" maxlength="10000"
                              placeholder="اكتب سياسة الخصوصية لمتجرك هنا...&#10;&#10;مثال:&#10;- نحن نحترم خصوصيتك.&#10;- لا نشارك بياناتك مع أطراف ثالثة.&#10;- نستخدم بياناتك لتحسين خدمتنا فقط."
                              class="form-input h-auto py-4 resize-none font-mono text-sm leading-relaxed">{{ old('privacy_policy', $store->privacy_policy) }}</textarea>
                </div>
            </div>

            {{-- Return Policy --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-display text-lg font-bold text-ink dark:text-cream">سياسة الاستبدال والإرجاع</h3>
                        <p class="text-xs text-ink-muted dark:text-cream/60">وضّح شروط الإرجاع والاستبدال</p>
                    </div>
                </div>

                <div class="p-6">
                    <textarea name="return_policy" rows="10" maxlength="10000"
                              placeholder="اكتب سياسة الاستبدال والإرجاع هنا...&#10;&#10;مثال:&#10;- يمكنك الإرجاع خلال 14 يوماً.&#10;- يجب أن يكون المنتج بحالته الأصلية.&#10;- تكاليف الشحن على المشتري."
                              class="form-input h-auto py-4 resize-none font-mono text-sm leading-relaxed">{{ old('return_policy', $store->return_policy) }}</textarea>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end">
                <button type="submit" class="btn-solid">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    حفظ السياسات
                </button>
            </div>
        </div>
    </form>

@endsection