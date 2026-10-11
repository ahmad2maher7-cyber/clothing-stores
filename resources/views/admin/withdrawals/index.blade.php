@extends('admin.layouts.app')

@section('title', 'طلبات السحب')
@section('page-title', 'طلبات السحب')

@section('content')

    {{-- ═══ Header ═══ --}}
    <div class="mb-6">
        <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">💸 طلبات السحب</h2>
        <p class="text-sm text-ink-muted dark:text-cream/60">إدارة طلبات سحب أرباح التجار</p>
    </div>

    {{-- ═══ Stats ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <a href="{{ route('admin.withdrawals.index') }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-4 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ !request('status') ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">الكل</p>
            <p class="font-display text-2xl font-bold text-ink dark:text-cream">{{ $stats['all'] }}</p>
        </a>

        <a href="{{ route('admin.withdrawals.index', ['status' => 'pending']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-4 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('status') == 'pending' ? 'border-amber-500 dark:border-amber-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">⏳ معلق</p>
            <p class="font-display text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending'] }}</p>
            <p class="text-xs text-ink-muted dark:text-cream/50 mt-1">{{ number_format($stats['pending_amount'], 0) }} ₪</p>
        </a>

        <a href="{{ route('admin.withdrawals.index', ['status' => 'approved']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-4 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('status') == 'approved' ? 'border-blue-500 dark:border-blue-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">✅ موافق عليه</p>
            <p class="font-display text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['approved'] }}</p>
        </a>

        <a href="{{ route('admin.withdrawals.index', ['status' => 'paid']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-4 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('status') == 'paid' ? 'border-emerald-500 dark:border-emerald-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">💰 تم التحويل</p>
            <p class="font-display text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['paid'] }}</p>
            <p class="text-xs text-ink-muted dark:text-cream/50 mt-1">{{ number_format($stats['paid_amount'], 0) }} ₪</p>
        </a>

        <a href="{{ route('admin.withdrawals.index', ['status' => 'rejected']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-4 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('status') == 'rejected' ? 'border-red-500 dark:border-red-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">❌ مرفوض</p>
            <p class="font-display text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['rejected'] }}</p>
        </a>
    </div>

    {{-- ═══ Search ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 mb-6">
        <form method="GET" class="flex gap-2">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث باسم المتجر، صاحب الحساب، أو رقم الطلب..."
                       class="form-input pl-10">
                <i class="fa-solid fa-magnifying-glass absolute right-3 top-1/2 -translate-y-1/2 text-ink-muted dark:text-cream/40 pointer-events-none"></i>
            </div>
            <button type="submit" class="btn-solid">بحث</button>
            <a href="{{ route('admin.withdrawals.index') }}" class="btn-outline">إعادة</a>
        </form>
    </div>

    {{-- ═══ Table ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
        @if($withdrawals->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">#</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المتجر</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المبلغ</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">طريقة السحب</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">التاريخ</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($withdrawals as $w)
                            <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-4 py-3 font-mono text-xs text-ink-muted dark:text-cream/50">
                                    #{{ $w->id }}
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-ink dark:text-cream">{{ $w->store->name }}</p>
                                    <p class="text-xs text-ink-muted dark:text-cream/50">{{ $w->account_name }}</p>
                                </td>
                                <td class="px-4 py-3 font-display font-bold text-ink dark:text-cream">
                                    {{ number_format($w->amount, 2) }} ₪
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    {{ $w->payment_method_label }}
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $colors = [
                                            'pending' => 'badge-stone border-amber-200 dark:border-amber-900/50',
                                            'approved' => 'badge-stone border-blue-200',
                                            'paid' => 'badge-forest',
                                            'rejected' => 'badge-danger',
                                        ];
                                    @endphp
                                    <span class="badge {{ $colors[$w->status] ?? 'badge-stone' }} whitespace-nowrap">
                                        {{ $w->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-ink-muted dark:text-cream/50 whitespace-nowrap">
                                    {{ $w->created_at->format('Y/m/d H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.withdrawals.show', $w) }}"
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
                {{ $withdrawals->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <i class="fa-solid fa-inbox text-4xl text-ink-muted dark:text-cream/40"></i>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد طلبات سحب</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60">ستظهر هنا طلبات السحب من التجار</p>
            </div>
        @endif
    </div>

@endsection