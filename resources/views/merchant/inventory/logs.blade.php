@extends('merchant.layouts.app')

@section('title', 'سجل حركات المخزون')
@section('page-title', 'سجل حركات المخزون')

@section('content')

    {{-- Breadcrumb --}}
    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
        <a href="{{ route('merchant.inventory.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            المخزون
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">سجل الحركات</span>
    </nav>

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">سجل حركات المخزون</h2>
        <p class="text-sm text-ink-muted dark:text-cream/60">
            @if($variant)
                <i class="fa-solid fa-filter text-[10px] ml-1"></i>
                حركات المتغير: {{ $variant->product->name }} - {{ $variant->size }} / {{ $variant->color }}
            @else
                <i class="fa-solid fa-list text-[10px] ml-1"></i>
                جميع حركات المخزون
            @endif
        </p>
    </div>

    {{-- Filter Badge --}}
    @if($variant)
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 mb-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <i class="fa-solid fa-filter"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-ink dark:text-cream">عرض سجل متغير محدد</p>
                    <p class="text-xs text-ink-muted dark:text-cream/50">
                        <i class="fa-solid fa-barcode text-[10px] ml-1"></i>
                        SKU: {{ $variant->sku }} — المخزون: {{ $variant->stock_quantity }}
                    </p>
                </div>
            </div>
            <a href="{{ route('merchant.inventory.logs') }}" class="btn-secondary btn-sm">
                <i class="fa-solid fa-list"></i>
                عرض كل السجل
            </a>
        </div>
    @endif

    {{-- Logs Table --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
        @if($logs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">التاريخ</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المنتج</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">النوع</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الكمية</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">السبب</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">بواسطة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($logs as $log)
                            <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-4 py-3 text-xs text-ink-muted dark:text-cream/60 whitespace-nowrap">
                                    <i class="fa-regular fa-calendar text-[10px] ml-1"></i>
                                    {{ $log->created_at->format('Y/m/d H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-ink dark:text-cream">
                                        {{ $log->variant->product->name }}
                                    </div>
                                    <div class="text-xs text-ink-muted dark:text-cream/50">
                                        {{ $log->variant->size }} / {{ $log->variant->color }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($log->change_type === 'in')
                                        <span class="badge badge-forest">
                                            <i class="fa-solid fa-plus text-[10px]"></i>
                                            إدخال
                                        </span>
                                    @elseif($log->change_type === 'out')
                                        <span class="badge badge-danger">
                                            <i class="fa-solid fa-minus text-[10px]"></i>
                                            إخراج
                                        </span>
                                    @else
                                        <span class="badge badge-stone">
                                            <i class="fa-solid fa-gear text-[10px]"></i>
                                            تعديل
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-display font-bold text-ink dark:text-cream">
                                    {{ $log->quantity }}
                                </td>
                                <td class="px-4 py-3 text-xs text-ink-soft dark:text-cream/70">
                                    {{ $log->reason ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-xs text-ink-muted dark:text-cream/50">
                                    <i class="fa-solid fa-user text-[10px] ml-1"></i>
                                    {{ $log->createdBy?->full_name ?? 'النظام' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-stone-200 dark:border-stone-800">
                {{ $logs->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <i class="fa-solid fa-clock-rotate-left text-4xl text-ink-muted dark:text-cream/40"></i>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد حركات</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60 mb-6">
                    @if($variant)
                        لم يتم تسجيل أي حركات لهذا المتغير
                    @else
                        لم يتم تسجيل أي حركات مخزون بعد
                    @endif
                </p>
                <a href="{{ route('merchant.inventory.index') }}" class="btn-solid inline-flex">
                    <i class="fa-solid fa-arrow-left"></i>
                    العودة للمخزون
                </a>
            </div>
        @endif
    </div>

@endsection