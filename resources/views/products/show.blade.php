@extends('layouts.public')

@section('title', $product->name)

@section('content')

    {{-- ═══════════════════════════════════════
         BREADCRUMB
    ═══════════════════════════════════════ --}}
    <div class="bg-stone-50 dark:bg-zinc-900/50 border-b border-stone-200 dark:border-stone-800">
        <div class="container-x py-4">
            <nav class="flex items-center gap-2 text-xs font-medium tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
                <a href="{{ route('home') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">الرئيسية</a>
                <span class="opacity-40">/</span>
                <a href="{{ route('products.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">المنتجات</a>
                @if($product->category)
                    <span class="opacity-40">/</span>
                    <a href="{{ route('products.index', ['category' => $product->category_id]) }}" 
                       class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                        {{ $product->category->name }}
                    </a>
                @endif
                <span class="opacity-40">/</span>
                <span class="text-ink dark:text-cream truncate">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         PRODUCT DETAILS
    ═══════════════════════════════════════ --}}
    <div class="container-x py-10 lg:py-14">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden mb-10"
             x-data="productDetail()">

            <div class="grid grid-cols-1 lg:grid-cols-2">

                {{-- ═══════ IMAGES ═══════ --}}
                <div class="p-6 lg:p-8 bg-stone-50 dark:bg-zinc-950 lg:border-l border-stone-200 dark:border-stone-800">

                    {{-- Main Image --}}
                    <div class="aspect-square rounded-lg overflow-hidden bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 mb-4">
                        <template x-if="selectedImage">
                            <img :src="selectedImage" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!selectedImage">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_url) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-stone-300 dark:text-stone-700">
                                    <i class="fas fa-tshirt" style="font-size: 8rem;"></i>
                                </div>
                            @endif
                        </template>
                    </div>

                    {{-- Thumbnails --}}
                    @if($product->images->count() > 1)
                        <div class="grid grid-cols-5 gap-3">
                            @foreach($product->images as $image)
                                <button @click="selectedImage = '{{ asset('storage/' . $image->image_url) }}'"
                                        class="aspect-square rounded overflow-hidden bg-white dark:bg-zinc-900 border-2 transition-colors"
                                        :class="selectedImage === '{{ asset('storage/' . $image->image_url) }}' 
                                                ? 'border-forest-700 dark:border-gold-400' 
                                                : 'border-stone-200 dark:border-stone-800 hover:border-forest-500'">
                                    <img src="{{ asset('storage/' . $image->image_url) }}" 
                                         class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ═══════ INFO ═══════ --}}
                <div class="p-6 lg:p-8 flex flex-col">

                    {{-- Store --}}
                    <a href="{{ route('stores.show', $product->store) }}" 
                       class="inline-flex items-center gap-2 text-xs font-medium tracking-widest uppercase text-ink-muted dark:text-cream/50 hover:text-forest-700 dark:hover:text-gold-400 transition-colors mb-4">
                        <i class="fas fa-store"></i>
                        {{ $product->store->name }}
                    </a>

                    {{-- Title --}}
                    <h1 class="font-display text-2xl lg:text-3xl font-bold text-ink dark:text-cream mb-4 text-balance">
                        {{ $product->name }}
                    </h1>

                    {{-- Rating --}}
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex text-amber-500">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($avgRating))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm text-ink-muted dark:text-cream/60">
                            {{ number_format($avgRating, 1) }} ({{ $reviewsCount }} تقييم)
                        </span>
                    </div>

                    {{-- Price --}}
                    <div class="pb-6 mb-6 border-b border-stone-200 dark:border-stone-800">
                        @if($product->discount_price)
                            <div class="flex items-baseline gap-3">
                                <span class="font-display text-4xl font-bold text-forest-700 dark:text-gold-400">
                                    {{ number_format($product->discount_price, 0) }} ₪
                                </span>
                                <span class="text-lg text-ink-muted dark:text-cream/40 line-through">
                                    {{ number_format($product->base_price, 0) }} ₪
                                </span>
                                <span class="px-2.5 py-1 text-[10px] font-bold tracking-widest uppercase bg-red-600 text-white rounded">
                                    -{{ round((1 - $product->discount_price / $product->base_price) * 100) }}%
                                </span>
                            </div>
                        @else
                            <span class="font-display text-4xl font-bold text-forest-700 dark:text-gold-400">
                                {{ number_format($product->base_price, 0) }} ₪
                            </span>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($product->description)
                        <p class="text-sm text-ink-soft dark:text-cream/70 leading-relaxed mb-6">
                            {{ $product->description }}
                        </p>
                    @endif

                    {{-- Attributes --}}
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        @if($product->brand)
                            <div class="p-3 bg-stone-50 dark:bg-zinc-950 rounded border border-stone-200 dark:border-stone-800">
                                <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">الماركة</p>
                                <p class="font-semibold text-sm text-ink dark:text-cream">{{ $product->brand->name }}</p>
                            </div>
                        @endif
                        <div class="p-3 bg-stone-50 dark:bg-zinc-950 rounded border border-stone-200 dark:border-stone-800">
                            <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">الفئة</p>
                            <p class="font-semibold text-sm text-ink dark:text-cream">
                                @switch($product->gender)
                                    @case('men') رجالي @break
                                    @case('women') نسائي @break
                                    @case('kids') أطفال @break
                                    @default للجنسين
                                @endswitch
                            </p>
                        </div>
                    </div>

                    {{-- ─── Size ─── --}}
                    <div class="mb-5">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-xs font-semibold tracking-widest uppercase text-ink dark:text-cream">
                                المقاس
                            </label>
                            <span x-show="!selectedSize" class="text-xs text-red-600 dark:text-red-400">
                                اختر مقاساً
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sizes as $size)
                                <button type="button" @click="selectedSize = '{{ $size }}'"
                                        class="min-w-[3rem] h-11 px-4 text-sm font-semibold border-2 rounded transition-all"
                                        :class="selectedSize === '{{ $size }}' 
                                                ? 'bg-forest-700 text-white border-forest-700 dark:bg-gold-500 dark:text-forest-950 dark:border-gold-500' 
                                                : 'bg-white dark:bg-zinc-900 text-ink dark:text-cream border-stone-300 dark:border-stone-700 hover:border-forest-500 dark:hover:border-gold-400'">
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- ─── Color ─── --}}
                    <div class="mb-5">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-xs font-semibold tracking-widest uppercase text-ink dark:text-cream">
                                اللون
                            </label>
                            <span x-show="!selectedColor" class="text-xs text-red-600 dark:text-red-400">
                                اختر لوناً
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($colors as $color)
                                <button type="button" @click="selectedColor = '{{ $color }}'"
                                        class="h-11 px-4 text-sm font-semibold border-2 rounded transition-all"
                                        :class="selectedColor === '{{ $color }}' 
                                                ? 'bg-forest-700 text-white border-forest-700 dark:bg-gold-500 dark:text-forest-950 dark:border-gold-500' 
                                                : 'bg-white dark:bg-zinc-900 text-ink dark:text-cream border-stone-300 dark:border-stone-700 hover:border-forest-500 dark:hover:border-gold-400'">
                                    {{ $color }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- ─── Quantity ─── --}}
                    <div class="mb-6">
                        <label class="text-xs font-semibold tracking-widest uppercase text-ink dark:text-cream block mb-3">
                            الكمية
                        </label>
                        <div class="inline-flex items-center bg-white dark:bg-zinc-900 border border-stone-300 dark:border-stone-700 rounded overflow-hidden">
                            <button @click="if (qty > 1) qty--" type="button"
                                    class="w-11 h-11 flex items-center justify-center text-ink dark:text-cream hover:bg-stone-100 dark:hover:bg-zinc-800 transition-colors">
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            <input type="text" x-model="qty" readonly
                                   class="w-16 h-11 text-center border-0 bg-transparent font-bold text-ink dark:text-cream focus:ring-0">
                            <button @click="qty++" type="button"
                                    class="w-11 h-11 flex items-center justify-center text-ink dark:text-cream hover:bg-stone-100 dark:hover:bg-zinc-800 transition-colors">
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ─── Actions ─── --}}
                    <div class="space-y-3 pt-6 mt-auto border-t border-stone-200 dark:border-stone-800">
                        <button type="button" 
                                @click="addToCart({{ $product->id }})"
                                :disabled="!selectedSize || !selectedColor"
                                :class="(!selectedSize || !selectedColor) ? 'opacity-50 cursor-not-allowed' : ''"
                                class="btn-solid w-full">
                            <i class="fas fa-shopping-bag"></i>
                            أضف للسلة
                        </button>

                        @auth
                            @if(auth()->user()->role === 'customer')
                                <button onclick="toggleWishlist({{ $product->id }})"
                                        class="btn-outline w-full">
                                    <i class="fas fa-heart"></i>
                                    أضف للمفضلة
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════
             REVIEWS
        ═══════════════════════════════════════ --}}
        @if($product->reviews->count() > 0)
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6 lg:p-8 mb-10">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="eyebrow block mb-2">— التقييمات</span>
                        <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">
                            آراء العملاء ({{ $reviewsCount }})
                        </h2>
                    </div>
                </div>

                <div class="space-y-6">
                    @foreach($product->reviews->take(5) as $review)
                        <div class="pb-6 border-b border-stone-100 dark:border-stone-800 last:border-0 last:pb-0">
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded-full font-bold shrink-0">
                                    {{ mb_substr($review->customer->full_name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-3 mb-2">
                                        <div>
                                            <p class="font-semibold text-sm text-ink dark:text-cream">
                                                {{ $review->customer->full_name }}
                                            </p>
                                            <p class="text-xs text-ink-muted dark:text-cream/40 mt-0.5">
                                                {{ $review->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="flex text-amber-500 shrink-0">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-sm"></i>
                                                @else
                                                    <i class="far fa-star text-sm"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-sm text-ink-soft dark:text-cream/70 leading-relaxed">
                                            {{ $review->comment }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════
             RELATED PRODUCTS
        ═══════════════════════════════════════ --}}
        @if($relatedProducts->count() > 0)
            <div>
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <span class="eyebrow block mb-2">— مقترحات</span>
                        <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">
                            منتجات مشابهة
                        </h2>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($relatedProducts as $related)
                        @include('partials.product-card', ['product' => $related])
                    @endforeach
                </div>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
function productDetail() {
    return {
        selectedImage: null,
        selectedSize: null,
        selectedColor: null,
        qty: 1,

        addToCart(productId) {
            if (!this.selectedSize || !this.selectedColor) {
                showToast('يجب اختيار المقاس واللون', 'error');
                return;
            }

            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    size: this.selectedSize,
                    color: this.selectedColor,
                    quantity: this.qty,
                }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    
                    const badge = document.querySelector('[data-cart-count]');
                    if (badge && data.cart_count !== undefined) {
                        badge.textContent = data.cart_count > 9 ? '9+' : data.cart_count;
                    } else if (data.cart_count > 0) {
                        setTimeout(() => window.location.reload(), 800);
                    }
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(() => showToast('حدث خطأ في الاتصال', 'error'));
        }
    }
}
</script>
@endpush