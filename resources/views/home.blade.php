@extends('layouts.public')

@section('title', 'الرئيسية')

@section('content')

    {{-- ═══════════════════════════════════════
         HERO SECTION
    ═══════════════════════════════════════ --}}
    <section class="relative bg-gradient-to-br from-forest-50 via-canvas to-gold-50 dark:from-forest-950 dark:via-zinc-950 dark:to-zinc-900 overflow-hidden border-b border-stone-200 dark:border-stone-800">
        
        {{-- Decorative Elements --}}
        <div class="absolute top-20 right-20 w-72 h-72 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-20 left-20 w-96 h-96 bg-forest-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="container-x relative py-20 md:py-28 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                {{-- ─── Text Content ─── --}}
                <div class="space-y-7 animate-fade-up">

                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-forest-900/5 dark:bg-gold-400/10 text-forest-700 dark:text-gold-400 text-xs font-semibold tracking-widest uppercase rounded">
                        <span class="w-1.5 h-1.5 bg-gold-500 rounded-full animate-pulse-soft"></span>
                        عروض حصرية لفترة محدودة
                    </span>

                    <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-[1.05] tracking-tight text-ink dark:text-cream text-balance">
                        تسوّق الأناقة
                        <br>
                        <span class="text-forest-700 dark:text-gold-400">بأبسط طريقة</span>
                    </h1>

                    <p class="text-base lg:text-lg text-ink-muted dark:text-cream/60 leading-relaxed max-w-lg text-pretty">
                        اكتشف تشكيلة مختارة بعناية من الملابس العصرية من أفضل المتاجر الموثوقة، بأسعار منافسة وجودة عالية.
                    </p>

                    {{-- CTA --}}
                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ route('products.index') }}" class="btn-solid group">
                            تسوّق الآن
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <a href="{{ route('stores.index') }}" class="btn-outline">
                            تصفّح المتاجر
                        </a>
                    </div>

                    {{-- Trust Indicators --}}
                    <div class="flex flex-wrap gap-6 pt-6 border-t border-stone-200 dark:border-stone-800">
                        @php
                            $trust = [
                                ['label' => 'شحن سريع',      'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                                ['label' => 'جودة مضمونة',    'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                                ['label' => 'إرجاع مجاني',    'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                            ];
                        @endphp
                        @foreach($trust as $item)
                            <div class="flex items-center gap-2 text-sm text-ink-muted dark:text-cream/60">
                                <svg class="w-4 h-4 text-forest-700 dark:text-gold-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                                </svg>
                                {{ $item['label'] }}
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ─── Visual ─── --}}
                <div class="hidden lg:flex items-center justify-center relative animate-fade-up" style="animation-delay: 200ms;">
                    
                    {{-- Main Card --}}
                    <div class="relative w-full max-w-md">
                        
                        {{-- Gradient Background --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-forest-700 to-gold-500 rounded-3xl rotate-6 shadow-2xl"></div>
                        
                        {{-- Content Card --}}
                        <div class="relative bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-3xl p-10 flex items-center justify-center aspect-square shadow-xl">
                            
                            {{-- Icon --}}
                            <svg class="w-40 h-40 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="0.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>

                        {{-- Floating Badge 1 --}}
                        <div class="absolute -top-4 -right-4 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-xl px-4 py-3 shadow-lg flex items-center gap-2">
                            <div class="w-8 h-8 flex items-center justify-center bg-gold-500/10 rounded">
                                <svg class="w-4 h-4 text-gold-600 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50">متاجر موثوقة</p>
                                <p class="text-sm font-bold text-ink dark:text-cream">
                                    {{ \App\Models\Store::where('status', 'active')->count() }}+
                                </p>
                            </div>
                        </div>

                        {{-- Floating Badge 2 --}}
                        <div class="absolute -bottom-4 -left-4 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-xl px-4 py-3 shadow-lg flex items-center gap-2">
                            <div class="w-8 h-8 flex items-center justify-center bg-forest-700/10 rounded">
                                <svg class="w-4 h-4 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50">منتجات متنوعة</p>
                                <p class="text-sm font-bold text-ink dark:text-cream">
                                    {{ \App\Models\Product::where('status', 'active')->count() }}+
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════
         CATEGORIES
    ═══════════════════════════════════════ --}}
    @if($mainCategories->count() > 0)
        <section class="py-20 lg:py-24 animate-on-scroll">
            <div class="container-x">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-12">
                    <div>
                        <span class="eyebrow block mb-3">— التصنيفات</span>
                        <h2 class="section-title">تسوّق حسب التصنيف</h2>
                        <p class="section-subtitle mt-3 max-w-md">اختر من بين مجموعة واسعة من التصنيفات المتنوعة</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="link-arrow shrink-0">
                        عرض الكل
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                </div>

                {{-- Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($mainCategories as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}"
                           class="group flex flex-col items-center text-center p-6 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 hover:border-forest-500 dark:hover:border-gold-500 rounded-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">

                            <div class="w-14 h-14 flex items-center justify-center mb-4 bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded transition-all duration-300 group-hover:bg-forest-700 group-hover:text-white dark:group-hover:bg-gold-500 dark:group-hover:text-forest-950">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>

                            <h3 class="font-display font-bold text-sm text-ink dark:text-cream mb-1">
                                {{ $category->name }}
                            </h3>
                            <p class="text-xs text-ink-muted dark:text-cream/50">
                                {{ $category->products_count }} منتج
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════
         FEATURED PRODUCTS
    ═══════════════════════════════════════ --}}
    @if($featuredProducts->count() > 0)
        <section class="py-20 lg:py-24 bg-stone-50 dark:bg-zinc-900/50 border-y border-stone-200 dark:border-stone-800 animate-on-scroll">
            <div class="container-x">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-12">
                    <div>
                        <span class="eyebrow block mb-3">— مختارات</span>
                        <h2 class="section-title">منتجات مميزة</h2>
                        <p class="section-subtitle mt-3 max-w-md">اختياراتنا المفضلة من أفضل المنتجات</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="link-arrow shrink-0">
                        عرض الكل
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                </div>

                {{-- Grid --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($featuredProducts as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════
         DISCOUNTED PRODUCTS
    ═══════════════════════════════════════ --}}
    @if($discountedProducts->count() > 0)
        <section class="py-20 lg:py-24 animate-on-scroll">
            <div class="container-x">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-12">
                    <div>
                        <span class="eyebrow block mb-3 text-red-600 dark:text-red-400">— تخفيضات</span>
                        <h2 class="section-title">
                            منتجات بخصم
                            <span class="text-red-600 dark:text-red-400">حتى 50%</span>
                        </h2>
                        <p class="section-subtitle mt-3 max-w-md">وفّر أكثر على مشترياتك</p>
                    </div>
                    <a href="{{ route('products.index', ['has_discount' => 1]) }}" class="link-arrow shrink-0">
                        عرض الكل
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                </div>

                {{-- Grid --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($discountedProducts as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════
         ACTIVE OFFERS
    ═══════════════════════════════════════ --}}
    @if($activeOffers->count() > 0)
        <section class="py-20 lg:py-24 bg-stone-50 dark:bg-zinc-900/50 border-y border-stone-200 dark:border-stone-800 animate-on-scroll">
            <div class="container-x">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-12">
                    <div>
                        <span class="eyebrow block mb-3">— عروض نشطة</span>
                        <h2 class="section-title">العروض والتخفيضات</h2>
                        <p class="section-subtitle mt-3 max-w-md">لا تفوّت هذه العروض الحصرية</p>
                    </div>
                    <a href="{{ route('offers.index') }}" class="link-arrow shrink-0">
                        كل العروض
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                </div>

                {{-- Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($activeOffers as $offer)
                        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden hover:border-forest-500 dark:hover:border-gold-500 hover:shadow-lg transition-all duration-300">

                            {{-- Banner --}}
                            <div class="aspect-[16/9] bg-gradient-to-br from-forest-700 to-gold-500 relative overflow-hidden">
                                @if($offer->banner)
                                    <img src="{{ $offer->banner_url }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Discount Badge --}}
                                <div class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1.5 text-xs font-bold tracking-widest uppercase rounded">
                                    -{{ $offer->discount_percent }}%
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-6">
                                <h3 class="font-display font-bold text-lg text-ink dark:text-cream mb-3 line-clamp-2">
                                    {{ $offer->title }}
                                </h3>

                                @if($offer->description)
                                    <p class="text-sm text-ink-muted dark:text-cream/60 mb-5 line-clamp-2">
                                        {{ $offer->description }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between pt-5 border-t border-stone-100 dark:border-stone-800 text-xs">
                                    <a href="{{ route('stores.show', $offer->store) }}" class="flex items-center gap-2 text-ink-muted dark:text-cream/50 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        {{ $offer->store->name }}
                                    </a>
                                    <span class="text-ink-muted dark:text-cream/50">
                                        حتى {{ $offer->end_date->format('d/m') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════
         STORES
    ═══════════════════════════════════════ --}}
    @if($stores->count() > 0)
        <section class="py-20 lg:py-24 animate-on-scroll">
            <div class="container-x">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-12">
                    <div>
                        <span class="eyebrow block mb-3">— المتاجر</span>
                        <h2 class="section-title">متاجر مميزة</h2>
                        <p class="section-subtitle mt-3 max-w-md">تسوّق من أفضل المتاجر الموثوقة</p>
                    </div>
                    <a href="{{ route('stores.index') }}" class="link-arrow shrink-0">
                        كل المتاجر
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                </div>

                {{-- Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($stores as $store)
                        <a href="{{ route('stores.show', $store) }}"
                           class="group bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 hover:border-forest-500 dark:hover:border-gold-500 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg">

                            {{-- Banner --}}
                            <div class="aspect-[3/1] bg-gradient-to-br from-forest-700 to-gold-500 relative overflow-hidden">
                                @if($store->banner)
                                    <img src="{{ $store->banner_url }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-6 -mt-12 relative">
                                {{-- Logo --}}
                                <div class="w-20 h-20 flex items-center justify-center bg-white dark:bg-zinc-900 border-4 border-white dark:border-zinc-900 rounded-full shadow-lg mb-4 overflow-hidden">
                                    @if($store->logo)
                                        <img src="{{ $store->logo_url }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    @endif
                                </div>

                                <h3 class="font-display font-bold text-lg text-ink dark:text-cream mb-2 group-hover:text-forest-700 dark:group-hover:text-gold-400 transition-colors">
                                    {{ $store->name }}
                                </h3>

                                <p class="text-sm text-ink-muted dark:text-cream/60 line-clamp-2 mb-4">
                                    {{ $store->description }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <span class="badge badge-forest">
                                        {{ $store->products_count }} منتج
                                    </span>
                                    <span class="text-forest-700 dark:text-gold-400">
                                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════
         CTA SECTION
    ═══════════════════════════════════════ --}}
    <section class="py-20 lg:py-24 bg-forest-900 dark:bg-forest-950 relative overflow-hidden animate-on-scroll">
        
        {{-- Decorative --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-gold-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="container-x relative text-center">
            <span class="eyebrow block mb-4 text-gold-400">— ابدأ الآن</span>
            
            <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-5 text-balance">
                جاهز لتبدأ التسوق؟
            </h2>
            
            <p class="text-base lg:text-lg text-cream/70 max-w-lg mx-auto mb-10 text-pretty">
                آلاف المنتجات بانتظارك. اكتشف تشكيلتنا اليوم واحصل على أفضل الأسعار.
            </p>

            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center justify-center gap-3 h-12 px-8 font-semibold text-xs tracking-widest uppercase bg-gold-500 text-forest-950 hover:bg-gold-400 rounded transition-all">
                    ابدأ التسوّق
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center justify-center gap-3 h-12 px-8 font-semibold text-xs tracking-widest uppercase bg-transparent text-white border border-white/20 hover:border-gold-400 hover:text-gold-400 rounded transition-all">
                    أنشئ حساب
                </a>
            </div>
        </div>
    </section>

@endsection