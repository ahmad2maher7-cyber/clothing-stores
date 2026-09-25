@extends('layouts.public')

@section('title', 'تفاصيل الطلب ' . $order->order_number)

@section('content')

    <div class="max-w-[1000px] mx-auto px-4 py-6">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-[12px] mb-5" style="color: var(--text-tertiary);">
            <a href="{{ route('customer.orders.index') }}" class="transition hover:text-[color:var(--gold)]">طلباتي</a>
            <span>/</span>
            <span class="font-mono" style="color: var(--text-primary);">{{ $order->order_number }}</span>
        </nav>

        @php
            $statusConfig = [
                'pending' => ['⏳ قيد المراجعة', 'سنتواصل معك لتأكيد الطلب', '#fef3c7', '#92400e'],
                'processing' => ['⚙️ جاري التجهيز', 'يتم تجهيز طلبك الآن', '#dbeafe', '#1e40af'],
                'shipped' => ['🚚 تم الشحن', 'طلبك في الطريق إليك', '#ede9fe', '#6d28d9'],
                'delivering' => ['📍 جاري التوصيل', 'سيتصل بك المندوب قريباً', '#fed7aa', '#9a3412'],
                'delivered' => ['✅ تم التسليم', 'نتمنى أن تكون راضياً', '#d1fae5', '#065f46'],
                'cancelled' => ['❌ ملغى', 'تم إلغاء الطلب', '#fee2e2', '#991b1b'],
            ];
            [$statusLabel, $statusDesc, $statusBg, $statusColor] = $statusConfig[$order->status] ?? ['غير معروف', '', '#f3f4f6', '#374151'];
        @endphp

        {{-- Status Header --}}
        <div class="rounded-xl border-2 p-6 md:p-8 mb-5 text-center"
             style="background-color: {{ $statusBg }}; border-color: {{ $statusColor }};">
            <div class="text-5xl mb-3">
                @switch($order->status)
                    @case('pending') ⏳ @break
                    @case('processing') ⚙️ @break
                    @case('shipped') 🚚 @break
                    @case('delivering') 📍 @break
                    @case('delivered') ✅ @break
                    @case('cancelled') ❌ @break
                    @default 📦
                @endswitch
            </div>
            <h2 class="text-xl md:text-2xl font-bold mb-1" style="color: {{ $statusColor }};">
                {{ $statusLabel }}
            </h2>
            <p class="text-[13px]" style="color: {{ $statusColor }};">{{ $statusDesc }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Items --}}
                <div class="rounded-xl border overflow-hidden"
                     style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div class="px-5 py-4 border-b" style="border-color: var(--border-light);">
                        <h3 class="text-[14px] font-bold" style="color: var(--text-primary);">
                            🛍️ المنتجات ({{ $order->items->count() }})
                        </h3>
                    </div>
                    <div class="divide-y" style="border-color: var(--border-light);">
                        @foreach($order->items as $item)
                            <div class="p-4 flex items-center gap-4">
                                <div class="w-16 h-16 rounded-lg overflow-hidden shrink-0 border"
                                     style="background-color: var(--bg-tertiary); border-color: var(--border-light);">
                                    @if($item->variant->product->primaryImage)
                                        <img src="{{ asset('storage/' . $item->variant->product->primaryImage->image_url) }}"
                                             class="w-full h-full object-cover" alt="">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-3xl">👕</div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-medium mb-1" style="color: var(--text-primary);">
                                        {{ $item->product_name }}
                                    </p>
                                    <div class="flex flex-wrap gap-2 text-[11px]">
                                        <span class="px-2 py-0.5 rounded"
                                              style="background-color: var(--gold-soft); color: var(--gold);">
                                            مقاس: {{ $item->size }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded"
                                              style="background-color: var(--bg-tertiary); color: var(--text-secondary);">
                                            لون: {{ $item->color }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-left shrink-0">
                                    <p class="text-[11px]" style="color: var(--text-tertiary);">
                                        × {{ $item->quantity }}
                                    </p>
                                    <p class="text-[15px] font-bold" style="color: var(--gold);">
                                        {{ number_format($item->total_price, 0) }} ₪
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Timeline --}}
                @if($order->statusHistory->count() > 0)
                    <div class="rounded-xl border p-5"
                         style="background-color: var(--bg-primary); border-color: var(--border-light);">
                        <h3 class="text-[14px] font-bold mb-4" style="color: var(--text-primary);">
                            📜 سجل الطلب
                        </h3>
                        <div class="space-y-4">
                            @foreach($order->statusHistory->sortBy('created_at') as $history)
                                @php
                                    $historyLabels = [
                                        'pending' => '⏳ قيد المراجعة',
                                        'processing' => '⚙️ جاري التجهيز',
                                        'shipped' => '🚚 تم الشحن',
                                        'delivering' => '📍 جاري التوصيل',
                                        'delivered' => '✅ تم التسليم',
                                        'cancelled' => '❌ ملغى',
                                    ];
                                @endphp
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center shrink-0">
                                        <div class="w-3 h-3 rounded-full mt-1" style="background-color: var(--gold);"></div>
                                        @if(!$loop->last)
                                            <div class="w-0.5 flex-1 my-1" style="background-color: var(--border-light);"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <p class="text-[13px] font-semibold" style="color: var(--text-primary);">
                                            {{ $historyLabels[$history->status] ?? $history->status }}
                                        </p>
                                        @if($history->note)
                                            <p class="text-[12px] mt-1" style="color: var(--text-secondary);">
                                                {{ $history->note }}
                                            </p>
                                        @endif
                                        <p class="text-[11px] mt-1" style="color: var(--text-tertiary);">
                                            {{ $history->created_at->format('Y/m/d - H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-4">

                {{-- Order Info --}}
                <div class="rounded-xl border p-5"
                     style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <h3 class="text-[14px] font-bold mb-4" style="color: var(--text-primary);">
                        📋 تفاصيل الطلب
                    </h3>
                    <div class="space-y-3 text-[13px]">
                        <div class="flex justify-between">
                            <span style="color: var(--text-secondary);">رقم الطلب:</span>
                            <span class="font-mono font-medium" style="color: var(--text-primary);">
                                {{ $order->order_number }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: var(--text-secondary);">التاريخ:</span>
                            <span style="color: var(--text-primary);">
                                {{ $order->created_at->format('Y/m/d') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: var(--text-secondary);">المتجر:</span>
                            <a href="{{ route('stores.show', $order->store) }}"
                               class="transition hover:text-[color:var(--gold)]"
                               style="color: var(--gold);">
                                {{ $order->store->name }}
                            </a>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: var(--text-secondary);">طريقة الدفع:</span>
                            <span style="color: var(--text-primary);">
                                {{ $order->paymentMethod?->name ?? '—' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: var(--text-secondary);">حالة الدفع:</span>
                            @if($order->payment_status === 'paid')
                                <span style="color: #166534;">✅ مدفوع</span>
                            @else
                                <span style="color: #d97706;">⏳ غير مدفوع</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t" style="border-color: var(--border-light);">
                        <p class="text-[11px] mb-1" style="color: var(--text-tertiary);">عنوان التوصيل</p>
                        <p class="text-[12px]" style="color: var(--text-primary);">
                            {{ $order->shipping_address }}
                        </p>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="rounded-xl border p-5"
                     style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <h3 class="text-[14px] font-bold mb-4" style="color: var(--text-primary);">
                        💰 ملخص الفاتورة
                    </h3>
                    <div class="space-y-2 text-[13px]">
                        <div class="flex justify-between">
                            <span style="color: var(--text-secondary);">المجموع الفرعي:</span>
                            <span style="color: var(--text-primary);">
                                {{ number_format($order->subtotal, 0) }} ₪
                            </span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between" style="color: #166534;">
                                <span>الخصم:</span>
                                <span>- {{ number_format($order->discount, 0) }} ₪</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span style="color: var(--text-secondary);">الشحن:</span>
                            <span style="color: var(--text-primary);">
                                {{ number_format($order->shipping_cost, 0) }} ₪
                            </span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t"
                             style="border-color: var(--border-light);">
                            <span class="text-[14px] font-bold" style="color: var(--text-primary);">الإجمالي:</span>
                            <span class="text-xl font-bold" style="color: var(--gold);">
                                {{ number_format($order->total, 0) }} ₪
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Cancel --}}
                @if($order->status === 'pending')
                    <form action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('هل أنت متأكد من إلغاء الطلب؟')"
                                class="w-full h-11 rounded-lg font-semibold text-[13px] border-2 transition"
                                style="background-color: #fef2f2; color: #dc2626; border-color: #fecaca;">
                            ❌ إلغاء الطلب
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

@endsection