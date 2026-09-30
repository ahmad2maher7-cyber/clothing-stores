<div class="group bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 hover:border-forest-500 dark:hover:border-gold-500 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg">

    {{-- ═══ Image ═══ --}}
    <a href="{{ route('products.show', $product->slug) }}" class="block relative">
        <div class="aspect-square overflow-hidden bg-stone-50 dark:bg-zinc-800">
            @if($product->primaryImage)
                <img src="{{ asset('storage/' . $product->primaryImage->image_url) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full flex items-center justify-center text-stone-300 dark:text-stone-700">
                    <i class="fa-solid fa-shirt text-6xl"></i>
                </div>
            @endif
        </div>

        {{-- Discount Badge --}}
        @if($product->discount_price)
            <div class="absolute top-3 right-3 bg-red-600 text-white px-2.5 py-1 text-[10px] font-bold tracking-widest uppercase rounded">
                -{{ round((1 - $product->discount_price / $product->base_price) * 100) }}%
            </div>
        @endif

        {{-- Wishlist Button --}}
        @auth
            @if(auth()->user()->role === 'customer')
                <button onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }})"
                        title="أضف للمفضلة"
                        class="absolute top-3 left-3 w-9 h-9 flex items-center justify-center bg-white/90 dark:bg-zinc-900/90 backdrop-blur-sm border border-stone-200 dark:border-stone-700 rounded-full text-ink-muted hover:text-red-500 hover:border-red-300 transition-all">
                    <i class="fa-regular fa-heart"></i>
                </button>
            @endif
        @endauth
    </a>

    {{-- ═══ Content ═══ --}}
    <div class="p-4">
        
        {{-- Store --}}
        <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/40 mb-1.5 truncate">
            {{ $product->store->name }}
        </p>

        {{-- Name --}}
        <a href="{{ route('products.show', $product->slug) }}">
            <h3 class="font-display text-sm font-bold text-ink dark:text-cream mb-3 line-clamp-2 min-h-[2.5rem] hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                {{ $product->name }}
            </h3>
        </a>

        {{-- Rating --}}
        <div class="flex items-center gap-1.5 mb-3">
            <div class="flex text-amber-500">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($product->rating_avg))
                        <i class="fa-solid fa-star text-xs"></i>
                    @else
                        <i class="fa-regular fa-star text-xs"></i>
                    @endif
                @endfor
            </div>
            <span class="text-[10px] text-ink-muted dark:text-cream/40">
                ({{ $product->rating_avg }})
            </span>
        </div>

        {{-- Price --}}
        <div class="flex items-baseline gap-2 mb-4">
            @if($product->discount_price)
                <span class="font-display text-lg font-bold text-forest-700 dark:text-gold-400">
                    {{ number_format($product->discount_price, 0) }} ₪
                </span>
                <span class="text-xs text-ink-muted dark:text-cream/40 line-through">
                    {{ number_format($product->base_price, 0) }} ₪
                </span>
            @else
                <span class="font-display text-lg font-bold text-forest-700 dark:text-gold-400">
                    {{ number_format($product->base_price, 0) }} ₪
                </span>
            @endif
        </div>

        {{-- Action --}}
        <a href="{{ route('products.show', $product->slug) }}"
           class="flex items-center justify-center gap-2 w-full h-10 text-xs font-semibold tracking-widest uppercase text-forest-700 dark:text-gold-400 border border-forest-700/20 dark:border-gold-400/20 hover:bg-forest-700 hover:text-white dark:hover:bg-gold-500 dark:hover:text-forest-950 rounded transition-all">
            عرض المنتج
            <i class="fa-solid fa-chevron-left text-xs"></i>
        </a>
    </div>
</div>