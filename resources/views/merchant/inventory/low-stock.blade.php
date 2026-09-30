@extends('merchant.layouts.app')

@section('title', 'مخزون منخفض')
@section('page-title', 'المنتجات منخفضة المخزون')

@section('content')

    {{-- ═══ Breadcrumb ═══ --}}
    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
        <a href="{{ route('merchant.inventory.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            المخزون
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">مخزون منخفض</span>
    </nav>

    {{-- ═══ Alert Banner ═══ --}}
    <div class="bg-amber-50 dark:bg-amber-950/30 border-r-4 border-amber-500 rounded-lg p-5 mb-6 flex items-start gap-3">
        <div class="w-10 h-10 flex items-center justify-center bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 rounded-full shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="font-bold text-amber-800 dark:text-amber-300 mb-1">
                يوجد {{ $variants->count() }} متغير بمخزون منخفض
            </p>
            <p class="text-sm text-amber-700 dark:text-amber-400/80">
                يُنصح بإعادة تعبئة المخزون قريباً لتجنب نفاد المنتجات
            </p>
        </div>
    </div>

    {{-- ═══ Table ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
        @if($variants->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المنتج</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">SKU</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المقاس</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">اللون</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المخزون الحالي</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">حد التنبيه</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($variants as $variant)
                            <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-4 py-3">
                                    <a href="{{ route('merchant.products.show', $variant->product) }}"
                                       class="font-medium text-ink dark:text-cream hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                                        {{ $variant->product->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-ink-muted dark:text-cream/60">{{ $variant->sku }}</td>
                                <td class="px-4 py-3">
                                    <span class="badge badge-stone">{{ $variant->size }}</span>
                                </td>
                                <td class="px-4 py-3 text-ink-soft dark:text-cream/70">{{ $variant->color }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-display font-bold text-xl text-amber-600 dark:text-amber-400">
                                        {{ $variant->stock_quantity }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-ink-muted dark:text-cream/60">{{ $variant->low_stock_threshold }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('merchant.products.edit', $variant->product) }}"
                                       class="inline-flex items-center gap-1 text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        تعديل
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- ═══ Empty State ═══ --}}
            <div class="text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 rounded-full">
                    <svg class="w-10 h-10 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">
                    لا توجد منتجات بمخزون منخفض
                </h3>
                <p class="text-sm text-ink-muted dark:text-cream/60">
                    جميع منتجاتك بمخزون جيد ✅
                </p>
            </div>
        @endif
    </div>

@endsection