@extends('merchant.layouts.app')

@section('title', 'الطلبات')
@section('page-title', 'الطلبات')

@section('content')

        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">الطلبات</h2>
            <p class="text-sm text-ink-muted dark:text-cream/60">إدارة ومتابعة طلبات متجرك</p>
        </div>

        {{-- 📊 Export Button --}}
        <a href="{{ route('exports.orders', request()->only(['status', 'payment_status'])) }}"
           class="inline-flex items-center justify-center gap-2 h-11 px-5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded transition-colors whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            📊 تصدير Excel
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3 mb-6">
        @php
            $statsConfig = [
                ['key' => 'all',         'label' => 'الكل',            'icon' => 'fa-list',              'color' => 'default'],
                ['key' => 'pending',     'label' => 'قيد الانتظار',    'icon' => 'fa-clock',             'status' => 'pending',     'color' => 'amber'],
                ['key' => 'processing',  'label' => 'جاري التجهيز',    'icon' => 'fa-cog',               'status' => 'processing',  'color' => 'blue'],
                ['key' => 'shipped',     'label' => 'تم الشحن',        'icon' => 'fa-truck',             'status' => 'shipped',     'color' => 'violet'],
                ['key' => 'delivering',  'label' => 'قيد التوصيل',     'icon' => 'fa-motorcycle',        'status' => 'delivering',  'color' => 'orange'],
                ['key' => 'delivered',   'label' => 'تم التسليم',      'icon' => 'fa-check-circle',      'status' => 'delivered',   'color' => 'forest'],
            ];
        @endphp

        @foreach($statsConfig as $config)
            @php
                $isActive = isset($config['status']) 
                    ? request('status') == $config['status'] 
                    : !request('status');
            @endphp
            <a href="{{ route('merchant.orders.index', isset($config['status']) ? ['status' => $config['status']] : []) }}"
               class="bg-white dark:bg-zinc-900 border rounded-lg p-4 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                      {{ $isActive ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
                <div class="flex items-center justify-center gap-1 mb-2">
                    <i class="fa-solid {{ $config['icon'] }} text-[10px] 
                              @if($config['color'] === 'amber') text-amber-600 dark:text-amber-400
                              @elseif($config['color'] === 'blue') text-blue-600 dark:text-blue-400
                              @elseif($config['color'] === 'violet') text-violet-600 dark:text-violet-400
                              @elseif($config['color'] === 'orange') text-orange-600 dark:text-orange-400
                              @elseif($config['color'] === 'forest') text-forest-700 dark:text-gold-400
                              @else text-ink-muted dark:text-cream/50 @endif"></i>
                    <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50">{{ $config['label'] }}</p>
                </div>
                <p class="font-display text-xl font-bold
                          @if($config['color'] === 'amber') text-amber-600 dark:text-amber-400
                          @elseif($config['color'] === 'blue') text-blue-600 dark:text-blue-400
                          @elseif($config['color'] === 'violet') text-violet-600 dark:text-violet-400
                          @elseif($config['color'] === 'orange') text-orange-600 dark:text-orange-400
                          @elseif($config['color'] === 'forest') text-forest-700 dark:text-gold-400
                          @else text-ink dark:text-cream @endif">
                    {{ $stats[$config['key']] }}
                </p>
            </a>
        @endforeach

        <div class="bg-forest-900 dark:bg-forest-950 text-white rounded-lg p-4 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gold-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <p class="relative text-[10px] tracking-widest uppercase text-gold-400 mb-2">
                <i class="fa-solid fa-coins"></i> الإيرادات
            </p>
            <p class="relative font-display text-lg font-bold">{{ number_format($stats['total_revenue'], 0) }} ₪</p>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث برقم الطلب أو اسم الزبون..."
                       class="form-input pl-10">
                <i class="fa-solid fa-magnifying-glass text-sm text-ink-muted dark:text-cream/40 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>

            <select name="payment_status" class="form-input">
                <option value="">كل حالات الدفع</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
            </select>

            <button type="submit" class="btn-solid">
                <i class="fa-solid fa-magnifying-glass"></i>
                بحث
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">رقم الطلب</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الزبون</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المنتجات</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الإجمالي</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الدفع</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">التاريخ</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($orders as $order)
                            @php
                                $statusConfig = [
                                    'pending'    => ['قيد المراجعة',   'fa-clock',          'badge-stone'],
                                    'processing' => ['جاري التجهيز',   'fa-cog',            'badge-stone'],
                                    'shipped'    => ['تم الشحن',       'fa-truck',          'badge-stone'],
                                    'delivering' => ['جاري التوصيل',   'fa-motorcycle',     'badge-stone'],
                                    'delivered'  => ['تم التسليم',     'fa-check-circle',   'badge-forest'],
                                    'cancelled'  => ['ملغى',           'fa-times-circle',   'badge-danger'],
                                    'returned'   => ['مُرجع',          'fa-undo',           'badge-stone'],
                                ];
                                [$statusLabel, $statusIcon, $badgeClass] = $statusConfig[$order->status] ?? ['—', 'fa-question', 'badge-stone'];
                            @endphp
                            <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-mono font-medium text-forest-700 dark:text-gold-400">{{ $order->order_number }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-ink dark:text-cream">{{ $order->customer->full_name }}</p>
                                    @if($order->customer->phone)
                                        <p class="text-xs text-ink-muted dark:text-cream/50 mt-0.5" dir="ltr">{{ $order->customer->phone }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge badge-stone">
                                        <i class="fa-solid fa-box text-[10px]"></i>
                                        {{ $order->items_count }} منتج
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-display font-bold text-ink dark:text-cream">{{ number_format($order->total, 0) }} ₪</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($order->payment_status === 'paid')
                                        <span class="badge badge-forest">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                            مدفوع
                                        </span>
                                    @else
                                        <span class="badge badge-stone">
                                            <i class="fa-solid fa-clock text-[10px]"></i>
                                            غير مدفوع
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge {{ $badgeClass }} whitespace-nowrap">
                                        <i class="fa-solid {{ $statusIcon }} text-[10px]"></i>
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-ink-muted dark:text-cream/50 whitespace-nowrap">
                                    <i class="fa-regular fa-calendar text-[10px] ml-1"></i>
                                    {{ $order->created_at->format('Y/m/d') }}
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('merchant.orders.show', $order) }}"
                                       class="inline-flex items-center gap-1 text-xs font-medium text-forest-700 dark:text-gold-400 hover:gap-2 transition-all">
                                        عرض
                                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
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
            <div class="text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <i class="fa-solid fa-inbox text-4xl text-ink-muted dark:text-cream/40"></i>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد طلبات</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60">ستظهر الطلبات هنا عند وصولها</p>
            </div>
        @endif
    </div>

@endsection