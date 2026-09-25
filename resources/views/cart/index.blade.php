@extends('layouts.public')

@section('title', 'سلة التسوق')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-gray-900">الرئيسية</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-900">سلة التسوق</span>
        </nav>

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">🛒 سلة التسوق</h1>
            <p class="text-sm text-gray-500">
                {{ $cart->items->sum('quantity') }} منتج في سلتك
            </p>
        </div>

        @if($cart->items->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ============ Cart Items ============ --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($itemsByStore as $storeId => $items)
                        @php
                            $store = $items->first()->variant->product->store;
                        @endphp

                        <div class="bg-white border border-gray-200 rounded-lg">
                            {{-- Store Header --}}
                            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                                <a href="{{ route('stores.show', $store) }}" 
                                   class="text-sm font-medium text-gray-900 hover:text-gray-600">
                                    🏪 {{ $store->name }}
                                </a>
                                <span class="text-xs text-gray-500">{{ $items->count() }} منتج</span>
                            </div>

                            {{-- Items --}}
                            <div class="divide-y divide-gray-100">
                                @foreach($items as $item)
                                    <div class="p-4 flex gap-4" x-data="{ qty: {{ $item->quantity }} }">
                                        {{-- Image --}}
                                        <a href="{{ route('products.show', $item->variant->product->slug) }}" 
                                           class="w-20 h-20 md:w-24 md:h-24 bg-gray-50 border border-gray-200 rounded-md overflow-hidden shrink-0">
                                            @if($item->variant->product->primaryImage)
                                                <img src="{{ asset('storage/' . $item->variant->product->primaryImage->image_url) }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-3xl">👕</div>
                                            @endif
                                        </a>

                                        {{-- Info --}}
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('products.show', $item->variant->product->slug) }}">
                                                <h3 class="font-medium text-sm text-gray-900 hover:text-gray-600 line-clamp-1 mb-2">
                                                    {{ $item->variant->product->name }}
                                                </h3>
                                            </a>

                                            <p class="text-xs text-gray-500 mb-3">
                                                المقاس: <span class="inline-block bg-gray-100 px-2 py-0.5 rounded text-gray-700">{{ $item->variant->size }}</span>
                                                <span class="mx-2">|</span>
                                                اللون: {{ $item->variant->color }}
                                            </p>

                                            <p class="text-sm font-bold text-gray-900 mb-3">{{ number_format($item->price, 0) }} ₪</p>

                                            {{-- Controls --}}
                                            <div class="flex items-center gap-3">
                                                <div class="inline-flex items-center border border-gray-300 rounded-md">
                                                    <button type="button" 
                                                            @click="if (qty > 1) { qty--; updateQty({{ $item->id }}, qty); }"
                                                            class="px-3 py-1 text-gray-600 hover:text-gray-900 text-sm">−</button>
                                                    <input type="text" x-model="qty" readonly
                                                           class="w-10 text-center border-0 focus:ring-0 text-sm font-medium">
                                                    <button type="button" 
                                                            @click="if (qty < {{ $item->variant->stock_quantity }}) { qty++; updateQty({{ $item->id }}, qty); }"
                                                            class="px-3 py-1 text-gray-600 hover:text-gray-900 text-sm">+</button>
                                                </div>

                                                <button type="button" 
                                                        @click="removeItem({{ $item->id }})"
                                                        class="text-xs text-gray-500 hover:text-red-600 transition">
                                                    🗑️ حذف
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Subtotal --}}
                                        <div class="text-left shrink-0">
                                            <p class="text-xs text-gray-500 mb-1">الإجمالي</p>
                                            <p class="font-bold text-gray-900" 
                                               id="item-total-{{ $item->id }}">
                                                {{ number_format($item->price * $item->quantity, 0) }} ₪
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- Continue Shopping --}}
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition">
                        ← متابعة التسوق
                    </a>
                </div>

                {{-- ============ Order Summary ============ --}}
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-24">
                        <h3 class="font-bold text-gray-900 mb-5">ملخص السلة</h3>

                        <div class="space-y-3 mb-5 pb-5 border-b border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">المجموع الفرعي:</span>
                                <span class="font-medium text-gray-900" id="cart-subtotal">{{ number_format($subtotal, 0) }} ₪</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">الشحن:</span>
                                <span class="text-xs text-gray-500">يُحسب في الخطوة القادمة</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-6">
                            <span class="font-bold text-gray-900">الإجمالي:</span>
                            <span class="text-2xl font-bold text-gray-900" id="cart-total">
                                {{ number_format($subtotal, 0) }} ₪
                            </span>
                        </div>

                        @auth
                            @if(auth()->user()->role === 'customer')
                                <a href="{{ route('checkout.index') }}" class="btn-primary btn-lg w-full">
                                    إتمام الطلب ←
                                </a>
                            @else
                                <div class="bg-gray-50 border border-gray-200 text-gray-600 p-3 rounded-md text-sm text-center">
                                    ⚠️ يجب تسجيل الدخول كزبون
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-primary btn-lg w-full">
                                سجّل دخولك للإتمام
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @else
            {{-- Empty Cart --}}
            <div class="bg-white border border-gray-200 rounded-lg text-center py-16">
                <div class="text-6xl mb-4">🛒</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">السلة فارغة</h3>
                <p class="text-sm text-gray-500 mb-6">ابدأ بإضافة منتجات إلى سلتك</p>
                <a href="{{ route('products.index') }}" class="btn-primary btn-lg">
                    تسوّق الآن
                </a>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
function updateQty(itemId, qty) {
    fetch(`/cart/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ quantity: qty }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`item-total-${itemId}`).textContent = 
                new Intl.NumberFormat('en').format(data.item_subtotal) + ' ₪';
            document.getElementById('cart-subtotal').textContent = 
                new Intl.NumberFormat('en').format(data.cart_subtotal) + ' ₪';
            document.getElementById('cart-total').textContent = 
                new Intl.NumberFormat('en').format(data.cart_subtotal) + ' ₪';
        } else {
            alert(data.message);
        }
    });
}

function removeItem(itemId) {
    if (!confirm('هل أنت متأكد من حذف هذا المنتج؟')) return;

    fetch(`/cart/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}
</script>
@endpush