@extends('admin.layouts.app')

@section('title', $store->name)
@section('page-title', 'تفاصيل المتجر')

@section('content')

    {{-- ═══ Breadcrumb ═══ --}}
    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">
        <a href="{{ route('admin.stores.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            المتاجر
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">{{ $store->name }}</span>
    </nav>

    {{-- ═══ Store Header ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden mb-6">

        {{-- Banner --}}
        @if($store->banner)
            <img src="{{ asset('storage/' . $store->banner) }}"
                 alt="{{ $store->name }}"
                 class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gradient-to-br from-forest-700 to-gold-500"></div>
        @endif

        <div class="p-6">
            <div class="flex flex-col md:flex-row items-start gap-5">

                {{-- Logo --}}
                <div class="w-24 h-24 rounded-lg bg-white dark:bg-zinc-900 border-4 border-white dark:border-zinc-900 shadow-lg flex items-center justify-center -mt-20 shrink-0 overflow-hidden">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}"
                             alt="{{ $store->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <svg class="w-10 h-10 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                        <div class="min-w-0">
                            <h1 class="font-display text-2xl font-bold text-ink dark:text-cream mb-2">
                                {{ $store->name }}
                            </h1>

                            @if($store->description)
                                <p class="text-sm text-ink-muted dark:text-cream/60 mb-3 max-w-2xl">
                                    {{ $store->description }}
                                </p>
                            @endif

                            <div class="flex flex-wrap gap-4 text-sm">
                                <span class="flex items-center gap-2 text-ink-soft dark:text-cream/70">
                                    <svg class="w-4 h-4 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $store->merchant->full_name }}
                                </span>
                                <span class="flex items-center gap-2 text-ink-soft dark:text-cream/70">
                                    <svg class="w-4 h-4 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $store->merchant->email }}
                                </span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col gap-2 shrink-0">
                            @if($store->status === 'pending')
                                <form action="{{ route('admin.stores.approve', $store) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button class="inline-flex items-center gap-2 h-10 px-5 text-xs font-semibold tracking-widest uppercase bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 hover:bg-forest-800 dark:hover:bg-gold-400 rounded transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        اعتماد المتجر
                                    </button>
                                </form>
                            @elseif($store->status === 'active')
                                <form action="{{ route('admin.stores.suspend', $store) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button class="inline-flex items-center gap-2 h-10 px-5 text-xs font-semibold tracking-widest uppercase text-red-600 dark:text-red-400 border border-stone-300 dark:border-stone-700 hover:border-red-300 dark:hover:border-red-800 hover:bg-red-50 dark:hover:bg-red-950/20 rounded transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        إيقاف مؤقت
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ Stats ═══ --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mt-6 pt-6 border-t border-stone-200 dark:border-stone-800">
                <div class="text-center">
                    <p class="font-display text-3xl font-bold text-forest-700 dark:text-gold-400">
                        {{ $store->products_count }}
                    </p>
                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mt-1">منتج</p>
                </div>
                <div class="text-center">
                    <p class="font-display text-3xl font-bold text-ink dark:text-cream">
                        {{ $store->orders_count }}
                    </p>
                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mt-1">طلب</p>
                </div>
                <div class="text-center">
                    <p class="font-display text-3xl font-bold text-forest-700 dark:text-gold-400">
                        {{ number_format($store->orders()->where('payment_status', 'paid')->sum('total'), 0) }}
                    </p>
                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mt-1">₪ إيرادات</p>
                </div>
                <div class="text-center">
                    <p class="font-display text-3xl font-bold text-amber-600 dark:text-amber-400">
                        {{ $store->reviews()->count() }}
                    </p>
                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mt-1">تقييم</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ Recent Products ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden mb-6">
        <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
            <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="font-display font-bold text-ink dark:text-cream">
                آخر المنتجات <span class="text-ink-muted dark:text-cream/50 text-sm">({{ $store->products->count() }})</span>
            </h3>
        </div>

        @if($store->products->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المنتج</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">السعر</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($store->products as $product)
                            <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-4 py-3 font-medium text-ink dark:text-cream">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-ink-soft dark:text-cream/70">
                                    {{ number_format($product->base_price, 0) }} ₪
                                </td>
                                <td class="px-4 py-3">
                                    @if($product->status === 'active')
                                        <span class="badge badge-forest">نشط</span>
                                    @else
                                        <span class="badge badge-stone">مسودة</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-ink-muted dark:text-cream/50 text-sm">
                لا توجد منتجات
            </div>
        @endif
    </div>

    {{-- ═══ Recent Orders ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
        <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
            <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3 class="font-display font-bold text-ink dark:text-cream">
                آخر الطلبات <span class="text-ink-muted dark:text-cream/50 text-sm">({{ $store->orders->count() }})</span>
            </h3>
        </div>

        @if($store->orders->count() > 0)
            <div class="divide-y divide-stone-100 dark:divide-stone-800">
                @foreach($store->orders as $order)
                    <a href="{{ route('admin.orders.show', $order) }}"
                       class="p-4 flex justify-between items-center gap-4 hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors group">
                        <div class="flex-1 min-w-0">
                            <p class="font-mono font-medium text-forest-700 dark:text-gold-400 group-hover:underline">
                                {{ $order->order_number }}
                            </p>
                            <p class="text-xs text-ink-muted dark:text-cream/50 mt-0.5">
                                {{ $order->created_at->format('Y/m/d') }}
                            </p>
                        </div>
                        <p class="font-display font-bold text-ink dark:text-cream whitespace-nowrap">
                            {{ number_format($order->total, 0) }} ₪
                        </p>
                        <svg class="w-4 h-4 text-ink-muted dark:text-cream/40 group-hover:text-forest-700 dark:group-hover:text-gold-400 group-hover:-translate-x-1 transition-all" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-ink-muted dark:text-cream/50 text-sm">
                لا توجد طلبات
            </div>
        @endif
    </div>

@endsection