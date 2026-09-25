<div class="bg-white border border-gray-200 hover:border-gray-400 rounded-lg overflow-hidden transition group">

    {{-- Image --}}
    <a href="{{ route('products.show', $product->slug) }}" class="block relative">
        <div class="aspect-square bg-gray-50 overflow-hidden">
            @if($product->primaryImage)
                <img src="{{ asset('storage/' . $product->primaryImage->image_url) }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                     alt="{{ $product->name }}">
            @else
                <div class="w-full h-full flex items-center justify-center text-5xl">
                    👕
                </div>
            @endif
        </div>

        {{-- Discount Badge --}}
        @if($product->discount_price)
            <span class="absolute top-3 right-3 bg-gray-900 text-white text-xs font-medium px-2.5 py-1 rounded-md">
                -{{ round((1 - $product->discount_price / $product->base_price) * 100) }}%
            </span>
        @endif

        {{-- Wishlist --}}
        @auth
            @if(auth()->user()->role === 'customer')
                <button onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }})"
                        class="absolute top-3 left-3 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center text-sm hover:border-gray-400 transition">
                    ❤️
                </button>
            @endif
        @endauth
    </a>

    {{-- Content --}}
    <div class="p-4">
        <p class="text-xs text-gray-500 mb-1">{{ $product->store->name }}</p>

        <a href="{{ route('products.show', $product->slug) }}">
            <h3 class="font-medium text-gray-900 mb-2 line-clamp-2 hover:text-gray-600 transition min-h-[2.5rem] text-sm">
                {{ $product->name }}
            </h3>
        </a>

        {{-- Rating --}}
        <div class="flex items-center gap-1 mb-3">
            <div class="text-yellow-500 text-xs">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($product->rating_avg))
                        ★
                    @else
                        ☆
                    @endif
                @endfor
            </div>
            <span class="text-xs text-gray-400">({{ $product->rating_avg }})</span>
        </div>

        {{-- Price --}}
        <div class="flex items-baseline gap-2 mb-4">
            @if($product->discount_price)
                <span class="text-base font-bold text-gray-900">{{ number_format($product->discount_price, 0) }} ₪</span>
                <span class="text-xs text-gray-400 line-through">{{ number_format($product->base_price, 0) }} ₪</span>
            @else
                <span class="text-base font-bold text-gray-900">{{ number_format($product->base_price, 0) }} ₪</span>
            @endif
        </div>

        {{-- Action --}}
        <a href="{{ route('products.show', $product->slug) }}"
           class="btn-primary btn-sm w-full">
            عرض المنتج
        </a>
    </div>
</div>