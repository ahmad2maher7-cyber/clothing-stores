@extends('admin.layouts.app')

@section('title', $order->order_number)
@section('page-title', 'تفاصيل الطلب')

@section('content')

    {{-- ═══ Breadcrumb ═══ --}}
    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">
        <a href="{{ route('admin.orders.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            الطلبات
        </a>
        <span class="opacity-40">/</span>
        <span class="font-mono text-ink dark:text-cream">{{ $order->order_number }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ═══ Main Content ═══ --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Order Info --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                <div class="flex justify-between items-start mb-5">
                    <div>
                        <h2 class="font-display text-2xl font-bold font-mono text-ink dark:text-cream">
                            {{ $order->order_number }}
                        </h2>
                        <p class="text-sm text-ink-muted dark:text-cream/60 mt-1">
                            {{ $order->created_at->format('Y/m/d — H:i') }}
                        </p>
                    </div>
                    @php
                        $statusMap = [
                            'pending' => ['⏳ قيد المراجعة', 'bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900/50'],
                            'processing' => ['⚙️ التجهيز', 'bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-900/50'],
                            'shipped' => ['🚚 الشحن', 'bg-violet-50 dark:bg-violet-950/30 text-violet-700 dark:text-violet-300 border-violet-200 dark:border-violet-900/50'],
                            'delivering' => ['📍 التوصيل', 'bg-orange-50 dark:bg-orange-950/30 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-900/50'],
                            'delivered' => ['✅ التسليم', 'bg-forest-50 dark:bg-forest-950/30 text-forest-700 dark:text-forest-300 border-forest-200 dark:border-forest-900/50'],
                            'cancelled' => ['❌ ملغى', 'bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-300 border-red-200 dark:border-red-900/50'],
                        ];
                        [$statusLabel, $statusClass] = $statusMap[$order->status] ?? ['—', 'bg-stone-100 text-stone-700'];
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 {{ $statusClass }} border rounded text-sm font-medium">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-5 pt-5 border-t border-stone-200 dark:border-stone-800 text-sm">
                    <div>
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">الزبون</p>
                        <p class="font-medium text-ink dark:text-cream">{{ $order->customer->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">المتجر</p>
                        <p class="font-medium text-ink dark:text-cream">{{ $order->store->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">الدفع</p>
                        <p class="font-medium text-ink dark:text-cream">{{ $order->paymentMethod?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">حالة الدفع</p>
                        @if($order->payment_status === 'paid')
                            <span class="badge badge-forest">مدفوع</span>
                        @else
                            <span class="badge badge-stone">غير مدفوع</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                    <svg class="w-5 h-5 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <h3 class="font-display font-bold text-ink dark:text-cream">
                        المنتجات <span class="text-ink-muted dark:text-cream/50 text-sm">({{ $order->items->count() }})</span>
                    </h3>
                </div>
                <div class="divide-y divide-stone-100 dark:divide-stone-800">
                    @foreach($order->items as $item)
                        <div class="p-4 flex justify-between items-center gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-ink dark:text-cream">{{ $item->product_name }}</p>
                                <p class="text-xs text-ink-muted dark:text-cream/50 mt-1">
                                    {{ $item->size }} / {{ $item->color }} × {{ $item->quantity }}
                                </p>
                            </div>
                            <p class="font-display font-bold text-ink dark:text-cream whitespace-nowrap">
                                {{ number_format($item->total_price, 0) }} ₪
                            </p>
                        </div>
                    @endforeach
                </div>

                {{-- Summary --}}
                <div class="p-5 bg-stone-50 dark:bg-zinc-950 border-t border-stone-200 dark:border-stone-800 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-ink-muted dark:text-cream/60">المجموع الفرعي</span>
                        <span class="font-medium text-ink dark:text-cream">{{ number_format($order->subtotal, 0) }} ₪</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-ink-muted dark:text-cream/60">الشحن</span>
                        <span class="font-medium text-ink dark:text-cream">{{ number_format($order->shipping_cost, 0) }} ₪</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-ink-muted dark:text-cream/60">خصم</span>
                            <span class="font-medium text-forest-700 dark:text-gold-400">− {{ number_format($order->discount, 0) }} ₪</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-display font-bold text-lg pt-3 border-t border-stone-200 dark:border-stone-800">
                        <span class="text-ink dark:text-cream">الإجمالي</span>
                        <span class="text-forest-700 dark:text-gold-400">{{ number_format($order->total, 0) }} ₪</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ Sidebar ═══ --}}
        <div class="space-y-6">

            {{-- Customer --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-ink dark:text-cream">الزبون</h3>
                </div>
                <div class="space-y-2 text-sm">
                    <p class="font-medium text-ink dark:text-cream">{{ $order->customer->full_name }}</p>
                    <p class="text-ink-muted dark:text-cream/60">{{ $order->customer->email }}</p>
                    @if($order->customer->phone)
                        <p class="text-ink-muted dark:text-cream/60" dir="ltr">{{ $order->customer->phone }}</p>
                    @endif
                </div>
                <a href="{{ route('admin.users.show', $order->customer) }}"
                   class="inline-flex items-center gap-1.5 mt-4 text-sm font-medium text-forest-700 dark:text-gold-400 hover:gap-2.5 transition-all">
                    عرض الملف
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>

            {{-- Shipping --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-ink dark:text-cream">عنوان التوصيل</h3>
                </div>
                <p class="text-sm text-ink-soft dark:text-cream/70 leading-relaxed">{{ $order->shipping_address }}</p>

                @if($order->shippingZone)
                    <div class="mt-4 pt-4 border-t border-stone-200 dark:border-stone-800 text-xs text-ink-muted dark:text-cream/50">
                        <p>{{ $order->shippingZone->city }}</p>
                        <p class="mt-1">المدة: {{ $order->shippingZone->estimated_days }} أيام</p>
                    </div>
                @endif
            </div>

            {{-- Store --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-ink dark:text-cream">المتجر</h3>
                </div>
                <p class="font-medium text-ink dark:text-cream">{{ $order->store->name }}</p>
                <a href="{{ route('admin.stores.show', $order->store) }}"
                   class="inline-flex items-center gap-1.5 mt-3 text-sm font-medium text-forest-700 dark:text-gold-400 hover:gap-2.5 transition-all">
                    عرض المتجر
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

@endsection