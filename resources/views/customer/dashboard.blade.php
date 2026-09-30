@extends('customer.layouts.app')

@section('title', 'لوحة التحكم')

@section('content')

    <div class="container-x py-10 lg:py-14">

        {{-- ═══ Welcome ═══ --}}
        <div class="mb-8">
            <span class="eyebrow block mb-3">— نظرة عامة</span>
            <h1 class="font-display text-3xl font-bold text-ink dark:text-cream mb-2">
                مرحباً {{ auth()->user()->full_name }} 👋
            </h1>
            <p class="text-sm text-ink-muted dark:text-cream/60">
                نتمنى لك تجربة تسوق ممتعة
            </p>
        </div>

        {{-- ═══ Stats Grid ═══ --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

            {{-- Orders --}}
            <a href="{{ route('customer.orders.index') }}"
               class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/40">الطلبات</span>
                </div>
                <p class="font-display text-3xl font-bold text-ink dark:text-cream">{{ $stats['orders'] }}</p>
            </a>

            {{-- Pending --}}
            <a href="{{ route('customer.orders.index', ['status' => 'pending']) }}"
               class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 flex items-center justify-center bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/40">قيد المعالجة</span>
                </div>
                <p class="font-display text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending'] }}</p>
            </a>

            {{-- Delivered --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/40">تم التسليم</span>
                </div>
                <p class="font-display text-3xl font-bold text-forest-700 dark:text-gold-400">{{ $stats['delivered'] }}</p>
            </div>

            {{-- Wishlist --}}
            <a href="{{ route('customer.wishlist') }}"
               class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 flex items-center justify-center bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/40">المفضلة</span>
                </div>
                <p class="font-display text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['wishlist'] }}</p>
            </a>
        </div>

        {{-- ═══ Total Spent Banner ═══ --}}
        <div class="bg-forest-900 dark:bg-forest-950 text-white rounded-lg p-6 mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-xs tracking-widest uppercase text-gold-400 mb-2">💰 إجمالي مشترياتك</p>
                    <p class="font-display text-3xl md:text-4xl font-bold">{{ number_format($stats['total_spent'], 0) }} ₪</p>
                </div>
                <div class="w-16 h-16 flex items-center justify-center bg-white/5 backdrop-blur rounded-full">
                    <svg class="w-8 h-8 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- ═══ Recent Orders ═══ --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden mb-6">
            <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h2 class="font-display font-bold text-ink dark:text-cream">آخر الطلبات</h2>
                </div>
                <a href="{{ route('customer.orders.index') }}"
                   class="text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity whitespace-nowrap">
                    عرض الكل ←
                </a>
            </div>

            @if($recentOrders->count() > 0)
                <div class="divide-y divide-stone-100 dark:divide-stone-800">
                    @foreach($recentOrders as $order)
                        @php
                            $statusConfig = [
                                'pending' => ['⏳ قيد المراجعة', 'text-amber-600 dark:text-amber-400'],
                                'processing' => ['⚙️ جاري التجهيز', 'text-blue-600 dark:text-blue-400'],
                                'shipped' => ['🚚 تم الشحن', 'text-violet-600 dark:text-violet-400'],
                                'delivering' => ['📍 جاري التوصيل', 'text-orange-600 dark:text-orange-400'],
                                'delivered' => ['✅ تم التسليم', 'text-forest-700 dark:text-gold-400'],
                                'cancelled' => ['❌ ملغى', 'text-red-600 dark:text-red-400'],
                            ];
                            [$statusLabel, $statusColor] = $statusConfig[$order->status] ?? ['—', 'text-ink-muted'];
                        @endphp
                        <a href="{{ route('customer.orders.show', $order) }}"
                           class="flex items-center gap-4 p-4 hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors group">
                            <div class="w-10 h-10 bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-mono font-medium text-sm text-forest-700 dark:text-gold-400 group-hover:underline">
                                    {{ $order->order_number }}
                                </p>
                                <p class="text-xs text-ink-muted dark:text-cream/50 truncate mt-0.5">
                                    {{ $order->store->name }} • {{ $order->created_at->format('Y/m/d') }}
                                </p>
                            </div>
                            <div class="text-left shrink-0">
                                <p class="font-display font-bold text-sm text-ink dark:text-cream">{{ number_format($order->total, 0) }} ₪</p>
                                <p class="text-xs {{ $statusColor }}">{{ $statusLabel }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-16">
                    <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                        <svg class="w-8 h-8 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <p class="text-sm text-ink-muted dark:text-cream/60 mb-5">لا توجد طلبات بعد</p>
                    <a href="{{ route('products.index') }}" class="btn-solid inline-flex">
                        ابدأ التسوق
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>

        {{-- ═══ Recent Wishlist ═══ --}}
        @if($recentWishlist->count() > 0)
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h2 class="font-display font-bold text-ink dark:text-cream">من المفضلة</h2>
                    </div>
                    <a href="{{ route('customer.wishlist') }}"
                       class="text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity whitespace-nowrap">
                        عرض الكل ←
                    </a>
                </div>

                <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-5">
                    @foreach($recentWishlist as $item)
                        @if($item->product)
                            @include('partials.product-card', ['product' => $item->product])
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

@endsection