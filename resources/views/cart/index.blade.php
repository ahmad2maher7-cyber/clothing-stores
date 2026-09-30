@extends('layouts.public')

@section('title', 'سلة التسوق')

@section('content')

    {{-- ═══════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════ --}}
    <div class="bg-stone-50 dark:bg-zinc-900/50 border-b border-stone-200 dark:border-stone-800">
        <div class="container-x py-10 lg:py-14">
            <nav class="flex items-center gap-2 text-xs font-medium tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-6">
                <a href="{{ route('home') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">الرئيسية</a>
                <span class="opacity-40">/</span>
                <span class="text-ink dark:text-cream">السلة</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <span class="eyebrow block mb-3">— السلة</span>
                    <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-ink dark:text-cream tracking-tight mb-3">
                        سلة المشتريات
                    </h1>
                    <p class="text-sm text-ink-muted dark:text-cream/60">
                        {{ $cart->items->sum('quantity') }} منتج في سلتك
                    </p>
                </div>
                <a href="{{ route('products.index') }}" class="link-arrow shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                    متابعة التسوق
                </a>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         CART CONTENT
    ═══════════════════════════════════════ --}}
    <div class="container-x py-10 lg:py-14">

        @if($cart->items->count() > 0)

            <div class="grid lg:grid-cols-12 gap-8">

                {{-- ═══════ CART ITEMS ═══════ --}}
                <div class="lg:col-span-8 space-y-6">

                    @foreach($itemsByStore as $storeId => $items)
                        @php
                            $store = $items->first()->variant->product->store;
                        @endphp

                        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">

                            {{-- Store Header --}}
                            <div class="px-6 py-4 border-b border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-zinc-950 flex items-center justify-between">
                                <a href="{{ route('stores.show', $store) }}" 
                                   class="flex items-center gap-3 group">
                                    <div class="w-8 h-8 flex items-center justify-center bg-forest-700 dark:bg-gold-500 text-white dark:text-ink rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50">متجر</p>
                                        <p class="text-sm font-semibold text-ink dark:text-cream group-hover:text-forest-700 dark:group-hover:text-gold-400 transition-colors">
                                            {{ $store->name }}
                                        </p>
                                    </div>
                                </a>
                                <span class="badge badge-forest">{{ $items->count() }} منتج</span>
                            </div>

                            {{-- Items --}}
                            <div class="divide-y divide-stone-100 dark:divide-stone-800">
                                @foreach($items as $item)
                                    <div class="p-5 flex gap-4" x-data="{ qty: {{ $item->quantity }} }">

                                        {{-- Image --}}
                                        <a href="{{ route('products.show', $item->variant->product->slug) }}" 
                                           class="w-24 h-24 shrink-0 bg-stone-100 dark:bg-zinc-800 rounded-lg overflow-hidden border border-stone-200 dark:border-stone-800">
                                            @if($item->variant->product->primaryImage)
                                                <img src="{{ asset('storage/' . $item->variant->product->primaryImage->image_url) }}" 
                                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-stone-300 dark:text-stone-700">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </a>

                                        {{-- Info --}}
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('products.show', $item->variant->product->slug) }}">
                                                <h3 class="font-display font-bold text-sm text-ink dark:text-cream mb-2 line-clamp-2 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                                                    {{ $item->variant->product->name }}
                                                </h3>
                                            </a>

                                            <div class="flex flex-wrap gap-3 text-xs text-ink-muted dark:text-cream/50 mb-3">
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="font-medium text-ink-soft dark:text-cream/70">المقاس:</span>
                                                    <span class="px-2 py-0.5 bg-stone-100 dark:bg-zinc-800 rounded">{{ $item->variant->size }}</span>
                                                </span>
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="font-medium text-ink-soft dark:text-cream/70">اللون:</span>
                                                    <span class="px-2 py-0.5 bg-stone-100 dark:bg-zinc-800 rounded">{{ $item->variant->color }}</span>
                                                </span>
                                            </div>

                                            <p class="font-display text-lg font-bold text-forest-700 dark:text-gold-400">
                                                {{ number_format($item->price, 0) }} ₪
                                            </p>

                                            {{-- Controls --}}
                                            <div class="flex flex-wrap items-center gap-4 mt-4">
                                                
                                                {{-- Quantity --}}
                                                <div class="inline-flex items-center bg-white dark:bg-zinc-900 border border-stone-300 dark:border-stone-700 rounded overflow-hidden">
                                                    <button type="button" 
                                                            @click="if (qty > 1) { qty--; updateQty({{ $item->id }}, qty); }"
                                                            class="w-9 h-9 flex items-center justify-center text-ink dark:text-cream hover:bg-stone-100 dark:hover:bg-zinc-800 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                                                        </svg>
                                                    </button>
                                                    <input type="text" x-model="qty" readonly
                                                           class="w-12 h-9 text-center border-0 bg-transparent text-sm font-bold text-ink dark:text-cream focus:ring-0">
                                                    <button type="button" 
                                                            @click="if (qty < {{ $item->variant->stock_quantity }}) { qty++; updateQty({{ $item->id }}, qty); }"
                                                            class="w-9 h-9 flex items-center justify-center text-ink dark:text-cream hover:bg-stone-100 dark:hover:bg-zinc-800 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                                        </svg>
                                                    </button>
                                                </div>

                                                {{-- Remove --}}
                                                <button type="button" 
                                                        @click="removeItem({{ $item->id }})"
                                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:opacity-70 transition-opacity">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    حذف
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Subtotal --}}
                                        <div class="text-left shrink-0 hidden sm:block">
                                            <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/40 mb-1">الإجمالي</p>
                                            <p class="font-display font-bold text-ink dark:text-cream" 
                                               id="item-total-{{ $item->id }}">
                                                {{ number_format($item->price * $item->quantity, 0) }} ₪
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ═══════ ORDER SUMMARY ═══════ --}}
                <div class="lg:col-span-4">
                    <div class="sticky top-24 space-y-4">

                        {{-- Summary Card --}}
                        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">

                            {{-- Header --}}
                            <div class="px-6 py-4 border-b border-stone-200 dark:border-stone-800">
                                <span class="eyebrow">— ملخص الطلب</span>
                            </div>

                            {{-- Summary --}}
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-ink-muted dark:text-cream/60">المجموع الفرعي</span>
                                    <span class="font-semibold text-ink dark:text-cream" id="cart-subtotal">
                                        {{ number_format($subtotal, 0) }} ₪
                                    </span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-ink-muted dark:text-cream/60">الشحن</span>
                                    <span class="text-xs text-ink-muted dark:text-cream/40">يُحدد في الدفع</span>
                                </div>

                                <div class="pt-4 border-t border-stone-200 dark:border-stone-800 flex justify-between items-baseline">
                                    <span class="font-display font-bold text-ink dark:text-cream">الإجمالي</span>
                                    <span class="font-display text-3xl font-bold text-forest-700 dark:text-gold-400" id="cart-total">
                                        {{ number_format($subtotal, 0) }} ₪
                                    </span>
                                </div>
                            </div>

                            {{-- Action --}}
                            <div class="p-6 pt-0">
                                @auth
                                    @if(auth()->user()->role === 'customer')
                                        <a href="{{ route('checkout.index') }}" class="btn-solid w-full">
                                            إتمام الطلب
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </a>
                                    @else
                                        <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 text-amber-800 dark:text-amber-300 p-4 rounded text-sm text-center">
                                            <svg class="w-5 h-5 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            يجب تسجيل الدخول كزبون
                                        </div>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn-solid w-full">
                                        سجّل دخولك
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
                                        </svg>
                                    </a>
                                @endauth
                            </div>
                        </div>

                        {{-- Trust Indicators --}}
                        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                            <div class="space-y-4">
                                @php
                                    $trust = [
                                        ['label' => 'دفع آمن 100%',           'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                                        ['label' => 'إرجاع مجاني خلال 14 يوم', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                        ['label' => 'شحن سريع لكل المدن',      'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                                    ];
                                @endphp
                                @foreach($trust as $item)
                                    <div class="flex items-center gap-3 text-sm">
                                        <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                                            </svg>
                                        </div>
                                        <span class="text-ink-soft dark:text-cream/70">{{ $item['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            {{-- ═══════ EMPTY CART ═══════ --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded text-center py-20 max-w-2xl mx-auto">
                <div class="w-24 h-24 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <svg class="w-12 h-12 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="eyebrow block mb-3">— السلة فارغة</span>
                <h2 class="font-display text-3xl font-bold text-ink dark:text-cream mb-3">
                    لم تُضف أي منتج بعد
                </h2>
                <p class="text-sm text-ink-muted dark:text-cream/60 mb-8 max-w-md mx-auto">
                    ابدأ التسوّق واكتشف منتجاتنا المختارة بعناية
                </p>
                <a href="{{ route('products.index') }}" class="btn-solid group inline-flex">
                    ابدأ التسوق
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
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
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('حدث خطأ في الاتصال', 'error'));
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
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('حدث خطأ في الاتصال', 'error'));
}
</script>
@endpush