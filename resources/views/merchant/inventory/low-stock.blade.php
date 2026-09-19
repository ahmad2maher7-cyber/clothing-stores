@extends('merchant.layouts.app')

@section('title', 'مخزون منخفض')
@section('page-title', 'المنتجات منخفضة المخزون')

@section('content')

    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('merchant.inventory.index') }}" class="hover:text-indigo-600">المخزون</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800">مخزون منخفض</span>
    </nav>

    <div class="bg-orange-50 border-r-4 border-orange-500 rounded-lg p-4 mb-6">
        <p class="font-bold text-orange-800">⚠️ يوجد {{ $variants->count() }} متغير بمخزون منخفض</p>
        <p class="text-sm text-orange-600">يُنصح بإعادة تعبئة المخزون قريباً لتجنب نفاد المنتجات</p>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-right">المنتج</th>
                    <th class="px-4 py-3 text-right">SKU</th>
                    <th class="px-4 py-3 text-right">المقاس</th>
                    <th class="px-4 py-3 text-right">اللون</th>
                    <th class="px-4 py-3 text-right">المخزون الحالي</th>
                    <th class="px-4 py-3 text-right">حد التنبيه</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($variants as $variant)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $variant->product->name }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $variant->sku }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-indigo-100 px-2 py-1 rounded text-xs">{{ $variant->size }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $variant->color }}</td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-orange-600 text-lg">{{ $variant->stock_quantity }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $variant->low_stock_threshold }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('merchant.products.edit', $variant->product) }}"
                               class="text-indigo-600 hover:text-indigo-900 text-sm">✏️ تعديل</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-500">
                            ✅ لا توجد منتجات بمخزون منخفض
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
