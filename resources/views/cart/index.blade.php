@extends('layouts.public')

@section('title', 'سلة التسوق')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">الرئيسية</a>
            <span class="mx-2">›</span>
            <span class="text-gray-800">سلة التسوق</span>
        </nav>

        <h1 class="text-3xl font-bold text-gray-800 mb-8">🛒 سلة التسوق</h1>

        @if($cart->items->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Cart Items --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($itemsByStore as $storeId => $items)
                        @php
                            $store = $items->first()->variant->product->store;
                        @endphp

                        <div class="bg-white rounded-lg shadow">
                            {{-- Store Header --}}
                            <div class="p-4 border-b bg-gray-50 rounded-t-lg flex items-center justify-between">
                                <a href="{{ route('stores.show', $store) }}" class="flex items-center font-medium text-indigo-600 hover:text-indigo-700">
                                    🏪 {{ $store->name }}
                                </a>
                                <span class="text-sm text-gray-500">{{ $items->count() }} منتج</span>
                            </div>

                            {{-- Items --}}
                            <div class="divide-y">
                                @foreach($items as $item)
                                    <div class="p-4 flex gap-4" x-data="{ qty: {{ $item->quantity }} }">
                                        {{-- Image --}}
                                        <a href="{{ route('products.show', $item->variant->product->slug) }}" 
                                           class="w-20 h-20 md:w-24 md:h-24 bg-gray-100 rounded-lg overflow-hidden shrink-0">
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
                                                <h3 class="font-medium text-gray-800 hover:text-indigo-600 line-clamp-1">
                                                    {{ $item->variant->product->name }}
                                                </h3>
                                            </a>
                                            <p class="text-xs text-gray-500 mt-1">
                                                المقاس: <span class="bg-indigo-100 px-2 rounded">{{ $item->variant->size }}</span>
                                                | اللون: {{ $item->variant->color }}
                                            </p>
                                            <p class="text-indigo-600 font-bold mt-2">{{ number_format($item->price, 0) }} ₪</p>

                                            {{-- Quantity Controls --}}
                                            <div class="flex items-center gap-3 mt-3">
                                                <div class="flex items-center border rounded-lg">
                                                    <button type="button" 
                                                            @click="if (qty > 1) { qty--; updateQty({{ $item->id }}, qty); }"
                                                            class="px-3 py-1 text-gray-600 hover:text-indigo-600">−</button>
                                                    <input type="number" x-model="qty" readonly
                                                           class="w-12 text-center border-0 focus:ring-0 text-sm font-bold">
                                                    <button type="button" 
                                                            @click="if (qty < {{ $item->variant->stock_quantity }}) { qty++; updateQty({{ $item->id }}, qty); }"
                                                            class="px-3 py-1 text-gray-600 hover:text-indigo-600">+</button>
                                                </div>

                                                <button type="button" 
                                                        @click="removeItem({{ $item->id }})"
                                                        class="text-red-500 hover:text-red-700 text-sm">
                                                    🗑️ حذف
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Subtotal --}}
                                        <div class="text-left shrink-0">
                                            <p class="text-xs text-gray-500">الإجمالي</p>
                                            <p class="font-bold text-gray-800" 
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
                       class="inline-flex items-center text-indigo-600 hover:text-indigo-700">
                        ← متابعة التسوق
                    </a>
                </div>

                {{-- Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                        <h3 class="font-bold text-lg mb-4">📋 ملخص السلة</h3>

                        <div class="space-y-3 mb-4 pb-4 border-b">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">المجموع الفرعي:</span>
                                <span class="font-medium" id="cart-subtotal">{{ number_format($subtotal, 0) }} ₪</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">الشحن:</span>
                                <span class="text-gray-500 text-xs">يُحسب في الخطوة القادمة</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-6">
                            <span class="font-bold">الإجمالي:</span>
                            <span class="text-2xl font-bold text-indigo-600" id="cart-total">
                                {{ number_format($subtotal, 0) }} ₪
                            </span>
                        </div>

                        @auth
                            @if(auth()->user()->role === 'customer')
                                <a href="{{ route('checkout.index') }}"
                                   class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center py-3 rounded-lg font-bold transition">
                                    💳 إتمام الطلب
                                </a>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded-lg text-sm text-center">
                                    ⚠️ تسجيل دخول كزبون مطلوب
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                               class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center py-3 rounded-lg font-bold">
                                سجّل دخولك للإتمام
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow text-center py-16">
                <div class="text-7xl mb-4">🛒</div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">السلة فارغة</h3>
                <p class="text-gray-500 mb-6">ابدأ بإضافة منتجات إلى سلتك</p>
                <a href="{{ route('products.index') }}"
                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-bold">
                    🛍️ تسوق الآن
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