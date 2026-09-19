@extends('merchant.layouts.app')

@section('title', 'المخزون')
@section('page-title', 'إدارة المخزون')

@section('content')

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">إدارة المخزون</h2>
        <p class="text-gray-500 text-sm">متابعة وتحديث كميات جميع المنتجات</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-r-4 border-indigo-500">
            <p class="text-xs text-gray-500 mb-1">إجمالي المتغيرات</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_variants'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-r-4 border-green-500">
            <p class="text-xs text-gray-500 mb-1">إجمالي القطع</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['total_stock'] }}</p>
        </div>
        <a href="{{ route('merchant.inventory.index', ['filter' => 'low_stock']) }}"
           class="bg-white rounded-lg shadow p-4 border-r-4 border-orange-500 hover:shadow-md transition">
            <p class="text-xs text-gray-500 mb-1">⚠️ مخزون منخفض</p>
            <p class="text-2xl font-bold text-orange-600">{{ $stats['low_stock'] }}</p>
        </a>
        <a href="{{ route('merchant.inventory.index', ['filter' => 'out_of_stock']) }}"
           class="bg-white rounded-lg shadow p-4 border-r-4 border-red-500 hover:shadow-md transition">
            <p class="text-xs text-gray-500 mb-1">⛔ نفد المخزون</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['out_of_stock'] }}</p>
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 SKU، لون، أو اسم منتج..."
                   class="md:col-span-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">

            <select name="filter" class="border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">كل المخزون</option>
                <option value="in_stock" {{ request('filter') == 'in_stock' ? 'selected' : '' }}>✅ متوفر</option>
                <option value="low_stock" {{ request('filter') == 'low_stock' ? 'selected' : '' }}>⚠️ منخفض</option>
                <option value="out_of_stock" {{ request('filter') == 'out_of_stock' ? 'selected' : '' }}>⛔ نفد</option>
            </select>

            <div class="flex space-x-2 space-x-reverse">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex-1">
                    بحث
                </button>
                <a href="{{ route('merchant.inventory.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                    إعادة
                </a>
            </div>
        </form>
    </div>

    {{-- Low Stock Alert --}}
    @if($stats['low_stock'] > 0)
        <div class="bg-orange-50 border-r-4 border-orange-500 rounded-lg p-4 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-bold text-orange-800">⚠️ تنبيه: يوجد {{ $stats['low_stock'] }} متغير بمخزون منخفض</p>
                    <p class="text-sm text-orange-600">يُنصح بإعادة التعبئة قريباً</p>
                </div>
                <a href="{{ route('merchant.inventory.lowStock') }}"
                   class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">
                    عرض القائمة
                </a>
            </div>
        </div>
    @endif

    {{-- Inventory Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden" x-data="inventoryManager()">
        @if($variants->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المنتج</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">SKU</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المقاس</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">اللون</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المخزون</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">حد التنبيه</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">تعديل سريع</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($variants as $variant)
                            <tr class="hover:bg-gray-50" x-data="{ editing: false, newStock: {{ $variant->stock_quantity }}, reason: '' }">
                                <td class="px-4 py-3">
                                    <a href="{{ route('merchant.products.show', $variant->product) }}"
                                       class="font-medium text-gray-900 hover:text-indigo-600">
                                        {{ $variant->product->name }}
                                    </a>
                                    <div class="text-xs text-gray-500">{{ $variant->product->category?->name }}</div>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $variant->sku }}</td>
                                <td class="px-4 py-3">
                                    <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-xs">{{ $variant->size }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $variant->color }}</td>
                                <td class="px-4 py-3">
                                    <span x-show="!editing" class="font-bold text-lg
                                        @if($variant->stock_quantity <= 0) text-red-600
                                        @elseif($variant->stock_quantity <= $variant->low_stock_threshold) text-orange-600
                                        @else text-green-600 @endif">
                                        {{ $variant->stock_quantity }}
                                    </span>
                                    <input x-show="editing" type="number" x-model="newStock" min="0"
                                           class="w-20 border-gray-300 rounded text-sm focus:border-indigo-500">
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $variant->low_stock_threshold }}</td>
                                <td class="px-4 py-3">
                                    @if($variant->stock_quantity <= 0)
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs whitespace-nowrap">⛔ نفد</span>
                                    @elseif($variant->stock_quantity <= $variant->low_stock_threshold)
                                        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs whitespace-nowrap">⚠️ منخفض</span>
                                    @else
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs whitespace-nowrap">✅ متوفر</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div x-show="!editing" class="flex space-x-1 space-x-reverse">
                                        <button @click="editing = true"
                                                class="text-indigo-600 hover:text-indigo-900 text-xs">
                                            ✏️ تعديل
                                        </button>
                                        <a href="{{ route('merchant.inventory.variantLogs', $variant) }}"
                                           class="text-gray-600 hover:text-gray-900 text-xs mr-2">📜 سجل</a>
                                    </div>
                                    <div x-show="editing" class="flex space-x-1 space-x-reverse">
                                        <button @click="saveStock({{ $variant->id }}, newStock, reason)"
                                                class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">
                                            💾
                                        </button>
                                        <button @click="editing = false; newStock = {{ $variant->stock_quantity }}"
                                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-2 py-1 rounded text-xs">
                                            ✖️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t bg-gray-50">
                {{ $variants->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا يوجد مخزون</h3>
                <p class="text-gray-500">أضف منتجات ومتغيرات لتظهر هنا</p>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
function inventoryManager() {
    return {
        async saveStock(variantId, newStock, reason) {
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
                        reason: reason || 'تعديل سريع من صفحة المخزون',
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.error || 'حدث خطأ');
                }
            } catch (e) {
                alert('حدث خطأ في الاتصال');
            }
        }
    }
}
</script>
@endpush