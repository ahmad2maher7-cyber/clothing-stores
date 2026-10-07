@extends('layouts.public')

@section('title', 'العروض')

@section('content')

    {{-- ═══════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════ --}}
    <div class="bg-gradient-to-br from-forest-900 to-forest-950 text-white relative overflow-hidden">
        
        {{-- Decorative --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-gold-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="container-x relative py-16 lg:py-20">
            <nav class="flex items-center gap-2 text-xs font-medium tracking-widest uppercase text-cream/50 mb-6">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition-colors">الرئيسية</a>
                <span class="opacity-40">/</span>
                <span class="text-gold-400">العروض</span>
            </nav>

            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-gold-500/20 text-gold-300 text-xs font-semibold tracking-widest uppercase rounded mb-5">
                    <span class="w-1.5 h-1.5 bg-gold-400 rounded-full animate-pulse-soft"></span>
                    عروض حصرية
                </span>
                <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight mb-5 text-balance">
                    وفّر أكثر مع
                    <br>
                    <span class="text-gold-400">عروضنا الحصرية</span>
                </h1>
                <p class="text-base lg:text-lg text-cream/70 max-w-lg text-pretty">
                    اكتشف أفضل العروض والتخفيضات من متاجرنا الموثوقة
                </p>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         ACTIVE OFFERS
    ═══════════════════════════════════════ --}}
    @if($activeOffers->count() > 0)
        <section class="container-x py-16 lg:py-20">
            <div class="flex items-center gap-3 mb-10">
                <span class="w-2 h-2 bg-forest-700 dark:bg-gold-400 rounded-full animate-pulse-soft"></span>
                <h2 class="font-display text-2xl md:text-3xl font-bold text-ink dark:text-cream">
                    عروض نشطة الآن
                </h2>
                <span class="badge badge-forest">{{ $activeOffers->count() }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($activeOffers as $offer)
                    <div class="group bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 hover:border-forest-500 dark:hover:border-gold-500 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">

                        {{-- Banner --}}
                        <div class="aspect-[16/9] bg-gradient-to-br from-forest-700 to-gold-500 relative overflow-hidden">
                            @if($offer->banner)
                                <img src="{{ $offer->banner_url }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Discount Badge --}}
                            <div class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1.5 text-xs font-bold tracking-widest uppercase rounded shadow-lg">
                                -{{ $offer->discount_percent }}%
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6">
                            <h3 class="font-display font-bold text-xl text-ink dark:text-cream mb-3 line-clamp-2 group-hover:text-forest-700 dark:group-hover:text-gold-400 transition-colors">
                                {{ $offer->title }}
                            </h3>

                            @if($offer->description)
                                <p class="text-sm text-ink-muted dark:text-cream/60 mb-5 line-clamp-2 leading-relaxed">
                                    {{ $offer->description }}
                                </p>
                            @endif

                            <div class="flex items-center justify-between pt-5 border-t border-stone-100 dark:border-stone-800 mb-5">
                                <a href="{{ route('stores.show', $offer->store) }}" 
                                   class="flex items-center gap-2 text-xs font-medium text-ink-muted dark:text-cream/50 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $offer->store->name }}
                                </a>
                                <span class="text-xs text-ink-muted dark:text-cream/50">
                                    حتى {{ $offer->end_date->format('d/m') }}
                                </span>
                            </div>

                            <a href="{{ route('stores.show', $offer->store) }}" 
                               class="btn-solid w-full">
                                تسوّق العرض
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════
         UPCOMING OFFERS
    ═══════════════════════════════════════ --}}
    @if($upcomingOffers->count() > 0)
        <section class="bg-stone-50 dark:bg-zinc-900/50 border-t border-stone-200 dark:border-stone-800">
            <div class="container-x py-16 lg:py-20">
                <div class="flex items-center gap-3 mb-10">
                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                    <h2 class="font-display text-2xl md:text-3xl font-bold text-ink dark:text-cream">
                        عروض قادمة
                    </h2>
                    <span class="badge badge-stone">{{ $upcomingOffers->count() }}</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($upcomingOffers as $offer)
                        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden opacity-75 hover:opacity-100 transition-opacity">

                            {{-- Banner --}}
                            <div class="aspect-[16/9] bg-gradient-to-br from-stone-300 to-stone-500 dark:from-zinc-700 dark:to-zinc-800 relative overflow-hidden">
                                @if($offer->banner)
                                    <img src="{{ $offer->banner_url }}" 
                                         class="w-full h-full object-cover">
                                @endif
                                
                                {{-- Soon Badge --}}
                                <div class="absolute top-4 right-4 bg-white/95 dark:bg-zinc-900/95 text-ink dark:text-cream px-3 py-1.5 text-xs font-bold tracking-widest uppercase rounded shadow-lg flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    قريباً
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-6">
                                <h3 class="font-display font-bold text-lg text-ink dark:text-cream mb-2 line-clamp-2">
                                    {{ $offer->title }}
                                </h3>

                                <div class="flex items-center justify-between pt-4 mt-4 border-t border-stone-100 dark:border-stone-800">
                                    <span class="badge badge-stone">-{{ $offer->discount_percent }}%</span>
                                    <span class="text-xs text-ink-muted dark:text-cream/50">
                                        يبدأ {{ $offer->start_date->diffForHumans() }}
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
         EMPTY STATE
    ═══════════════════════════════════════ --}}
    @if($activeOffers->count() == 0 && $upcomingOffers->count() == 0)
        <div class="container-x py-20">
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded text-center py-20 max-w-2xl mx-auto">
                <div class="w-24 h-24 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <svg class="w-12 h-12 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-bold text-ink dark:text-cream mb-3">
                    لا توجد عروض حالياً
                </h3>
                <p class="text-sm text-ink-muted dark:text-cream/60 mb-8 max-w-md mx-auto">
                    تفقدنا قريباً للاطلاع على العروض والتخفيضات الجديدة
                </p>
                <a href="{{ route('products.index') }}" class="btn-solid">
                    تسوّق المنتجات
                </a>
            </div>
        </div>
    @endif

@endsection