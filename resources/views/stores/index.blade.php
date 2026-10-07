@extends('layouts.public')

@section('title', 'كل المتاجر')

@section('content')

    {{-- ═══════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════ --}}
    <div class="bg-stone-50 dark:bg-zinc-900/50 border-b border-stone-200 dark:border-stone-800">
        <div class="container-x py-10 lg:py-14">
            <nav class="flex items-center gap-2 text-xs font-medium tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-6">
                <a href="{{ route('home') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">الرئيسية</a>
                <span class="opacity-40">/</span>
                <span class="text-ink dark:text-cream">المتاجر</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <span class="eyebrow block mb-3">— المتاجر المعتمدة</span>
                    <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-ink dark:text-cream tracking-tight mb-3">
                        تسوّق من المتاجر
                    </h1>
                    <p class="text-sm text-ink-muted dark:text-cream/60">
                        {{ $stores->total() }} متجر موثوق
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         SEARCH
    ═══════════════════════════════════════ --}}
    <div class="container-x py-8">
        <form method="GET" class="max-w-2xl">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث باسم المتجر..."
                       class="w-full h-12 pl-12 pr-4 text-sm bg-white dark:bg-zinc-900 border border-stone-300 dark:border-stone-700 rounded focus:border-forest-600 dark:focus:border-gold-400 focus:ring-0">
                <i class="fas fa-search absolute right-4 top-1/2 -translate-y-1/2 text-ink-muted dark:text-cream/40 pointer-events-none"></i>
            </div>
        </form>
    </div>

    {{-- ═══════════════════════════════════════
         STORES GRID
    ═══════════════════════════════════════ --}}
    <div class="container-x pb-16 lg:pb-20">
        @if($stores->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach($stores as $store)
                    <a href="{{ route('stores.show', $store) }}"
                       class="group bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 hover:border-forest-500 dark:hover:border-gold-500 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg">

                        {{-- Banner --}}
                        <div class="aspect-[3/1] bg-gradient-to-br from-forest-700 to-gold-500 relative overflow-hidden">
                            @if($store->banner)
                                <img src="{{ $store->banner_url }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-store text-white/40" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-6 -mt-12 relative">
                            
                            {{-- Logo --}}
                            <div class="w-20 h-20 flex items-center justify-center bg-white dark:bg-zinc-900 border-4 border-white dark:border-zinc-900 rounded-full shadow-lg mb-4 overflow-hidden">
                                @if($store->logo)
                                    <img src="{{ $store->logo_url }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-store text-forest-700 dark:text-gold-400 text-3xl"></i>
                                @endif
                            </div>

                            <h3 class="font-display font-bold text-lg text-ink dark:text-cream mb-2 group-hover:text-forest-700 dark:group-hover:text-gold-400 transition-colors">
                                {{ $store->name }}
                            </h3>

                            @if($store->description)
                                <p class="text-sm text-ink-muted dark:text-cream/60 line-clamp-2 mb-4">
                                    {{ $store->description }}
                                </p>
                            @endif

                            <div class="flex items-center justify-between pt-4 border-t border-stone-100 dark:border-stone-800">
                                <span class="badge badge-forest">
                                    {{ $store->products_count }} منتج
                                </span>
                                <span class="flex items-center gap-1 text-xs font-semibold text-forest-700 dark:text-gold-400">
                                    زيارة المتجر
                                    <i class="fas fa-chevron-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="flex justify-center">
                {{ $stores->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <i class="fas fa-store text-ink-muted dark:text-cream/40 text-3xl"></i>
                </div>
                <h3 class="font-display text-xl font-bold text-ink dark:text-cream mb-2">لا توجد متاجر</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60">جرب البحث بكلمات مختلفة</p>
            </div>
        @endif
    </div>

@endsection