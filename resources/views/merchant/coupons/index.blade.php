@extends('merchant.layouts.app')

@section('title', 'الكوبونات')
@section('page-title', 'الكوبونات')

@section('content')

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">الكوبونات</h2>
            <p class="text-sm text-ink-muted dark:text-cream/60">إدارة أكواد الخصم</p>
        </div>
        <a href="{{ route('merchant.coupons.create') }}" class="btn-solid whitespace-nowrap">
            <i class="fa-solid fa-plus"></i>
            إضافة كوبون
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">إجمالي</p>
            <p class="font-display text-3xl font-bold text-ink dark:text-cream">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">نشطة</p>
            <p class="font-display text-3xl font-bold text-forest-700 dark:text-gold-400">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">منتهية</p>
            <p class="font-display text-3xl font-bold text-ink-muted dark:text-cream/60">{{ $stats['expired'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">مرات الاستخدام</p>
            <p class="font-display text-3xl font-bold text-forest-700 dark:text-gold-400">{{ $stats['total_used'] }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
        @if($coupons->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الكود</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الخصم</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الفترة</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الاستخدام</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الحالة</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($coupons as $coupon)
                            @php
                                $isExpired = $coupon->end_date < now();
                                $isFull = $coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit;
                                $isActive = $coupon->status === 'active' && !$isExpired && !$isFull;
                            @endphp
                            <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-mono font-bold text-forest-700 dark:text-gold-400 bg-forest-50 dark:bg-forest-950/40 px-2.5 py-1 rounded text-sm">
                                        {{ $coupon->code }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($coupon->type === 'percentage')
                                        <span class="font-display font-bold text-ink dark:text-cream text-lg">{{ $coupon->value }}%</span>
                                    @else
                                        <span class="font-display font-bold text-ink dark:text-cream text-lg">{{ number_format($coupon->value, 0) }} ₪</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-ink-muted dark:text-cream/60">
                                    <div>{{ $coupon->start_date->format('Y/m/d') }}</div>
                                    <div class="text-ink-faint dark:text-cream/40 mt-0.5">
                                        <i class="fa-solid fa-arrow-left text-[8px]"></i> {{ $coupon->end_date->format('Y/m/d') }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-medium text-ink dark:text-cream">
                                        {{ $coupon->used_count }}@if($coupon->usage_limit)<span class="text-ink-muted dark:text-cream/50"> / {{ $coupon->usage_limit }}</span>@endif
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($isActive)
                                        <span class="badge badge-forest">نشط</span>
                                    @elseif($isExpired)
                                        <span class="badge badge-stone">منتهي</span>
                                    @elseif($isFull)
                                        <span class="badge badge-stone">استُنفد</span>
                                    @else
                                        <span class="badge badge-stone">معطل</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('merchant.coupons.edit', $coupon) }}"
                                           class="inline-flex items-center gap-1 text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                            تعديل
                                        </a>
                                        <form action="{{ route('merchant.coupons.destroy', $coupon) }}"
                                              method="POST" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف الكوبون؟')"
                                              class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 text-xs font-medium text-red-600 dark:text-red-400 hover:opacity-70 transition-opacity">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-stone-200 dark:border-stone-800">
                {{ $coupons->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <i class="fa-solid fa-ticket text-4xl text-ink-muted dark:text-cream/40"></i>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد كوبونات</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60 mb-6">أنشئ كوبون خصم لجذب الزبائن</p>
                <a href="{{ route('merchant.coupons.create') }}" class="btn-solid inline-flex">
                    <i class="fa-solid fa-plus"></i>
                    إضافة كوبون
                </a>
            </div>
        @endif
    </div>

@endsection