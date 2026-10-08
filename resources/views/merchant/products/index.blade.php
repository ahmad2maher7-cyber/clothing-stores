@extends('merchant.layouts.app')

@section('title', 'المنتجات')
@section('page-title', 'المنتجات')

@section('content')

    {{-- ═══ Header + Actions ═══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <span class="eyebrow block mb-2">— الكتالوج</span>
            <h1 class="font-display text-2xl font-bold text-ink dark:text-cream">المنتجات</h1>
            <p class="text-sm text-ink-muted dark:text-cream/60 mt-1">
                {{ $products->total() }} منتج في متجرك
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            {{-- 📊 Export Button --}}
            <a href="{{ route('export.products', request()->only(['category_id', 'status', 'gender'])) }}"
               class="inline-flex items-center justify-center gap-2 h-11 px-5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded transition-colors whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                📊 تصدير Excel
            </a>

            {{-- Add Product Button --}}
            <a href="{{ route('merchant.products.create') }}" class="btn-solid whitespace-nowrap">
                <i class="fa-solid fa-plus"></i>
                إضافة منتج
            </a>
        </div>
    </div>

    {{-- ═══ Filters ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث باسم المنتج..."
                       class="form-input pl-10">
                <i class="fa-solid fa-magnifying-glass text-sm text-ink-muted dark:text-cream/40 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>

            <select name="category_id" class="form-input">
                <option value="">كل التصنيفات</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="form-input">
                <option value="">كل الحالات</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>نفد</option>
            </select>

            <button type="submit" class="btn-solid md:col-span-4">
                <i class="fa-solid fa-magnifying-glass"></i>
                بحث
            </button>
        </form>
    </div>

    {{-- ═══ Products Grid ═══ --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-8">
            @foreach($products as $product)
                <div class="group bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden hover:border-forest-500 dark:hover:border-gold-500 transition-all">

                    <div class="aspect-square bg-stone-50 dark:bg-zinc-800 relative overflow-hidden">
                        @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->url }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-stone-300 dark:text-stone-700">
                                <i class="fa-solid fa-shirt text-6xl"></i>
                            </div>
                        @endif

                        <div class="absolute top-3 right-3">
                            @if($product->status === 'active')
                                <span class="badge badge-forest">نشط</span>
                            @elseif($product->status === 'draft')
                                <span class="badge badge-stone">مسودة</span>
                            @else
                                <span class="badge badge-danger">نفد</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-4">
                        <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/40 mb-1">
                            {{ $product->category?->name ?? 'بدون تصنيف' }}
                        </p>
                        <h3 class="font-display font-bold text-sm text-ink dark:text-cream mb-2 line-clamp-2 min-h-[2.5rem]">
                            {{ $product->name }}
                        </h3>

                        <div class="flex items-baseline gap-2 mb-4">
                            @if($product->discount_price)
                                <span class="font-display font-bold text-base text-forest-700 dark:text-gold-400">
                                    {{ number_format($product->discount_price, 0) }} ₪
                                </span>
                                <span class="text-xs text-ink-muted dark:text-cream/40 line-through">
                                    {{ number_format($product->base_price, 0) }} ₪
                                </span>
                            @else
                                <span class="font-display font-bold text-base text-forest-700 dark:text-gold-400">
                                    {{ number_format($product->base_price, 0) }} ₪
                                </span>
                            @endif
                        </div>

                        <div class="flex gap-2 pt-3 border-t border-stone-100 dark:border-stone-800">
                            <a href="{{ route('merchant.products.show', $product) }}"
                               class="flex-1 flex items-center justify-center h-9 text-xs font-semibold text-ink-muted dark:text-cream/60 border border-stone-200 dark:border-stone-800 rounded hover:border-forest-500 dark:hover:border-gold-500 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                                <i class="fa-solid fa-eye mr-1"></i> عرض
                            </a>
                            <a href="{{ route('merchant.products.edit', $product) }}"
                               class="flex-1 flex items-center justify-center h-9 text-xs font-semibold text-forest-700 dark:text-gold-400 border border-forest-700/20 dark:border-gold-400/20 rounded hover:bg-forest-700 hover:text-white dark:hover:bg-gold-500 dark:hover:text-forest-950 transition-colors">
                                <i class="fa-solid fa-pen mr-1"></i> تعديل
                            </a>
                            <form action="{{ route('merchant.products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('حذف المنتج؟')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-9 h-9 flex items-center justify-center text-ink-muted dark:text-cream/40 border border-stone-200 dark:border-stone-800 rounded hover:border-red-300 hover:text-red-600 transition-colors">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-center">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg text-center py-20">
            <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                <i class="fa-solid fa-shirt text-5xl text-ink-muted dark:text-cream/40"></i>
            </div>
            <h3 class="font-display text-xl font-bold text-ink dark:text-cream mb-2">
                لا توجد منتجات
            </h3>
            <p class="text-sm text-ink-muted dark:text-cream/60 mb-6">
                ابدأ بإضافة منتجات لمتجرك
            </p>
            <a href="{{ route('merchant.products.create') }}" class="btn-solid">
                <i class="fa-solid fa-plus"></i>
                إضافة منتج
            </a>
        </div>
    @endif

@endsection