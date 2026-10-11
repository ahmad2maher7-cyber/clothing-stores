@extends('merchant.layouts.app')

@section('title', 'المحفظة')
@section('page-title', 'المحفظة')

@section('content')

    {{-- ═══ Header ═══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <span class="eyebrow block mb-2">— المالية</span>
            <h1 class="font-display text-2xl font-bold text-ink dark:text-cream">💼 المحفظة</h1>
            <p class="text-sm text-ink-muted dark:text-cream/60 mt-1">
                إدارة أرباحك وطلبات السحب
            </p>
        </div>

        @if($stats['available'] >= 50)
            <a href="{{ route('merchant.wallet.withdraw.form') }}" class="btn-solid whitespace-nowrap">
                💸 طلب سحب
            </a>
        @endif
    </div>

    {{-- ═══ Stats Cards ═══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- Balance --}}
        <div class="bg-forest-900 dark:bg-forest-950 text-white rounded-lg p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gold-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <p class="relative text-xs tracking-widest uppercase text-gold-400 mb-2">💰 الرصيد الحالي</p>
            <p class="relative font-display text-3xl font-bold">{{ number_format($stats['balance'], 2) }} ₪</p>
        </div>

        {{-- Available --}}
        <div class="bg-white dark:bg-zinc-900 border-2 border-emerald-500 dark:border-emerald-600 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-emerald-600 dark:text-emerald-400 mb-2">✅ متاح للسحب</p>
            <p class="font-display text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                {{ number_format($stats['available'], 2) }} ₪
            </p>
        </div>

        {{-- Pending --}}
        <div class="bg-white dark:bg-zinc-900 border border-amber-200 dark:border-amber-900/50 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-amber-600 dark:text-amber-400 mb-2">⏳ معلق</p>
            <p class="font-display text-3xl font-bold text-amber-600 dark:text-amber-400">
                {{ number_format($stats['pending'], 2) }} ₪
            </p>
        </div>

        {{-- Total Earned --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">📈 إجمالي الأرباح</p>
            <p class="font-display text-3xl font-bold text-ink dark:text-cream">
                {{ number_format($stats['total_earned'], 2) }} ₪
            </p>
        </div>
    </div>

    {{-- ═══ Info Banner ═══ --}}
    <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/50 rounded-lg p-4 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="text-sm text-blue-800 dark:text-blue-300">
            <p class="font-semibold mb-1">💡 كيف تعمل المحفظة؟</p>
            <ul class="space-y-1 text-xs leading-relaxed">
                <li>• تُضاف الأرباح تلقائياً إلى محفظتك بعد تسليم الطلب للزبون</li>
                <li>• عمولة المنصة: <strong>{{ $stats['commission_rate'] }}%</strong> من قيمة المنتجات (بدون الشحن)</li>
                <li>• الحد الأدنى للسحب: <strong>50 ₪</strong></li>
                <li>• يُعالج المشرف طلب السحب خلال 1-3 أيام عمل</li>
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ═══ Recent Transactions ═══ --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
            <div class="p-5 border-b border-stone-200 dark:border-stone-800">
                <h3 class="font-display font-bold text-ink dark:text-cream">📜 آخر الحركات</h3>
            </div>

            @if($transactions->count() > 0)
                <div class="divide-y divide-stone-100 dark:divide-stone-800 max-h-96 overflow-y-auto">
                    @foreach($transactions as $t)
                        @php
                            $icon = match($t->type) {
                                'earning' => '💰',
                                'withdrawal' => '💸',
                                'refund' => '↩️',
                                'adjustment' => '⚙️',
                                default => '📋',
                            };
                            $isPositive = (float) $t->amount > 0;
                            $isZero = (float) $t->amount == 0;
                        @endphp
                        <div class="p-4 flex justify-between items-center gap-3 hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg shrink-0
                                            {{ $isPositive ? 'bg-emerald-50 dark:bg-emerald-950/30' : ($isZero ? 'bg-stone-100 dark:bg-zinc-800' : 'bg-red-50 dark:bg-red-950/20') }}">
                                    {{ $icon }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-ink dark:text-cream truncate">{{ $t->description }}</p>
                                    <p class="text-xs text-ink-muted dark:text-cream/50">
                                        {{ $t->created_at->diffForHumans() }}
                                        @if($t->commission > 0)
                                            • عمولة: {{ number_format($t->commission, 2) }} ₪
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-left shrink-0">
                                <p class="font-display font-bold {{ $isPositive ? 'text-emerald-600 dark:text-emerald-400' : ($isZero ? 'text-ink-muted dark:text-cream/50' : 'text-red-600 dark:text-red-400') }}">
                                    {{ $isPositive ? '+' : '' }}{{ number_format($t->amount, 2) }} ₪
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="text-5xl mb-3">📭</div>
                    <p class="text-sm text-ink-muted dark:text-cream/50">لا توجد حركات بعد</p>
                </div>
            @endif
        </div>

        {{-- ═══ Recent Withdrawal Requests ═══ --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
            <div class="p-5 border-b border-stone-200 dark:border-stone-800">
                <h3 class="font-display font-bold text-ink dark:text-cream">💸 طلبات السحب</h3>
            </div>

            @if($withdrawals->count() > 0)
                <div class="divide-y divide-stone-100 dark:divide-stone-800 max-h-96 overflow-y-auto">
                    @foreach($withdrawals as $w)
                        @php
                            $statusColors = [
                                'pending' => 'bg-amber-50 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/50',
                                'approved' => 'bg-blue-50 dark:bg-blue-950/30 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-900/50',
                                'paid' => 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900/50',
                                'rejected' => 'bg-red-50 dark:bg-red-950/30 text-red-800 dark:text-red-300 border-red-200 dark:border-red-900/50',
                            ];
                            $colorClass = $statusColors[$w->status] ?? 'bg-stone-100';
                        @endphp
                        <div class="p-4">
                            <div class="flex justify-between items-start gap-3 mb-2">
                                <div>
                                    <p class="font-display font-bold text-ink dark:text-cream">
                                        #{{ $w->id }} — {{ number_format($w->amount, 2) }} ₪
                                    </p>
                                    <p class="text-xs text-ink-muted dark:text-cream/50 mt-0.5">
                                        {{ $w->payment_method_label }} • {{ $w->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full border {{ $colorClass }} whitespace-nowrap">
                                    {{ $w->status_label }}
                                </span>
                            </div>

                            @if($w->admin_note)
                                <div class="mt-2 p-2 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded text-xs text-ink-soft dark:text-cream/70">
                                    <strong>ملاحظة المشرف:</strong> {{ $w->admin_note }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="text-5xl mb-3">💸</div>
                    <p class="text-sm text-ink-muted dark:text-cream/50 mb-4">لم تقم بأي طلب سحب بعد</p>
                    @if($stats['available'] >= 50)
                        <a href="{{ route('merchant.wallet.withdraw.form') }}" class="btn-solid inline-flex">
                            💸 اطلب سحب الآن
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

@endsection