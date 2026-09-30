@extends('layouts.public')

@section('title', 'كل المنتجات')

@section('content')

    {{-- ═══════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════ --}}
    <div class="bg-stone-50 dark:bg-zinc-900/50 border-b border-stone-200 dark:border-stone-800">
        <div class="container-x py-10 lg:py-14">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs font-medium tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-6">
                <a href="{{ route('home') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">الرئيسية</a>
                <span class="opacity-40">/</span>
                <span class="text-ink dark:text-cream">المنتجات</span>
            </nav>

            {{-- Title --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <span class="eyebrow block mb-3">— التسوق</span>
                    <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-ink dark:text-cream tracking-tight mb-3">
                        كل المنتجات
                    </h1>
                    <p class="text-sm text-ink-muted dark:text-cream/60">
                        {{ $products->total() }} منتج متاح
                        @if(request('search'))
                            — البحث: "<strong class="text-ink dark:text-cream">{{ request('search') }}</strong>"
                        @endif
                    </p>
                </div>

                {{-- Sort --}}
                <form method="GET" class="flex items-center gap-3">
                    @foreach(request()->except('sort') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <label class="text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 whitespace-nowrap">
                        ترتيب:
                    </label>
                    <select name="sort" onchange="this.form.submit()"
                            class="h-10 px-4 pr-10 text-sm bg-white dark:bg-zinc-900 border border-stone-300 dark:border-stone-700 rounded focus:border-forest-600 dark:focus:border-gold-400 focus:ring-0 cursor-pointer">
                        <option value="">الأحدث</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>الأرخص</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>الأغلى</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════ --}}
    <div class="container-x py-10 lg:py-14">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">

            {{-- ═══════ FILTERS SIDEBAR ═══════ --}}
            <aside class="lg:col-span-3">
                <div class="sticky top-24" x-data="{ openFilters: false }">

                    {{-- Mobile Toggle --}}
                    <button @click="openFilters = !openFilters"
                            class="lg:hidden w-full flex items-center justify-between px-5 py-4 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded mb-4">
                        <span class="font-semibold text-sm text-ink dark:text-cream flex items-center gap-2">
                            <i class="fas fa-filter"></i>
                            الفلاتر
                        </span>
                        <i class="fas fa-chevron-down text-ink-muted transition-transform" 
                           :class="openFilters ? 'rotate-180' : ''"></i>
                    </button>

                    {{-- Filters Content --}}
                    <div x-show="openFilters || window.innerWidth >= 1024"
                         x-cloak
                         class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded">
                        <form method="GET" class="divide-y divide-stone-200 dark:divide-stone-800">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif

                            {{-- ─── Header ─── --}}
                            <div class="p-5 flex items-center justify-between">
                                <h3 class="font-display font-bold text-ink dark:text-cream">الفلاتر</h3>
                                @if(count(request()->except('search', 'sort', 'page')) > 0)
                                    <a href="{{ route('products.index', request()->only('search', 'sort')) }}"
                                       class="text-xs text-red-600 dark:text-red-400 hover:opacity-70 transition-opacity">
                                        إعادة تعيين
                                    </a>
                                @endif
                            </div>

                            {{-- ─── Categories ─── --}}
                            <div class="p-5">
                                <h4 class="text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-4">
                                    التصنيف
                                </h4>
                                <div class="space-y-2 max-h-64 overflow-y-auto pr-2 -mr-2">
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" name="category" value=""
                                               {{ !request('category') ? 'checked' : '' }}
                                               class="w-4 h-4 text-forest-700 focus:ring-forest-500 border-stone-300 dark:border-stone-600">
                                        <span class="text-sm text-ink-soft dark:text-cream/80 group-hover:text-forest-700 dark:group-hover:text-gold-400 transition-colors">
                                            الكل
                                        </span>
                                    </label>
                                    @foreach($categories as $category)
                                        <div>
                                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                                <input type="radio" name="category" value="{{ $category->id }}"
                                                       {{ request('category') == $category->id ? 'checked' : '' }}
                                                       class="w-4 h-4 text-forest-700 focus:ring-forest-500 border-stone-300 dark:border-stone-600">
                                                <span class="text-sm font-medium text-ink-soft dark:text-cream/80 group-hover:text-forest-700 dark:group-hover:text-gold-400 transition-colors">
                                                    {{ $category->name }}
                                                </span>
                                            </label>
                                            @if($category->children->count() > 0)
                                                <div class="mr-6 mt-1.5 space-y-1.5">
                                                    @foreach($category->children as $child)
                                                        <label class="flex items-center gap-2.5 cursor-pointer group">
                                                            <input type="radio" name="category" value="{{ $child->id }}"
                                                                   {{ request('category') == $child->id ? 'checked' : '' }}
                                                                   class="w-3.5 h-3.5 text-forest-700 focus:ring-forest-500 border-stone-300 dark:border-stone-600">
                                                            <span class="text-xs text-ink-muted dark:text-cream/60 group-hover:text-forest-700 dark:group-hover:text-gold-400 transition-colors">
                                                                {{ $child->name }}
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- ─── Gender ─── --}}
                            <div class="p-5">
                                <h4 class="text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-4">
                                    الفئة
                                </h4>
                                <div class="space-y-2">
                                    @foreach(['men' => 'رجالي', 'women' => 'نسائي', 'kids' => 'أطفال', 'unisex' => 'للجنسين'] as $value => $label)
                                        <label class="flex items-center gap-2.5 cursor-pointer group">
                                            <input type="radio" name="gender" value="{{ $value }}"
                                                   {{ request('gender') == $value ? 'checked' : '' }}
                                                   class="w-4 h-4 text-forest-700 focus:ring-forest-500 border-stone-300 dark:border-stone-600">
                                            <span class="text-sm text-ink-soft dark:text-cream/80 group-hover:text-forest-700 dark:group-hover:text-gold-400 transition-colors">
                                                {{ $label }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- ─── Price ─── --}}
                            <div class="p-5">
                                <h4 class="text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-4">
                                    السعر
                                </h4>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                                           placeholder="من" min="0"
                                           class="form-input text-sm">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                                           placeholder="إلى" min="0"
                                           class="form-input text-sm">
                                </div>
                                <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">
                                    {{ $priceRange['min'] }} - {{ $priceRange['max'] }} ₪
                                </p>
                            </div>

                            {{-- ─── Brand ─── --}}
                            <div class="p-5">
                                <h4 class="text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-4">
                                    الماركة
                                </h4>
                                <select name="brand" class="form-input text-sm">
                                    <option value="">كل الماركات</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ─── Store ─── --}}
                            <div class="p-5">
                                <h4 class="text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-4">
                                    المتجر
                                </h4>
                                <select name="store" class="form-input text-sm">
                                    <option value="">كل المتاجر</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}" {{ request('store') == $store->id ? 'selected' : '' }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ─── Has Discount ─── --}}
                            <div class="p-5">
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" name="has_discount" value="1"
                                           {{ request('has_discount') ? 'checked' : '' }}
                                           class="w-4 h-4 text-forest-700 focus:ring-forest-500 border-stone-300 dark:border-stone-600 rounded">
                                    <span class="text-sm text-ink-soft dark:text-cream/80 group-hover:text-forest-700 dark:group-hover:text-gold-400 transition-colors">
                                        عليها خصم فقط
                                    </span>
                                </label>
                            </div>

                            {{-- ─── Submit ─── --}}
                            <div class="p-5 space-y-2">
                                <button type="submit" class="btn-solid w-full">
                                    تطبيق الفلاتر
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- ═══════ PRODUCTS GRID ═══════ --}}
            <div class="lg:col-span-9">

                {{-- Results Bar --}}
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-stone-200 dark:border-stone-800">
                    <p class="text-sm text-ink-muted dark:text-cream/60">
                        عرض <span class="font-bold text-ink dark:text-cream">{{ $products->firstItem() ?? 0 }}</span>
                        - <span class="font-bold text-ink dark:text-cream">{{ $products->lastItem() ?? 0 }}</span>
                        من <span class="font-bold text-ink dark:text-cream">{{ $products->total() }}</span>
                    </p>

                    {{-- Active Filters --}}
                    @if(count(request()->except('page', 'sort', 'search')) > 0)
                        <div class="hidden md:flex items-center gap-2">
                            <span class="text-xs text-ink-muted dark:text-cream/50">فلاتر نشطة:</span>
                            <span class="badge badge-forest">{{ count(request()->except('page', 'sort', 'search')) }}</span>
                        </div>
                    @endif
                </div>

                {{-- Grid --}}
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-5 mb-10">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="flex justify-center">
                        {{ $products->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded text-center py-20">

                        <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                            <i class="fas fa-search text-ink-muted dark:text-cream/40 text-3xl"></i>
                        </div>

                        <h3 class="font-display text-xl font-bold text-ink dark:text-cream mb-2">
                            لا توجد منتجات
                        </h3>
                        <p class="text-sm text-ink-muted dark:text-cream/60 mb-8 max-w-md mx-auto">
                            جرب تغيير الفلاتر أو البحث بكلمات مختلفة
                        </p>
                        <a href="{{ route('products.index') }}" class="btn-solid">
                            إعادة تعيين الفلاتر
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection