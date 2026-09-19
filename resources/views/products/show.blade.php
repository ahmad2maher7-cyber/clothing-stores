@extends('layouts.public')

@section('title', $product->name)

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">الرئيسية</a>
            <span class="mx-2">›</span>
            <a href="{{ route('products.index') }}" class="hover:text-indigo-600">المنتجات</a>
            <span class="mx-2">›</span>
            <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="hover:text-indigo-600">
                {{ $product->category?->name }}
            </a>
            <span class="mx-2">›</span>
            <span class="text-gray-800">{{ $product->name }}</span>
        </nav>

        {{-- Product Details --}}
        <div class="bg-white rounded-lg shadow mb-8" x-data="productDetail()">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">

                {{-- Images --}}
                <div>
                    {{-- Main Image --}}
                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-3">
                        <template x-if="selectedImage">
                            <img :src="selectedImage" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!selectedImage">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_url) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-9xl">👕</div>
                            @endif
                        </template>
                    </div>

                    {{-- Thumbnails --}}
                    @if($product->images->count() > 1)
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($product->images as $image)
                                <button @click="selectedImage = '{{ asset('storage/' . $image->image_url) }}'"
                                        class="aspect-square bg-gray-100 rounded overflow-hidden border-2 hover:border-indigo-500 transition">
                                    <img src="{{ asset('storage/' . $image->image_url) }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div>
                    <p class="text-sm text-gray-500 mb-1">
                        <a href="{{ route('stores.show', $product->store) }}" class="hover:text-indigo-600">
                            🏪 {{ $product->store->name }}
                        </a>
                    </p>

                    <h1 class="text-3xl font-bold text-gray-800 mb-3">{{ $product->name }}</h1>

                    {{-- Rating --}}
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 text-lg">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= round($avgRating) ? '⭐' : '☆' }}
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500 mr-2">({{ $reviewsCount }} تقييم)</span>
                    </div>

                    {{-- Price --}}
                    <div class="mb-4">
                        @if($product->discount_price)
                            <div class="flex items-center gap-3">
                                <span class="text-3xl font-bold text-red-600">{{ number_format($product->discount_price, 0) }} ₪</span>
                                <span class="text-xl text-gray-400 line-through">{{ number_format($product->base_price, 0) }} ₪</span>
                                <span class="bg-red-100 text-red-600 text-sm px-2 py-1 rounded font-bold">
                                    -{{ round((1 - $product->discount_price / $product->base_price) * 100) }}%
                                </span>
                            </div>
                        @else
                            <span class="text-3xl font-bold text-indigo-600">{{ number_format($product->base_price, 0) }} ₪</span>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($product->description)
                        <p class="text-gray-600 leading-relaxed mb-6">{{ $product->description }}</p>
                    @endif

                    {{-- Attributes --}}
                    <div class="grid grid-cols-2 gap-3 mb-6 text-sm">
                        @if($product->brand)
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-gray-500 text-xs mb-1">الماركة</p>
                                <p class="font-medium">{{ $product->brand->name }}</p>
                            </div>
                        @endif
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-gray-500 text-xs mb-1">الفئة</p>
                            <p class="font-medium">
                                @switch($product->gender)
                                    @case('men') 👔 رجالي @break
                                    @case('women') 👗 نسائي @break
                                    @case('kids') 🧒 أطفال @break
                                    @default 🧥 للجنسين
                                @endswitch
                            </p>
                        </div>
                    </div>

                    {{-- Size Selection --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">المقاس:</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sizes as $size)
                                <button type="button" @click="selectedSize = '{{ $size }}'"
                                        :class="selectedSize === '{{ $size }}' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 text-gray-700 hover:border-indigo-500'"
                                        class="border-2 px-4 py-2 rounded-lg font-medium transition">
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>
                        <p x-show="!selectedSize" class="text-xs text-red-500 mt-1">اختر مقاساً</p>
                    </div>

                    {{-- Color Selection --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">اللون:</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($colors as $color)
                                <button type="button" @click="selectedColor = '{{ $color }}'"
                                        :class="selectedColor === '{{ $color }}' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 text-gray-700 hover:border-indigo-500'"
                                        class="border-2 px-4 py-2 rounded-lg transition">
                                    {{ $color }}
                                </button>
                            @endforeach
                        </div>
                        <p x-show="!selectedColor" class="text-xs text-red-500 mt-1">اختر لوناً</p>
                    </div>

                    {{-- Quantity + Add to Cart --}}
                    <div class="flex gap-3 mb-4">
                        <div class="flex items-center border-2 border-gray-300 rounded-lg">
                            <button @click="if (qty > 1) qty--" type="button"
                                    class="px-4 py-3 text-gray-600 hover:text-indigo-600 text-xl">−</button>
                            <input type="number" x-model="qty" readonly
                                   class="w-16 text-center border-0 focus:ring-0 font-bold">
                            <button @click="qty++" type="button"
                                    class="px-4 py-3 text-gray-600 hover:text-indigo-600 text-xl">+</button>
                        </div>

                        <button type="button" 
                                @click="addToCart({{ $product->id }})"
                                :disabled="!selectedSize || !selectedColor"
                                :class="(!selectedSize || !selectedColor) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700'"
                                class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-bold transition">
                            🛒 أضف للسلة
                        </button>
                    </div>

                    {{-- Wishlist --}}
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <button onclick="toggleWishlist({{ $product->id }})"
                                    class="w-full border-2 border-gray-300 hover:border-red-500 hover:text-red-500 py-2 rounded-lg transition text-sm">
                                ❤️ أضف للمفضلة
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        {{-- Reviews --}}
        @if($product->reviews->count() > 0)
            <div class="bg-white rounded-lg shadow mb-8 p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">⭐ التقييمات ({{ $reviewsCount }})</h2>

                <div class="space-y-4">
                    @foreach($product->reviews->take(5) as $review)
                        <div class="border-b pb-4 last:border-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                        {{ mb_substr($review->customer->full_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm">{{ $review->customer->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $review->rating ? '⭐' : '☆' }}
                                    @endfor
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="text-gray-700 text-sm">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Related Products --}}
        @if($relatedProducts->count() > 0)
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">منتجات مشابهة</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
