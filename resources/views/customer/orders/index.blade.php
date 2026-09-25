@extends('layouts.public')

@section('title', 'طلباتي')

@section('content')

    <div class="max-w-[1400px] mx-auto px-4 py-6">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-[12px] mb-5" style="color: var(--text-tertiary);">
            <a href="{{ route('customer.dashboard') }}" class="transition hover:text-[color:var(--gold)]">حسابي</a>
            <span>/</span>
            <span style="color: var(--text-primary);">طلباتي</span>
        </nav>

        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold mb-1" style="color: var(--text-primary);">
                📦 طلباتي
            </h1>
            <p class="text-[13px]" style="color: var(--text-secondary);">
                تتبع وإدارة جميع طلباتك
            </p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
            <a href="{{ route('customer.orders.index') }}"
               class="rounded-xl border p-4 text-center transition hover:-translate-y-0.5"
               style="background-color: var(--bg-primary); border-color: var(--border-light); {{ !request('status') ? 'border-color: var(--gold);' : '' }}">
                <p class="text-[11px] mb-1" style="color: var(--text-secondary);">الكل</p>
                <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $stats['all'] }}</p>
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'pending']) }}"
               class="rounded-xl border p-4 text-center transition hover:-translate-y-0.5"
               style="background-color: var(--bg-primary); border-color: var(--border-light); {{ request('status') == 'pending' ? 'border-color: var(--gold);' : '' }}">
                <p class="text-[11px] mb-1" style="color: var(--text-secondary);">⏳ قيد المعالجة</p>
                <p class="text-2xl font-bold" style="color: #d97706;">{{ $stats['pending'] }}</p>
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'delivering']) }}"
               class="rounded-xl border p-4 text-center transition hover:-translate-y-0.5"
               style="background-color: var(--bg-primary); border-color: var(--border-light); {{ request('status') == 'delivering' ? 'border-color: var(--gold);' : '' }}">
                <p class="text-[11px] mb-1" style="color: var(--text-secondary);">🚚 قيد التوصيل</p>
                <p class="text-2xl font-bold" style="color: #7c3aed;">{{ $stats['delivering'] }}</p>
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'delivered']) }}"
               class="rounded-xl border p-4 text-center transition hover:-translate-y-0.5"
               style="background-color: var(--bg-primary); border-color: var(--border-light); {{ request('status') == 'delivered' ? 'border-color: var(--gold);' : '' }}">
                <p class="text-[11px] mb-1" style="color: var(--text-secondary);">✅ تم التسليم</p>
                <p class="text-2xl font-bold" style="color: #166534;">{{ $stats['delivered'] }}</p>
            </a>
        </div>

        {{-- Search --}}
        <div class="rounded-xl border p-4 mb-5"
             style="background-color: var(--bg-primary); border-color: var(--border-light);">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="🔍 ابحث برقم الطلب..."
                       class="form-input flex-1">
                <button type="submit"
                        class="h-11 px-5 rounded-lg font-semibold text-white text-[13px] transition hover:opacity-90"
                        style="background-color: var(--gold);">
                    بحث
                </button>
            </form>
        </div>

        {{-- Orders --}}
        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    @php
                        $statusConfig = [
                            'pending' => ['⏳ قيد المراجعة', '#fef3c7', '#92400e'],
                            'processing' => ['⚙️ جاري التجهيز', '#dbeafe', '#1e40af'],
                            'shipped' => ['🚚 تم الشحن', '#ede9fe', '#6d28d9'],
                            'delivering' => ['📍 جاري التوصيل', '#fed7aa', '#9a3412'],
                            'delivered' => ['✅ تم التسليم', '#d1fae5', '#065f46'],
                            'cancelled' => ['❌ ملغى', '#fee2e2', '#991b1b'],
                            'returned' => ['↩️ مُرجع', '#f3f4f6', '#374151'],
                        ];
                        [$label, $bg, $color] = $statusConfig[$order->status] ?? ['غير معروف', '#f3f4f6', '#374151'];
                    @endphp

                    <div class="rounded-xl border overflow-hidden transition hover:shadow-md"
                         style="background-color: var(--bg-primary); border-color: var(--border-light);">

                        {{-- Header --}}
                        <div class="p-4 border-b flex flex-wrap justify-between items-center gap-3"
                             style="border-color: var(--border-light); background-color: var(--bg-tertiary);">
                            <div class="flex items-center gap-4">
                                <div>
                                    <p class="text-[10px]" style="color: var(--text-tertiary);">رقم الطلب</p>
                                    <p class="text-[13px] font-bold font-mono" style="color: var(--gold);">
                                        {{ $order->order_number }}
                                    </p>
                                </div>
                                <div class="border-r pr-4" style="border-color: var(--border-light);">
                                    <p class="text-[10px]" style="color: var(--text-tertiary);">التاريخ</p>
                                    <p class="text-[12px] font-medium" style="color: var(--text-primary);">
                                        {{ $order->created_at->format('Y/m/d') }}
                                    </p>
                                </div>
                            </div>

                            <span class="text-[11px] font-bold px-3 py-1.5 rounded-lg"
                                  style="background-color: {{ $bg }}; color: {{ $color }};">
                                {{ $label }}
                            </span>
                        </div>

                        {{-- Body --}}
                        <div class="p-4">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center text-2xl"
                                     style="background-color: var(--gold-soft);">
                                    🏪
                                </div>
                                <div>
                                    <p class="text-[13px] font-medium" style="color: var(--text-primary);">
                                        {{ $order->store->name }}
                                    </p>
                                    <p class="text-[11px]" style="color: var(--text-tertiary);">
                                        {{ $order->items_count }} {{ $order->items_count == 1 ? 'منتج' : 'منتجات' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-3 border-t"
                                 style="border-color: var(--border-light);">
                                <div>
                                    <p class="text-[10px]" style="color: var(--text-tertiary);">الإجمالي</p>
                                    <p class="text-xl font-bold" style="color: var(--gold);">
                                        {{ number_format($order->total, 0) }} ₪
                                    </p>
                                </div>
                                <a href="{{ route('customer.orders.show', $order) }}"
                                   class="inline-flex items-center gap-1 h-9 px-4 rounded-lg font-semibold text-white text-[12px] transition hover:opacity-90"
                                   style="background-color: var(--gold);">
                                    عرض التفاصيل
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-5">{{ $orders->links() }}</div>
        @else
            <div class="rounded-xl border p-14 text-center"
                 style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--text-primary);">لا توجد طلبات</h3>
                <p class="text-[13px] mb-5" style="color: var(--text-secondary);">
                    ابدأ التسوق لإنشاء أول طلب
                </p>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 h-11 px-6 rounded-lg font-bold text-white text-[13px]"
                   style="background-color: var(--gold);">
                    🛍️ تسوق الآن
                </a>
            </div>
        @endif
    </div>

@endsection