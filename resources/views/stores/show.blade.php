@extends('layouts.public')

@section('title', $store->name)

@section('content')

    {{-- ═══════════════════════════════════════
         STORE BANNER
    ═══════════════════════════════════════ --}}
    <div class="relative h-56 md:h-72 lg:h-80 bg-gradient-to-br from-forest-700 to-gold-500 overflow-hidden">
        @if($store->banner)
            <img src="{{ asset('storage/' . $store->banner) }}" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════
         STORE INFO
    ═══════════════════════════════════════ --}}
    <div class="container-x">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6 lg:p-8 -mt-20 relative mb-10 shadow-lg">

            <div class="flex flex-col lg:flex-row items-start gap-6">

                {{-- Logo --}}
                <div class="w-24 h-24 lg:w-28 lg:h-28 flex items-center justify-center bg-white dark:bg-zinc-900 border-4 border-white dark:border-zinc-900 rounded-2xl shadow-xl shrink-0 overflow-hidden">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" 
                             class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-store text-forest-700 dark:text-gold-400 text-5xl"></i>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1">
                    <span class="eyebrow block mb-2">— متجر معتمد</span>
                    <h1 class="font-display text-2xl md:text-3xl lg:text-4xl font-bold text-ink dark:text-cream mb-3">
                        {{ $store->name }}
                    </h1>

                    @if($store->description)
                        <p class="text-sm md:text-base text-ink-muted dark:text-cream/60 leading-relaxed mb-5 max-w-2xl">
                            {{ $store->description }}
                        </p>
                    @endif

                    {{-- Meta --}}
                    <div class="flex flex-wrap gap-5 text-sm">
                        @if($store->address)
                            <div class="flex items-center gap-2 text-ink-soft dark:text-cream/70">
                                <i class="fas fa-map-marker-alt text-forest-700 dark:text-gold-400"></i>
                                {{ $store->address }}
                            </div>
                        @endif
                        <div class="flex items-center gap-2 text-ink-soft dark:text-cream/70">
                            <i class="fas fa-box text-forest-700 dark:text-gold-400"></i>
                            {{ $store->products()->where('status', 'active')->count() }} منتج
                        </div>
                        @if($store->reviews()->where('status', 'approved')->count() > 0)
                            <div class="flex items-center gap-2 text-ink-soft dark:text-cream/70">
                                <i class="fas fa-star text-amber-500"></i>
                                {{ number_format($store->reviews()->where('status', 'approved')->avg('rating'), 1) }}
                                ({{ $store->reviews()->where('status', 'approved')->count() }})
                            </div>
                        @endif
                    </div>

                    {{-- ═══ Contact Buttons ═══ --}}
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <div class="flex flex-wrap gap-2 mt-5">
                                <form action="{{ route('customer.chat.start') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="store_id" value="{{ $store->id }}">
                                    <button type="submit" 
                                            class="inline-flex items-center gap-2 h-10 px-5 bg-forest-700 hover:bg-forest-800 dark:bg-gold-500 dark:hover:bg-gold-400 dark:text-forest-950 text-white text-sm font-medium rounded transition-colors">
                                        <i class="fa-solid fa-comments"></i>
                                        تواصل مع المتجر
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="flex flex-wrap gap-2 mt-5">
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center gap-2 h-10 px-5 bg-forest-700 hover:bg-forest-800 text-white text-sm font-medium rounded transition-colors">
                                <i class="fa-solid fa-comments"></i>
                                سجّل دخول للتواصل
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- ═══ Working Hours ═══ --}}
            @if($store->working_hours)
                <div class="mt-6 pt-6 border-t border-stone-200 dark:border-stone-800">
                    <h3 class="text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-4 flex items-center gap-2">
                        <i class="fas fa-clock text-forest-700 dark:text-gold-400"></i>
                        أوقات العمل
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                        @php
                            $days = [
                                'saturday' => 'السبت', 'sunday' => 'الأحد', 'monday' => 'الاثنين',
                                'tuesday' => 'الثلاثاء', 'wednesday' => 'الأربعاء', 
                                'thursday' => 'الخميس', 'friday' => 'الجمعة',
                            ];
                        @endphp
                        @foreach($days as $key => $label)
                            @if(!empty($store->working_hours[$key]))
                                <div class="p-3 bg-stone-50 dark:bg-zinc-950 rounded border border-stone-200 dark:border-stone-800">
                                    <p class="text-xs font-semibold text-ink dark:text-cream mb-1">{{ $label }}</p>
                                    <p class="text-xs text-forest-700 dark:text-gold-400 font-mono" dir="ltr">
                                        {{ $store->working_hours[$key] }}
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         STORE PRODUCTS
    ═══════════════════════════════════════ --}}
    <div class="container-x pb-16 lg:pb-20">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <span class="eyebrow block mb-3">— منتجات المتجر</span>
                <h2 class="font-display text-2xl md:text-3xl font-bold text-ink dark:text-cream">
                    تسوّق من {{ $store->name }}
                </h2>
            </div>

            {{-- Filters --}}
            <form method="GET" class="flex items-center gap-3">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <select name="sort" onchange="this.form.submit()"
                        class="h-10 px-4 pr-10 text-sm bg-white dark:bg-zinc-900 border border-stone-300 dark:border-stone-700 rounded focus:border-forest-600 dark:focus:border-gold-400 focus:ring-0 cursor-pointer">
                    <option value="">الأحدث</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>الأرخص</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>الأغلى</option>
                </select>
            </form>
        </div>

        {{-- Categories Filter --}}
        @if($categories->count() > 0)
            <div class="flex flex-wrap gap-2 mb-8 pb-6 border-b border-stone-200 dark:border-stone-800">
                <a href="{{ route('stores.show', $store) }}"
                   class="px-4 py-2 text-xs font-semibold tracking-wide rounded transition-all
                          {{ !request('category') 
                             ? 'bg-forest-700 text-white dark:bg-gold-500 dark:text-forest-950' 
                             : 'bg-white dark:bg-zinc-900 text-ink-soft dark:text-cream/70 border border-stone-200 dark:border-stone-800 hover:border-forest-500' }}">
                    الكل ({{ $store->products()->where('status', 'active')->count() }})
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('stores.show', ['store' => $store, 'category' => $category->id]) }}"
                       class="px-4 py-2 text-xs font-semibold tracking-wide rounded transition-all
                              {{ request('category') == $category->id 
                                 ? 'bg-forest-700 text-white dark:bg-gold-500 dark:text-forest-950' 
                                 : 'bg-white dark:bg-zinc-900 text-ink-soft dark:text-cream/70 border border-stone-200 dark:border-stone-800 hover:border-forest-500' }}">
                        {{ $category->name }} ({{ $category->products_count }})
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Products Grid --}}
        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-10">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <i class="fas fa-box text-ink-muted dark:text-cream/40 text-3xl"></i>
                </div>
                <h3 class="font-display text-xl font-bold text-ink dark:text-cream mb-2">لا توجد منتجات</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60">هذا المتجر لم يضف منتجات في هذا التصنيف بعد</p>
            </div>
        @endif
    </div>

@endsection