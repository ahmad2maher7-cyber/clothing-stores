@extends('customer.layouts.app')

@section('title', 'طلباتي')

@section('content')

    <div class="container-x py-10 lg:py-14">

        {{-- ═══ Header ═══ --}}
        <div class="mb-8">
            <span class="eyebrow block mb-3">— حسابي</span>
            <h1 class="font-display text-3xl font-bold text-ink dark:text-cream mb-2">
                طلباتي
            </h1>
            <p class="text-sm text-ink-muted dark:text-cream/60">تتبع وإدارة طلباتك</p>
        </div>

        {{-- ═══ Stats ═══ --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

            {{-- All --}}
            <a href="{{ route('customer.orders.index') }}"
               class="bg-white dark:bg-zinc-900 border rounded-lg p-5 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                      {{ !request('status') ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
                <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">الكل</p>
                <p class="font-display text-2xl font-bold text-ink dark:text-cream">{{ $stats['all'] }}</p>
            </a>

            {{-- Pending --}}
            <a href="{{ route('customer.orders.index', ['status' => 'pending']) }}"
               class="bg-white dark:bg-zinc-900 border rounded-lg p-5 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                      {{ request('status') == 'pending' ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
                <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">⏳ قيد المعالجة</p>
                <p class="font-display text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending'] }}</p>
            </a>

            {{-- Delivering --}}
            <a href="{{ route('customer.orders.index', ['status' => 'delivering']) }}"
               class="bg-white dark:bg-zinc-900 border rounded-lg p-5 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                      {{ request('status') == 'delivering' ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
                <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">🚚 قيد التوصيل</p>
                <p class="font-display text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $stats['delivering'] }}</p>
            </a>

            {{-- Delivered --}}
            <a href="{{ route('customer.orders.index', ['status' => 'delivered']) }}"
               class="bg-white dark:bg-zinc-900 border rounded-lg p-5 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                      {{ request('status') == 'delivered' ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
                <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">✅ تم التسليم</p>
                <p class="font-display text-2xl font-bold text-forest-700 dark:text-gold-400">{{ $stats['delivered'] }}</p>
            </a>
        </div>

        {{-- ═══ Search ═══ --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 mb-6">
            <form method="GET" class="flex gap-2">
                <div class="flex-1 relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="ابحث برقم الطلب..."
                           class="form-input pl-10">
                    <svg class="w-4 h-4 text-ink-muted dark:text-cream/40 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="btn-solid">بحث</button>
            </form>
        </div>

        {{-- ═══ Orders ═══ --}}
        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    @php
                        $statusConfig = [
                            'pending' => ['⏳ قيد المراجعة', 'badge-stone'],
                            'processing' => ['⚙️ جاري التجهيز', 'badge-stone'],
                            'shipped' => ['🚚 تم الشحن', 'badge-stone'],
                            'delivering' => ['📍 جاري التوصيل', 'badge-stone'],
                            'delivered' => ['✅ تم التسليم', 'badge-forest'],
                            'cancelled' => ['❌ ملغى', 'badge-danger'],
                            'returned' => ['↩️ مُرجع', 'badge-stone'],
                        ];
                        [$statusLabel, $badgeClass] = $statusConfig[$order->status] ?? ['—', 'badge-stone'];
                    @endphp
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden hover:border-forest-500 dark:hover:border-gold-500 transition-colors">

                        {{-- Header --}}
                        <div class="p-5 bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800 flex flex-wrap justify-between items-center gap-4">
                            <div class="flex items-center gap-5">
                                <div>
                                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">رقم الطلب</p>
                                    <p class="font-mono font-bold text-sm text-forest-700 dark:text-gold-400">{{ $order->order_number }}</p>
                                </div>
                                <div>
                                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">التاريخ</p>
                                    <p class="text-sm text-ink-soft dark:text-cream/70">{{ $order->created_at->format('Y/m/d') }}</p>
                                </div>
                            </div>
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </div>

                        {{-- Body --}}
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-sm text-ink dark:text-cream truncate">{{ $order->store->name }}</p>
                                    <p class="text-xs text-ink-muted dark:text-cream/50">{{ $order->items_count }} منتج</p>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-5 border-t border-stone-100 dark:border-stone-800">
                                <div>
                                    <p class="text-xs text-ink-muted dark:text-cream/50 mb-1">الإجمالي</p>
                                    <p class="font-display text-xl font-bold text-ink dark:text-cream">{{ number_format($order->total, 0) }} ₪</p>
                                </div>
                                <a href="{{ route('customer.orders.show', $order) }}" 
                                   class="inline-flex items-center gap-2 h-10 px-5 text-xs font-semibold tracking-widest uppercase bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 hover:bg-forest-800 dark:hover:bg-gold-400 rounded transition-colors">
                                    عرض التفاصيل
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-center">{{ $orders->links() }}</div>
        @else
            {{-- ═══ Empty State ═══ --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <svg class="w-10 h-10 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد طلبات</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60 mb-6">ابدأ التسوق لإنشاء أول طلب</p>
                <a href="{{ route('products.index') }}" class="btn-solid inline-flex">
                    تسوق الآن
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>

@endsection