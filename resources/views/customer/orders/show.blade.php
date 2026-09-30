@extends('customer.layouts.app')

@section('title', 'تفاصيل الطلب ' . $order->order_number)

@section('content')

    <div class="container-x py-10 lg:py-14">

        {{-- ═══ Breadcrumb ═══ --}}
        <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">
            <a href="{{ route('customer.orders.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                طلباتي
            </a>
            <span class="opacity-40">/</span>
            <span class="font-mono text-ink dark:text-cream">{{ $order->order_number }}</span>
        </nav>

        {{-- ═══ Status Header ═══ --}}
        @php
            $statusConfig = [
                'pending' => ['⏳', 'قيد المراجعة', 'سنتواصل معك لتأكيد الطلب', 'bg-amber-50 dark:bg-amber-950/30 border-amber-200 dark:border-amber-900/50 text-amber-800 dark:text-amber-300'],
                'processing' => ['⚙️', 'جاري التجهيز', 'يتم تجهيز طلبك الآن', 'bg-blue-50 dark:bg-blue-950/30 border-blue-200 dark:border-blue-900/50 text-blue-800 dark:text-blue-300'],
                'shipped' => ['🚚', 'تم الشحن', 'طلبك في الطريق إليك', 'bg-violet-50 dark:bg-violet-950/30 border-violet-200 dark:border-violet-900/50 text-violet-800 dark:text-violet-300'],
                'delivering' => ['📍', 'جاري التوصيل', 'سيتصل بك المندوب قريباً', 'bg-orange-50 dark:bg-orange-950/30 border-orange-200 dark:border-orange-900/50 text-orange-800 dark:text-orange-300'],
                'delivered' => ['✅', 'تم التسليم', 'نتمنى أن تكون راضياً عن طلبك', 'bg-forest-50 dark:bg-forest-950/30 border-forest-200 dark:border-forest-900/50 text-forest-800 dark:text-forest-300'],
                'cancelled' => ['❌', 'ملغى', 'تم إلغاء الطلب', 'bg-red-50 dark:bg-red-950/30 border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-300'],
                'returned' => ['↩️', 'مُرجع', 'تم إرجاع الطلب', 'bg-stone-50 dark:bg-zinc-900 border-stone-200 dark:border-stone-800 text-ink-muted dark:text-cream/60'],
            ];
            [$icon, $label, $desc, $statusClasses] = $statusConfig[$order->status] ?? ['📦', 'غير معروف', '', 'bg-stone-50 dark:bg-zinc-900 border-stone-200'];
        @endphp

        <div class="{{ $statusClasses }} border rounded-lg p-8 mb-6 text-center">
            <div class="text-5xl mb-3">{{ $icon }}</div>
            <h1 class="font-display text-2xl font-bold mb-2">{{ $label }}</h1>
            <p class="text-sm opacity-80">{{ $desc }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ═══ Main ═══ --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Items --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                    <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <h3 class="font-display font-bold text-ink dark:text-cream">
                            المنتجات <span class="text-ink-muted dark:text-cream/50 text-sm">({{ $order->items->count() }})</span>
                        </h3>
                    </div>

                    <div class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($order->items as $item)
                            <div class="p-4 flex items-center gap-4">
                                <div class="w-14 h-14 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-sm text-ink dark:text-cream">{{ $item->product_name }}</p>
                                    <p class="text-xs text-ink-muted dark:text-cream/50 mt-1">
                                        المقاس: <span class="inline-block bg-stone-100 dark:bg-zinc-800 px-1.5 rounded">{{ $item->size }}</span>
                                        <span class="mx-2 opacity-40">•</span>
                                        اللون: {{ $item->color }}
                                    </p>
                                </div>
                                <div class="text-center text-sm shrink-0">
                                    <p class="text-xs text-ink-muted dark:text-cream/50">الكمية</p>
                                    <p class="font-medium text-ink dark:text-cream">× {{ $item->quantity }}</p>
                                </div>
                                <div class="text-left shrink-0 min-w-[80px]">
                                    <p class="text-xs text-ink-muted dark:text-cream/50">الإجمالي</p>
                                    <p class="font-bold text-sm text-ink dark:text-cream">{{ number_format($item->total_price, 0) }} ₪</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Timeline --}}
                @if($order->statusHistory->count() > 0)
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-display font-bold text-ink dark:text-cream">سجل الطلب</h3>
                        </div>

                        <div class="space-y-4">
                            @foreach($order->statusHistory->sortBy('created_at') as $history)
                                @php
                                    $histLabels = [
                                        'pending' => '⏳ قيد المراجعة',
                                        'processing' => '⚙️ جاري التجهيز',
                                        'shipped' => '🚚 تم الشحن',
                                        'delivering' => '📍 جاري التوصيل',
                                        'delivered' => '✅ تم التسليم',
                                        'cancelled' => '❌ ملغى',
                                        'returned' => '↩️ مُرجع',
                                    ];
                                @endphp
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center shrink-0">
                                        <div class="w-2.5 h-2.5 rounded-full bg-forest-700 dark:bg-gold-400 mt-1.5"></div>
                                        @if(!$loop->last)
                                            <div class="w-px h-full bg-stone-200 dark:bg-stone-800 my-1"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 pb-4">
                                        <p class="font-medium text-sm text-ink dark:text-cream">
                                            {{ $histLabels[$history->status] ?? $history->status }}
                                        </p>
                                        @if($history->note)
                                            <p class="text-xs text-ink-muted dark:text-cream/60 mt-1">{{ $history->note }}</p>
                                        @endif
                                        <p class="text-xs text-ink-faint dark:text-cream/40 mt-1">
                                            {{ $history->created_at->format('Y/m/d — H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- ═══ Sidebar ═══ --}}
            <div class="space-y-6">

                {{-- Order Info --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-display font-bold text-ink dark:text-cream">تفاصيل الطلب</h3>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center gap-3">
                            <span class="text-ink-muted dark:text-cream/50">رقم الطلب:</span>
                            <span class="font-mono font-medium text-forest-700 dark:text-gold-400">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between items-center gap-3">
                            <span class="text-ink-muted dark:text-cream/50">التاريخ:</span>
                            <span class="text-ink dark:text-cream">{{ $order->created_at->format('Y/m/d') }}</span>
                        </div>
                        <div class="flex justify-between items-center gap-3">
                            <span class="text-ink-muted dark:text-cream/50">المتجر:</span>
                            <a href="{{ route('stores.show', $order->store) }}" 
                               class="text-ink dark:text-cream hover:text-forest-700 dark:hover:text-gold-400 transition-colors truncate">
                                {{ $order->store->name }}
                            </a>
                        </div>
                        <div class="flex justify-between items-center gap-3">
                            <span class="text-ink-muted dark:text-cream/50">طريقة الدفع:</span>
                            <span class="text-ink dark:text-cream">{{ $order->paymentMethod?->name ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between items-center gap-3">
                            <span class="text-ink-muted dark:text-cream/50">حالة الدفع:</span>
                            @if($order->payment_status === 'paid')
                                <span class="badge badge-forest">مدفوع</span>
                            @else
                                <span class="badge badge-stone">غير مدفوع</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-5 pt-5 border-t border-stone-200 dark:border-stone-800">
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">عنوان التوصيل</p>
                        <p class="text-sm text-ink dark:text-cream leading-relaxed">{{ $order->shipping_address }}</p>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-display font-bold text-ink dark:text-cream">ملخص الفاتورة</h3>
                    </div>

                    <div class="space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-ink-muted dark:text-cream/60">المجموع الفرعي</span>
                            <span class="text-ink dark:text-cream">{{ number_format($order->subtotal, 2) }} ₪</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-forest-700 dark:text-gold-400">
                                <span>الخصم</span>
                                <span>− {{ number_format($order->discount, 2) }} ₪</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-ink-muted dark:text-cream/60">الشحن</span>
                            <span class="text-ink dark:text-cream">{{ number_format($order->shipping_cost, 2) }} ₪</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-stone-200 dark:border-stone-800">
                            <span class="font-display font-bold text-ink dark:text-cream">الإجمالي</span>
                            <span class="font-display font-bold text-forest-700 dark:text-gold-400 text-lg">{{ number_format($order->total, 0) }} ₪</span>
                        </div>
                    </div>
                </div>

                {{-- Cancel --}}
                @if($order->status === 'pending')
                    <form action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('هل أنت متأكد من إلغاء الطلب؟')"
                                class="w-full inline-flex items-center justify-center gap-2 h-12 px-6 text-xs font-semibold tracking-widest uppercase text-red-600 dark:text-red-400 border border-stone-300 dark:border-stone-700 hover:border-red-300 dark:hover:border-red-800 hover:bg-red-50 dark:hover:bg-red-950/20 rounded transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            إلغاء الطلب
                        </button>
                    </form>
                @endif

                {{-- Review --}}
                @if($order->status === 'delivered')
                    <a href="{{ route('customer.reviews.create', $order) }}" 
                       class="w-full inline-flex items-center justify-center gap-2 h-12 px-6 text-xs font-semibold tracking-widest uppercase bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 hover:bg-forest-800 dark:hover:bg-gold-400 rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        قيّم الطلب
                    </a>
                @endif
            </div>
        </div>
    </div>

@endsection