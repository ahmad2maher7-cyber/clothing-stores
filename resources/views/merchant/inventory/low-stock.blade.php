@extends('merchant.layouts.app')

@section('title', 'مخزون منخفض')
@section('page-title', 'المنتجات منخفضة المخزون')

@section('content')

    {{-- Breadcrumb --}}
    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
        <a href="{{ route('merchant.inventory.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            المخزون
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">مخزون منخفض</span>
    </nav>

    {{-- Alert --}}
    <div class="bg-amber-50 dark:bg-amber-950/30 border-r-4 border-amber-500 rounded-lg p-5 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400 rounded">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            </div>
            <div>
                <p class="font-display font-bold text-amber-800 dark:text-amber-300">
                    يوجد {{ $variants->count() }} متغير بمخزون منخفض
                </p>
                <p class="text-sm text-amber-700 dark:text-amber-400">
                    <i class="fa-solid fa-info-circle text-[10px] ml-1"></i>
                    يُنصح بإعادة تعبئة المخزون قريباً
                </p>
            </div>
        </div>
    </div>

    {{-- Table --}}
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
                                <td class="px-4 py-3 font-medium text-ink dark:text-cream">
                                    {{ $variant->product->name }}
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-ink-muted dark:text-cream/60">{{ $variant->sku }}</td>
                                <td class="px-4 py-3">
                                    <span class="badge badge-stone">{{ $variant->size }}</span>
                                </td>
                                <td class="px-4 py-3 text-ink-soft dark:text-cream/70">{{ $variant->color }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-display font-bold text-amber-600 dark:text-amber-400 text-lg">
                                        {{ $variant->stock_quantity }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-ink-muted dark:text-cream/50">{{ $variant->low_stock_threshold }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('merchant.products.edit', $variant->product) }}"
                                       class="inline-flex items-center gap-1 text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                        تعديل
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-green-100 dark:bg-green-950/40 rounded-full">
                    <i class="fa-solid fa-check-circle text-4xl text-green-600 dark:text-green-400"></i>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">ممتاز!</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60">لا توجد منتجات بمخزون منخفض</p>
            </div>
        @endif
    </div>

@endsection