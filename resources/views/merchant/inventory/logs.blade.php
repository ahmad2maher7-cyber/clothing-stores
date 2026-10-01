@extends('merchant.layouts.app')

@section('title', 'سجل حركات المخزون')
@section('page-title', 'سجل حركات المخزون')

@section('content')

    {{-- Breadcrumb --}}
    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('merchant.inventory.index') }}" class="hover:text-gray-900">المخزون</a>
        <span class="mx-2 text-gray-300">/</span>
        <span class="text-gray-900">سجل الحركات</span>
    </nav>

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">سجل حركات المخزون</h2>
        <p class="text-sm text-gray-500">
            @if($variant)
                حركات المتغير: {{ $variant->product->name }} - {{ $variant->size }} / {{ $variant->color }}
            @else
                جميع حركات المخزون
            @endif
        </p>
    </div>

    {{-- Filter Badge --}}
    @if($variant)
        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🔍</span>
                <div>
                    <p class="text-sm font-medium text-gray-900">عرض سجل متغير محدد</p>
                    <p class="text-xs text-gray-500">
                        SKU: {{ $variant->sku }} — المخزون: {{ $variant->stock_quantity }}
                    </p>
                </div>
            </div>
            <a href="{{ route('merchant.inventory.logs') }}" class="btn-secondary btn-sm">
                عرض كل السجل
            </a>
        </div>
    @endif

    {{-- Logs Table --}}
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        @if($logs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">التاريخ</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المنتج</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">النوع</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الكمية</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">السبب</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">بواسطة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-xs text-gray-600 whitespace-nowrap">
                                    {{ $log->created_at->format('Y/m/d H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">
                                        {{ $log->variant->product->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $log->variant->size }} / {{ $log->variant->color }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($log->change_type === 'in')
                                        <span class="badge badge-success">
                                            ➕ إدخال
                                        </span>
                                    @elseif($log->change_type === 'out')
                                        <span class="badge badge-danger">
                                            ➖ إخراج
                                        </span>
                                    @else
                                        <span class="badge badge-gray">
                                            ⚙️ تعديل
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-bold text-gray-900">
                                    {{ $log->quantity }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    {{ $log->reason ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    {{ $log->createdBy?->full_name ?? 'النظام' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="text-5xl mb-4">📜</div>
                <h3 class="text-base font-medium text-gray-900 mb-2">لا توجد حركات</h3>
                <p class="text-sm text-gray-500">
                    @if($variant)
                        لم يتم تسجيل أي حركات لهذا المتغير
                    @else
                        لم يتم تسجيل أي حركات مخزون بعد
                    @endif
                </p>
                <a href="{{ route('merchant.inventory.index') }}" class="btn-primary btn-sm mt-4">
                    العودة للمخزون
                </a>
            </div>
        @endif
    </div>

@endsection