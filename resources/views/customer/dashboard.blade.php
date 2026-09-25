@extends('layouts.public')

@section('title', 'حسابي')

@section('content')

    <div class="max-w-[1400px] mx-auto px-4 py-6">

        {{-- Welcome Banner --}}
        <div class="rounded-xl p-6 mb-6 border"
             style="background-color: var(--gold-soft); border-color: var(--border-light);">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold text-white"
                     style="background-color: var(--gold);">
                    {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold mb-1" style="color: var(--text-primary);">
                        مرحباً {{ auth()->user()->full_name }} 👋
                    </h1>
                    <p class="text-[13px]" style="color: var(--text-secondary);">
                        نتمنى لك تجربة تسوق ممتعة
                    </p>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <a href="{{ route('customer.orders.index') }}"
               class="rounded-xl border p-5 transition hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="text-3xl mb-3">📦</div>
                <p class="text-2xl font-bold mb-1" style="color: var(--text-primary);">
                    {{ $stats['orders'] }}
                </p>
                <p class="text-[12px]" style="color: var(--text-secondary);">إجمالي الطلبات</p>
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'pending']) }}"
               class="rounded-xl border p-5 transition hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="text-3xl mb-3">⏳</div>
                <p class="text-2xl font-bold mb-1" style="color: #d97706;">
                    {{ $stats['pending'] }}
                </p>
                <p class="text-[12px]" style="color: var(--text-secondary);">قيد المعالجة</p>
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'delivered']) }}"
               class="rounded-xl border p-5 transition hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="text-3xl mb-3">✅</div>
                <p class="text-2xl font-bold mb-1" style="color: #166534;">
                    {{ $stats['delivered'] }}
                </p>
                <p class="text-[12px]" style="color: var(--text-secondary);">تم التسليم</p>
            </a>

            <a href="{{ route('customer.wishlist') }}"
               class="rounded-xl border p-5 transition hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="text-3xl mb-3">❤️</div>
                <p class="text-2xl font-bold mb-1" style="color: #dc2626;">
                    {{ $stats['wishlist'] }}
                </p>
                <p class="text-[12px]" style="color: var(--text-secondary);">في المفضلة</p>
            </a>
        </div>

        {{-- Total Spent --}}
        <div class="rounded-xl p-6 mb-6 text-white"
             style="background-color: var(--gold);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[12px] mb-1 opacity-90">💰 إجمالي مشترياتك</p>
                    <p class="text-3xl md:text-4xl font-bold">
                        {{ number_format($stats['total_spent'], 0) }} ₪
                    </p>
                </div>
                <span class="text-6xl opacity-80">💳</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Quick Links --}}
            <div class="rounded-xl border overflow-hidden"
                 style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="px-5 py-4 border-b" style="border-color: var(--border-light);">
                    <h2 class="text-[14px] font-bold" style="color: var(--text-primary);">
                        ⚡ روابط سريعة
                    </h2>
                </div>
                <div class="p-2">
                    <a href="{{ route('customer.orders.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition"
                       style="color: var(--text-primary);"
                       onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                       onmouseout="this.style.backgroundColor='transparent';">
                        <span class="text-xl">📦</span>
                        <span class="text-[13px] font-medium">طلباتي</span>
                    </a>
                    <a href="{{ route('customer.wishlist') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition"
                       style="color: var(--text-primary);"
                       onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                       onmouseout="this.style.backgroundColor='transparent';">
                        <span class="text-xl">❤️</span>
                        <span class="text-[13px] font-medium">المفضلة</span>
                    </a>
                    <a href="{{ route('customer.profile') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition"
                       style="color: var(--text-primary);"
                       onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                       onmouseout="this.style.backgroundColor='transparent';">
                        <span class="text-xl">👤</span>
                        <span class="text-[13px] font-medium">الملف الشخصي</span>
                    </a>
                    <a href="{{ route('customer.reviews.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition"
                       style="color: var(--text-primary);"
                       onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                       onmouseout="this.style.backgroundColor='transparent';">
                        <span class="text-xl">⭐</span>
                        <span class="text-[13px] font-medium">تقييماتي</span>
                    </a>
                    <a href="{{ route('cart.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition"
                       style="color: var(--text-primary);"
                       onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                       onmouseout="this.style.backgroundColor='transparent';">
                        <span class="text-xl">🛒</span>
                        <span class="text-[13px] font-medium">السلة</span>
                    </a>
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="lg:col-span-2 rounded-xl border overflow-hidden"
                 style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="px-5 py-4 border-b flex items-center justify-between"
                     style="border-color: var(--border-light);">
                    <h2 class="text-[14px] font-bold" style="color: var(--text-primary);">
                        📦 آخر الطلبات
                    </h2>
                    <a href="{{ route('customer.orders.index') }}"
                       class="text-[12px] font-semibold flex items-center gap-1"
                       style="color: var(--gold);">
                        عرض الكل
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                </div>

                @if($recentOrders->count() > 0)
                    <div class="divide-y" style="border-color: var(--border-light);">
                        @foreach($recentOrders as $order)
                            @php
                                $statusLabels = [
                                    'pending' => ['⏳ قيد المراجعة', '#d97706'],
                                    'processing' => ['⚙️ جاري التجهيز', '#2563eb'],
                                    'shipped' => ['🚚 تم الشحن', '#7c3aed'],
                                    'delivering' => ['📍 جاري التوصيل', '#ea580c'],
                                    'delivered' => ['✅ تم التسليم', '#166534'],
                                    'cancelled' => ['❌ ملغى', '#dc2626'],
                                ];
                                [$label, $color] = $statusLabels[$order->status] ?? ['غير معروف', 'var(--text-secondary)'];
                            @endphp
                            <a href="{{ route('customer.orders.show', $order) }}"
                               class="p-4 flex items-center justify-between gap-4 transition"
                               onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                               onmouseout="this.style.backgroundColor='transparent';">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                         style="background-color: var(--gold-soft);">
                                        📦
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-semibold truncate font-mono" style="color: var(--text-primary);">
                                            {{ $order->order_number }}
                                        </p>
                                        <p class="text-[11px]" style="color: var(--text-tertiary);">
                                            {{ $order->created_at->format('Y/m/d') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-left shrink-0">
                                    <p class="text-[14px] font-bold" style="color: var(--gold);">
                                        {{ number_format($order->total, 0) }} ₪
                                    </p>
                                    <p class="text-[10px]" style="color: {{ $color }};">{{ $label }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="py-14 text-center">
                        <div class="text-5xl mb-3">📭</div>
                        <p class="text-[13px] mb-4" style="color: var(--text-secondary);">
                            لا توجد طلبات بعد
                        </p>
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center gap-2 h-10 px-5 rounded-lg font-semibold text-white text-[12px]"
                           style="background-color: var(--gold);">
                            🛍️ ابدأ التسوق
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection