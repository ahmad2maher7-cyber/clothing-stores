@extends('admin.layouts.app')

@section('title', 'الطلبات')
@section('page-title', 'الطلبات')

@section('content')

    {{-- ═══ Header + Export ═══ --}}
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">جميع الطلبات</h2>
            <p class="text-sm text-ink-muted dark:text-cream/60">متابعة طلبات المنصة</p>
        </div>

        {{-- 📊 Export Button --}}
        <a href="{{ route('exports.orders', request()->only(['status', 'payment_status'])) }}"
           class="inline-flex items-center gap-2 h-11 px-6 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded transition-colors shadow-sm whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            📊 تصدير Excel
        </a>
    </div>

    {{-- ═══ Stats ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">إجمالي</p>
            <p class="font-display text-3xl font-bold text-ink dark:text-cream">{{ $stats['all'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">⏳ قيد المراجعة</p>
            <p class="font-display text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">✅ تم التسليم</p>
            <p class="font-display text-3xl font-bold text-forest-700 dark:text-gold-400">{{ $stats['delivered'] }}</p>
        </div>
        <div class="bg-forest-900 dark:bg-forest-950 text-white rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-gold-400 mb-2">💰 الإيرادات</p>
            <p class="font-display text-2xl font-bold">{{ number_format($stats['revenue'], 0) }} ₪</p>
        </div>
    </div>

    {{-- ═══ Filters ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث برقم الطلب أو اسم الزبون..."
                       class="form-input pl-10">
                <svg class="w-4 h-4 text-ink-muted dark:text-cream/40 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <select name="status" class="form-input">
                <option value="">كل الحالات</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>التجهيز</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>الشحن</option>
                <option value="delivering" {{ request('status') == 'delivering' ? 'selected' : '' }}>التوصيل</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>التسليم</option>
            </select>
            <button type="submit" class="btn-solid">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                بحث
            </button>
        </form>
    </div>

    {{-- ═══ Table ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">رقم الطلب</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الزبون</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المتجر</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الإجمالي</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الحالة</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($orders as $order)
                            <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="font-mono font-medium text-forest-700 dark:text-gold-400">{{ $order->order_number }}</p>
                                    <p class="text-xs text-ink-muted dark:text-cream/50">{{ $order->created_at->format('Y/m/d') }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-ink dark:text-cream">{{ $order->customer->full_name }}</p>
                                </td>
                                <td class="px-4 py-3 text-ink-soft dark:text-cream/70">
                                    {{ $order->store->name }}
                                </td>
                                <td class="px-4 py-3 font-display font-bold text-ink dark:text-cream">
                                    {{ number_format($order->total, 0) }} ₪
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['⏳ قيد المراجعة', 'badge-stone'],
                                            'processing' => ['⚙️ التجهيز', 'badge-stone'],
                                            'shipped' => ['🚚 الشحن', 'badge-stone'],
                                            'delivering' => ['📍 التوصيل', 'badge-stone'],
                                            'delivered' => ['✅ التسليم', 'badge-forest'],
                                            'cancelled' => ['❌ ملغى', 'badge-danger'],
                                        ];
                                        [$label, $badgeClass] = $statusConfig[$order->status] ?? ['—', 'badge-stone'];
                                    @endphp
                                    <span class="badge {{ $badgeClass }} whitespace-nowrap">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="inline-flex items-center gap-1 text-xs font-medium text-forest-700 dark:text-gold-400 hover:gap-2 transition-all">
                                        عرض
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-stone-200 dark:border-stone-800">
                {{ $orders->links() }}
            </div>
        @else
            {{-- ═══ Empty State ═══ --}}
            <div class="text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <svg class="w-10 h-10 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد طلبات</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60">ستظهر الطلبات هنا عند وصولها</p>
            </div>
        @endif
    </div>

@endsection