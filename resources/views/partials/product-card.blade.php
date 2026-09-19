<div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition group">

    {{-- Image --}}
    <a href="{{ route('products.show', $product->slug) }}" class="block relative">
        <div class="h-56 bg-gray-100 overflow-hidden">
            @if($product->primaryImage)
                <img src="{{ asset('storage/' . $product->primaryImage->image_url) }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                     alt="{{ $product->name }}">
            @else
                <div class="w-full h-full flex items-center justify-center text-5xl">👕</div>
            @endif
        </div>

        {{-- Discount Badge --}}
        @if($product->discount_price)
            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                -{{ round((1 - $product->discount_price / $product->base_price) * 100) }}%
            </span>
        @endif

        {{-- Wishlist --}}
        @auth
            @if(auth()->user()->role === 'customer')
                <button onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }})"
                        class="absolute top-2 left-2 w-9 h-9 bg-white rounded-full shadow flex items-center justify-center hover:bg-red-50 transition z-10">
                    ❤️
                </button>
            @endif
        @endauth
    </a>

    {{-- Content --}}
    <div class="p-4">
        <p class="text-xs text-gray-500 mb-1">{{ $product->store->name }}</p>
        <a href="{{ route('products.show', $product->slug) }}">
            <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 hover:text-indigo-600 transition min-h-[3rem]">
                {{ $product->name }}
            </h3>
        </a>

        {{-- Rating --}}
        <div class="flex items-center mb-2">
            <div class="text-yellow-400 text-sm">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($product->rating_avg))
                        ⭐
                    @else
                        ☆
                    @endif
                @endfor
            </div>
            <span class="text-xs text-gray-500 mr-1">({{ $product->rating_avg }})</span>
        </div>

        {{-- Price --}}
        <div class="flex items-end justify-between mb-3">
            <div>
                @if($product->discount_price)
                    <span class="text-xl font-bold text-indigo-600">{{ number_format($product->discount_price, 0) }} ₪</span>
                    <span class="text-sm text-gray-400 line-through mr-1">{{ number_format($product->base_price, 0) }} ₪</span>
                @else
                    <span class="text-xl font-bold text-indigo-600">{{ number_format($product->base_price, 0) }} ₪</span>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex space-x-2 space-x-reverse">
            <a href="{{ route('products.show', $product->slug) }}"
               class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 rounded-lg text-sm font-medium transition">
                🛒 أضف للسلة
            </a>
            <a href="{{ route('products.show', $product->slug) }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm transition">
                👁️
            </a>
        </div>
    </div>
</div>