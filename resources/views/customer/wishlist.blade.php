@extends('customer.layouts.app')

@section('title', 'المفضلة')

@section('content')

    <div class="container-x py-10 lg:py-14">

        {{-- ═══ Header ═══ --}}
        <div class="mb-8">
            <span class="eyebrow block mb-3">— حسابي</span>
            <h1 class="font-display text-3xl font-bold text-ink dark:text-cream mb-2 flex items-center gap-3">
                <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                المفضلة
            </h1>
            <p class="text-sm text-ink-muted dark:text-cream/60">
                {{ $wishlists->count() }} منتج
            </p>
        </div>

        @if($wishlists->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($wishlists as $wishlist)
                    <div class="relative">
                        {{-- Remove Button --}}
                        <form action="{{ route('customer.wishlist.remove', $wishlist) }}" method="POST"
                              class="absolute top-3 left-3 z-10"
                              onsubmit="return confirm('هل تريد إزالة المنتج من المفضلة؟')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    title="إزالة من المفضلة"
                                    class="w-9 h-9 flex items-center justify-center bg-white/95 dark:bg-zinc-900/95 backdrop-blur border border-stone-200 dark:border-stone-700 rounded-full text-ink-muted hover:text-red-600 dark:hover:text-red-400 hover:border-red-300 dark:hover:border-red-800 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>

                        @include('partials.product-card', ['product' => $wishlist->product])
                    </div>
                @endforeach
            </div>
        @else
            {{-- ═══ Empty State ═══ --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg text-center py-20 max-w-2xl mx-auto">
                <div class="w-24 h-24 mx-auto mb-6 flex items-center justify-center bg-red-50 dark:bg-red-950/30 rounded-full">
                    <svg class="w-12 h-12 text-red-400 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <span class="eyebrow block mb-3">— المفضلة فارغة</span>
                <h3 class="font-display text-2xl font-bold text-ink dark:text-cream mb-3">
                    لم تُضف أي منتج بعد
                </h3>
                <p class="text-sm text-ink-muted dark:text-cream/60 mb-8 max-w-md mx-auto">
                    أضف منتجات تحبها لترجع إليها لاحقاً
                </p>
                <a href="{{ route('products.index') }}" class="btn-solid inline-flex">
                    تسوق الآن
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>

@endsection