@extends('layouts.public')

@section('title', $product->name)

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-gray-900">الرئيسية</a>
            <span class="mx-2 text-gray-300">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-gray-900">المنتجات</a>
            @if($product->category)
                <span class="mx-2 text-gray-300">/</span>
                <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="hover:text-gray-900">
                    {{ $product->category->name }}
                </a>
            @endif
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>

        {{-- Product Details --}}
        <div class="bg-white border border-gray-200 rounded-lg mb-8" x-data="productDetail()">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0">

                {{-- ========== Images ========== --}}
                <div class="p-6 md:border-l border-gray-200">
                    {{-- Main Image --}}
                    <div class="aspect-square bg-gray-50 rounded-lg overflow-hidden mb-3 border border-gray-200">
                        <template x-if="selectedImage">
                            <img :src="selectedImage" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!selectedImage">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_url) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-9xl">
                                    👕
                                </div>
                            @endif
                        </template>
                    </div>

                    {{-- Thumbnails --}}
                    @if($product->images->count() > 1)
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($product->images as $image)
                                <button @click="selectedImage = '{{ asset('storage/' . $image->image_url) }}'"
                                        class="aspect-square bg-gray-50 rounded-md overflow-hidden border border-gray-200 hover:border-gray-400 transition">
                                    <img src="{{ asset('storage/' . $image->image_url) }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ========== Info ========== --}}
                <div class="p-6 md:p-8">
                    <p class="text-sm text-gray-500 mb-2">
                        <a href="{{ route('stores.show', $product->store) }}" class="hover:text-gray-900">
                            {{ $product->store->name }}
                        </a>
                    </p>

                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                    {{-- Rating --}}
                    <div class="flex items-center gap-2 mb-5">
                        <div class="text-yellow-500">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= round($avgRating) ? '★' : '☆' }}
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500">({{ $reviewsCount }} تقييم)</span>
                    </div>

                    {{-- Price --}}
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        @if($product->discount_price)
                            <div class="flex items-baseline gap-3">
                                <span class="text-3xl font-bold text-gray-900">{{ number_format($product->discount_price, 0) }} ₪</span>
                                <span class="text-lg text-gray-400 line-through">{{ number_format($product->base_price, 0) }} ₪</span>
                                <span class="badge badge-dark">
                                    -{{ round((1 - $product->discount_price / $product->base_price) * 100) }}%
                                </span>
                            </div>
                        @else
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($product->base_price, 0) }} ₪</span>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($product->description)
                        <p class="text-sm text-gray-600 leading-relaxed mb-6">{{ $product->description }}</p>
                    @endif

                    {{-- Attributes --}}
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        @if($product->brand)
                            <div class="p-3 bg-gray-50 rounded-md">
                                <p class="text-xs text-gray-500 mb-1">الماركة</p>
                                <p class="text-sm font-medium text-gray-900">{{ $product->brand->name }}</p>
                            </div>
                        @endif
                        <div class="p-3 bg-gray-50 rounded-md">
                            <p class="text-xs text-gray-500 mb-1">الفئة</p>
                            <p class="text-sm font-medium text-gray-900">
                                @switch($product->gender)
                                    @case('men') رجالي @break
                                    @case('women') نسائي @break
                                    @case('kids') أطفال @break
                                    @default للجنسين
                                @endswitch
                            </p>
                        </div>
                    </div>

                    {{-- Size Selection --}}
                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-medium text-gray-900">المقاس</label>
                            <span x-show="!selectedSize" class="text-xs text-red-500">يجب اختيار مقاس</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sizes as $size)
                                <button type="button" @click="selectedSize = '{{ $size }}'"
                                        :class="selectedSize === '{{ $size }}' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:border-gray-400'"
                                        class="border px-4 py-2 rounded-md text-sm font-medium transition">
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Color Selection --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-medium text-gray-900">اللون</label>
                            <span x-show="!selectedColor" class="text-xs text-red-500">يجب اختيار لون</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($colors as $color)
                                <button type="button" @click="selectedColor = '{{ $color }}'"
                                        :class="selectedColor === '{{ $color }}' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:border-gray-400'"
                                        class="border px-4 py-2 rounded-md text-sm transition">
                                    {{ $color }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-900 mb-2">الكمية</label>
                        <div class="inline-flex items-center border border-gray-300 rounded-md">
                            <button @click="if (qty > 1) qty--" type="button"
                                    class="px-4 py-2 text-gray-600 hover:text-gray-900 text-lg">−</button>
                            <input type="text" x-model="qty" readonly
                                   class="w-16 text-center border-0 focus:ring-0 text-sm font-medium">
                            <button @click="qty++" type="button"
                                    class="px-4 py-2 text-gray-600 hover:text-gray-900 text-lg">+</button>
                        </div>
                    </div>

                    {{-- Add to Cart --}}
                    <button type="button" 
                            @click="addToCart({{ $product->id }})"
                            :disabled="!selectedSize || !selectedColor"
                            :class="(!selectedSize || !selectedColor) ? 'opacity-50 cursor-not-allowed' : ''"
                            class="btn-primary btn-lg w-full mb-3">
                        🛒 أضف للسلة
                    </button>

                    {{-- Wishlist --}}
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <button onclick="toggleWishlist({{ $product->id }})"
                                    class="btn-secondary w-full">
                                ❤️ أضف للمفضلة
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        {{-- ========== Reviews ========== --}}
        @if($product->reviews->count() > 0)
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">التقييمات ({{ $reviewsCount }})</h2>

                <div class="space-y-5">
                    @foreach($product->reviews->take(5) as $review)
                        <div class="border-b border-gray-200 pb-5 last:border-0 last:pb-0">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-700 flex items-center justify-center font-bold">
                                        {{ mb_substr($review->customer->full_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm text-gray-900">{{ $review->customer->full_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-yellow-500 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $review->rating ? '★' : '☆' }}
                                    @endfor
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ========== Related Products ========== --}}
        @if($relatedProducts->count() > 0)
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-6">منتجات مشابهة</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
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
                alert('يجب اختيار المقاس واللون');
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
                    alert('✅ ' + data.message);
                    window.location.reload();
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(() => alert('حدث خطأ في الاتصال'));
        }
    }
}
</script>
@endpush