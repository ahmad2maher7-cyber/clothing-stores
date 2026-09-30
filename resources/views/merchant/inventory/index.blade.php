@extends('merchant.layouts.app')

@section('title', 'المخزون')
@section('page-title', 'المخزون')

@section('content')

    <div class="mb-6">
        <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">إدارة المخزون</h2>
        <p class="text-sm text-ink-muted dark:text-cream/60">متابعة وتحديث كميات المنتجات</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 text-ink-muted dark:text-cream/60 rounded">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">إجمالي المتغيرات</p>
            <p class="font-display text-2xl font-bold text-ink dark:text-cream">{{ $stats['total_variants'] }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <i class="fa-solid fa-cubes"></i>
                </div>
            </div>
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">إجمالي القطع</p>
            <p class="font-display text-2xl font-bold text-forest-700 dark:text-gold-400">{{ number_format($stats['total_stock']) }}</p>
        </div>

        <a href="{{ route('merchant.inventory.index', ['filter' => 'low_stock']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-5 hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('filter') == 'low_stock' ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 flex items-center justify-center bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">⚠️ مخزون منخفض</p>
            <p class="font-display text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['low_stock'] }}</p>
        </a>

        <a href="{{ route('merchant.inventory.index', ['filter' => 'out_of_stock']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-5 hover:border-red-500 transition
                  {{ request('filter') == 'out_of_stock' ? 'border-red-600 dark:border-red-500' : 'border-stone-200 dark:border-stone-800' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 flex items-center justify-center bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 rounded">
                    <i class="fa-solid fa-ban"></i>
                </div>
            </div>
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">⛔ نفد المخزون</p>
            <p class="font-display text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['out_of_stock'] }}</p>
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث بـ SKU أو اسم المنتج..."
                       class="form-input pl-10">
                <i class="fa-solid fa-magnifying-glass text-sm text-ink-muted dark:text-cream/40 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>

            <select name="filter" class="form-input">
                <option value="">كل المخزون</option>
                <option value="in_stock" {{ request('filter') == 'in_stock' ? 'selected' : '' }}>متوفر</option>
                <option value="low_stock" {{ request('filter') == 'low_stock' ? 'selected' : '' }}>منخفض</option>
                <option value="out_of_stock" {{ request('filter') == 'out_of_stock' ? 'selected' : '' }}>نفد</option>
            </select>

            <button type="submit" class="btn-solid">
                <i class="fa-solid fa-magnifying-glass"></i>
                بحث
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden" x-data="inventoryManager()">
        @if($variants->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المنتج</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">SKU</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المقاس</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">اللون</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المخزون</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">تعديل سريع</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($variants as $variant)
                            <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors" 
                                x-data="{ editing: false, newStock: {{ $variant->stock_quantity }} }">
                                <td class="px-4 py-3">
                                    <a href="{{ route('merchant.products.show', $variant->product) }}"
                                       class="font-medium text-ink dark:text-cream hover:text-forest-700 dark:hover:text-gold-400 transition-colors line-clamp-1">
                                        {{ $variant->product->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-ink-muted dark:text-cream/60">{{ $variant->sku }}</td>
                                <td class="px-4 py-3">
                                    <span class="badge badge-stone">{{ $variant->size }}</span>
                                </td>
                                <td class="px-4 py-3 text-ink-soft dark:text-cream/70">{{ $variant->color }}</td>
                                <td class="px-4 py-3">
                                    <span x-show="!editing" 
                                          class="font-display font-bold text-lg
                                                 @if($variant->stock_quantity <= 0) text-red-600 dark:text-red-400
                                                 @elseif($variant->stock_quantity <= $variant->low_stock_threshold) text-amber-600 dark:text-amber-400
                                                 @else text-forest-700 dark:text-gold-400 @endif">
                                        {{ $variant->stock_quantity }}
                                    </span>
                                    <input x-show="editing" x-cloak type="number" x-model="newStock" min="0"
                                           class="form-input w-24 text-sm">
                                </td>
                                <td class="px-4 py-3">
                                    @if($variant->stock_quantity <= 0)
                                        <span class="badge badge-danger">نفد</span>
                                    @elseif($variant->stock_quantity <= $variant->low_stock_threshold)
                                        <span class="badge badge-stone">منخفض</span>
                                    @else
                                        <span class="badge badge-forest">متوفر</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div x-show="!editing" class="flex items-center gap-3">
                                        <button @click="editing = true"
                                                class="inline-flex items-center gap-1 text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                            تعديل
                                        </button>
                                        <a href="{{ route('merchant.inventory.variantLogs', $variant) }}"
                                           class="inline-flex items-center gap-1 text-xs font-medium text-ink-muted dark:text-cream/60 hover:text-ink dark:hover:text-cream transition-colors">
                                            <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                                            سجل
                                        </a>
                                    </div>
                                    <div x-show="editing" x-cloak class="flex items-center gap-1">
                                        <button @click="saveStock({{ $variant->id }}, newStock)"
                                                class="inline-flex items-center justify-center gap-1 h-8 px-3 text-xs font-semibold bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 hover:bg-forest-800 dark:hover:bg-gold-400 rounded transition-colors">
                                            حفظ
                                        </button>
                                        <button @click="editing = false; newStock = {{ $variant->stock_quantity }}"
                                                class="inline-flex items-center justify-center h-8 px-3 text-xs font-medium text-ink dark:text-cream border border-stone-300 dark:border-stone-700 hover:border-forest-500 dark:hover:border-gold-400 rounded transition-colors">
                                            إلغاء
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-stone-200 dark:border-stone-800">
                {{ $variants->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <i class="fa-solid fa-boxes-stacked text-4xl text-ink-muted dark:text-cream/40"></i>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا يوجد مخزون</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60">لم يتم العثور على نتائج مطابقة</p>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
function inventoryManager() {
    return {
        async saveStock(variantId, newStock) {
            try {
                const response = await fetch(`/merchant/inventory/variants/${variantId}/stock`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        stock_quantity: parseInt(newStock),
                        reason: 'تعديل سريع',
                    }),
                });
                const data = await response.json();
                if (data.success) {
                    showToast(data.message || 'تم تحديث المخزون', 'success');
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showToast(data.error || 'حدث خطأ', 'error');
                }
            } catch (e) {
                showToast('حدث خطأ في الاتصال', 'error');
            }
        }
    }
}
</script>
@endpush