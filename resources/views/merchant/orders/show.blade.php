@extends('merchant.layouts.app')

@section('title', 'تفاصيل الطلب ' . $order->order_number)
@section('page-title', 'تفاصيل الطلب')

@section('content')

    {{-- ═══ Breadcrumb ═══ --}}
    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
        <a href="{{ route('merchant.dashboard') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            لوحة التحكم
        </a>
        <span class="opacity-40">/</span>
        <a href="{{ route('merchant.orders.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            الطلبات
        </a>
        <span class="opacity-40">/</span>
        <span class="font-mono text-ink dark:text-cream">{{ $order->order_number }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ═══ Main Content ═══ --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Order Header --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                <div class="flex flex-wrap justify-between items-start gap-4 mb-5">
                    <div>
                        <h2 class="font-display text-2xl font-bold text-forest-700 dark:text-gold-400 font-mono">
                            {{ $order->order_number }}
                        </h2>
                        <p class="text-sm text-ink-muted dark:text-cream/60 mt-1">
                            {{ $order->created_at->format('Y/m/d — H:i') }}
                        </p>
                    </div>
                    @php
                        $statusMap = [
                            'pending' => ['⏳ قيد المراجعة', 'bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900/50'],
                            'processing' => ['⚙️ جاري التجهيز', 'bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-900/50'],
                            'shipped' => ['🚚 تم الشحن', 'bg-violet-50 dark:bg-violet-950/30 text-violet-700 dark:text-violet-300 border-violet-200 dark:border-violet-900/50'],
                            'delivering' => ['📍 جاري التوصيل', 'bg-orange-50 dark:bg-orange-950/30 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-900/50'],
                            'delivered' => ['✅ تم التسليم', 'bg-forest-50 dark:bg-forest-950/30 text-forest-700 dark:text-forest-300 border-forest-200 dark:border-forest-900/50'],
                            'cancelled' => ['❌ ملغى', 'bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-300 border-red-200 dark:border-red-900/50'],
                            'returned' => ['↩️ مُرجع', 'bg-stone-50 dark:bg-zinc-900 text-ink-muted dark:text-cream/60 border-stone-200 dark:border-stone-800'],
                        ];
                        [$statusLabel, $statusClass] = $statusMap[$order->status] ?? ['—', 'bg-stone-100'];
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 {{ $statusClass }} border rounded text-sm font-medium">
                        {{ $statusLabel }}
                    </span>
                </div>

                {{-- Order Info Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-5 pt-5 border-t border-stone-200 dark:border-stone-800">
                    <div>
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">حالة الدفع</p>
                        @if($order->payment_status === 'paid')
                            <span class="badge badge-forest">مدفوع</span>
                        @elseif($order->payment_status === 'refunded')
                            <span class="badge badge-stone">مسترد</span>
                        @else
                            <span class="badge badge-stone">غير مدفوع</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">طريقة الدفع</p>
                        <p class="font-medium text-ink dark:text-cream">{{ $order->paymentMethod?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">عدد المنتجات</p>
                        <p class="font-medium text-ink dark:text-cream">{{ $order->items->count() }}</p>
                    </div>
                    <div>
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">الإجمالي</p>
                        <p class="font-display font-bold text-forest-700 dark:text-gold-400 text-lg">
                            {{ number_format($order->total, 2) }} ₪
                        </p>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
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
                            <div class="w-16 h-16 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded flex items-center justify-center shrink-0">
                                <svg class="w-7 h-7 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-ink dark:text-cream">{{ $item->product_name }}</p>
                                <p class="text-xs text-ink-muted dark:text-cream/60 mt-1">
                                    المقاس: <span class="inline-block bg-stone-100 dark:bg-zinc-800 px-1.5 rounded">{{ $item->size }}</span>
                                    <span class="mx-2 opacity-40">|</span>
                                    اللون: {{ $item->color }}
                                </p>
                            </div>
                            <div class="text-center text-sm shrink-0">
                                <p class="text-xs text-ink-muted dark:text-cream/50">الكمية</p>
                                <p class="font-medium text-ink dark:text-cream">× {{ $item->quantity }}</p>
                            </div>
                            <div class="text-center text-sm shrink-0">
                                <p class="text-xs text-ink-muted dark:text-cream/50">سعر الوحدة</p>
                                <p class="font-medium text-ink dark:text-cream">{{ number_format($item->unit_price, 2) }} ₪</p>
                            </div>
                            <div class="text-left shrink-0">
                                <p class="text-xs text-ink-muted dark:text-cream/50">الإجمالي</p>
                                <p class="font-display font-bold text-forest-700 dark:text-gold-400">
                                    {{ number_format($item->total_price, 2) }} ₪
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Order Summary --}}
                <div class="p-6 bg-stone-50 dark:bg-zinc-950 border-t border-stone-200 dark:border-stone-800">
                    <div class="space-y-2.5 max-w-xs mr-auto">
                        <div class="flex justify-between text-sm">
                            <span class="text-ink-muted dark:text-cream/60">المجموع الفرعي:</span>
                            <span class="font-medium text-ink dark:text-cream">{{ number_format($order->subtotal, 2) }} ₪</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-sm text-forest-700 dark:text-gold-400">
                                <span>الخصم ({{ $order->coupon?->code }}):</span>
                                <span class="font-medium">− {{ number_format($order->discount, 2) }} ₪</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-ink-muted dark:text-cream/60">الشحن:</span>
                            <span class="font-medium text-ink dark:text-cream">{{ number_format($order->shipping_cost, 2) }} ₪</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-stone-200 dark:border-stone-800">
                            <span class="font-display font-bold text-ink dark:text-cream">الإجمالي:</span>
                            <span class="font-display font-bold text-forest-700 dark:text-gold-400 text-lg">
                                {{ number_format($order->total, 2) }} ₪
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Timeline --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-ink dark:text-cream">سجل الحالات</h3>
                </div>

                @if($order->statusHistory->count() > 0)
                    <div class="space-y-4">
                        @foreach($order->statusHistory->sortByDesc('created_at') as $history)
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
                            <div class="flex gap-4">
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
                                        @if($history->changedBy)
                                            • بواسطة {{ $history->changedBy->full_name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-ink-muted dark:text-cream/50 text-center py-4 text-sm">لا يوجد سجل حالات</p>
                @endif
            </div>
        </div>

        {{-- ═══ Sidebar ═══ --}}
        <div class="space-y-6">

            {{-- Update Status --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-ink dark:text-cream">تحديث الحالة</h3>
                </div>

                @if(in_array($order->status, ['cancelled', 'returned']))
                    <div class="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 rounded p-4 text-red-700 dark:text-red-300 text-sm flex items-start gap-3">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>
                            لا يمكن تعديل حالة طلب {{ $order->status === 'cancelled' ? 'ملغى' : 'مُرجع' }}
                        </span>
                    </div>
                @else
                    <form action="{{ route('merchant.orders.updateStatus', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="form-label">الحالة الجديدة</label>
                            <select name="status" required class="form-input">
                                <option value="">— اختر الحالة —</option>
                                <option value="pending" {{ $order->status == 'pending' ? 'disabled' : '' }}>⏳ قيد المراجعة</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'disabled' : '' }}>⚙️ جاري التجهيز</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'disabled' : '' }}>🚚 تم الشحن</option>
                                <option value="delivering" {{ $order->status == 'delivering' ? 'disabled' : '' }}>📍 جاري التوصيل</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'disabled' : '' }}>✅ تم التسليم</option>
                                <option value="cancelled">❌ إلغاء الطلب</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">ملاحظة (اختياري)</label>
                            <textarea name="note" rows="3" maxlength="500"
                                      placeholder="مثال: تم التواصل مع الزبون"
                                      class="form-input h-auto py-3 resize-none"></textarea>
                        </div>

                        <button type="submit" class="btn-solid w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            تحديث الحالة
                        </button>
                    </form>
                @endif
            </div>

            {{-- Customer Info --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-ink dark:text-cream">معلومات الزبون</h3>
                </div>

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">الاسم</p>
                        <p class="font-medium text-ink dark:text-cream">{{ $order->customer->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">البريد</p>
                        <p class="font-medium text-ink dark:text-cream break-all">{{ $order->customer->email }}</p>
                    </div>
                    @if($order->customer->phone)
                        <div>
                            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">الهاتف</p>
                            <p class="font-medium text-ink dark:text-cream" dir="ltr">{{ $order->customer->phone }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-ink dark:text-cream">عنوان التوصيل</h3>
                </div>

                <p class="text-sm text-ink-soft dark:text-cream/70 leading-relaxed">{{ $order->shipping_address }}</p>

                @if($order->shippingZone)
                    <div class="mt-4 pt-4 border-t border-stone-200 dark:border-stone-800">
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">منطقة الشحن</p>
                        <p class="font-medium text-ink dark:text-cream">
                            {{ $order->shippingZone->city }}, {{ $order->shippingZone->country }}
                        </p>
                        <p class="text-xs text-ink-muted dark:text-cream/50 mt-1">
                            المدة التقديرية: {{ $order->shippingZone->estimated_days }} أيام
                        </p>
                    </div>
                @endif

                @if($order->notes)
                    <div class="mt-4 pt-4 border-t border-stone-200 dark:border-stone-800">
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">ملاحظات الزبون</p>
                        <p class="text-sm text-ink-soft dark:text-cream/70 italic">"{{ $order->notes }}"</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection