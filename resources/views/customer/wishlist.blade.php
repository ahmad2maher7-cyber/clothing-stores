@extends('layouts.public')

@section('title', 'المفضلة')

@section('content')

    <div class="max-w-[1400px] mx-auto px-4 py-6">

        <nav class="flex items-center gap-2 text-[12px] mb-5" style="color: var(--text-tertiary);">
            <a href="{{ route('customer.dashboard') }}" class="transition hover:text-[color:var(--gold)]">حسابي</a>
            <span>/</span>
            <span style="color: var(--text-primary);">المفضلة</span>
        </nav>

        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold mb-1" style="color: var(--text-primary);">
                ❤️ قائمة المفضلة
            </h1>
            <p class="text-[13px]" style="color: var(--text-secondary);">
                {{ $wishlists->count() }} منتج في المفضلة
            </p>
        </div>

        @if($wishlists->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
                @foreach($wishlists as $wishlist)
                    @if($wishlist->product)
                        @include('partials.product-card', ['product' => $wishlist->product])
                    @endif
                @endforeach
            </div>
        @else
            <div class="rounded-xl border p-14 text-center max-w-lg mx-auto"
                 style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="text-7xl mb-4">💔</div>
                <h2 class="text-xl font-bold mb-2" style="color: var(--text-primary);">المفضلة فارغة</h2>
                <p class="text-[13px] mb-6" style="color: var(--text-secondary);">
                    أضف منتجات تحبها لترجع إليها لاحقاً
                </p>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 h-11 px-6 rounded-lg font-bold text-white text-[13px] transition hover:opacity-90"
                   style="background-color: var(--gold);">
                    🛍️ تسوق الآن
                </a>
            </div>
        @endif
    </div>

@endsection