@extends('admin.layouts.app')

@section('title', 'الطلبات')
@section('page-title', 'إدارة الطلبات')

@section('content')

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">🛒 جميع الطلبات</h2>
        <p class="text-gray-500 text-sm">متابعة طلبات المنصة</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs text-gray-500 mb-1">إجمالي الطلبات</p>
            <p class="text-2xl font-bold">{{ $stats['all'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-r-4 border-yellow-500">
            <p class="text-xs text-gray-500 mb-1">⏳ قيد المراجعة</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-r-4 border-green-500">
            <p class="text-xs text-gray-500 mb-1">✅ تم التسليم</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['delivered'] }}</p>
        </div>
        <div class="bg-gradient-to-l from-green-600 to-emerald-700 text-white rounded-lg shadow p-4">
            <p class="text-xs mb-1 opacity-90">💰 الإيرادات</p>
            <p class="text-xl font-bold">{{ number_format($stats['revenue'], 0) }} ₪</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 رقم الطلب أو اسم الزبون..."
                   class="md:col-span-2 border-gray-300 rounded-lg focus:border-indigo-500">

            <select name="status" class="border-gray-300 rounded-lg focus:border-indigo-500">
                <option value="">كل الحالات</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ قيد المراجعة</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>⚙️ التجهيز</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>🚚 الشحن</option>
                <option value="delivering" {{ request('status') == 'delivering' ? 'selected' : '' }}>📍 التوصيل</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>✅ التسليم</option>
            </select>

            <div class="flex gap-2">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex-1">بحث</button>
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">إعادة</a>
            </div>
        </form>
    </div>

    {{-- Orders --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($orders->count() > 0)
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">رقم الطلب</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الزبون</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المتجر</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الإجمالي</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <span class="font-mono font-medium text-indigo-600">{{ $order->order_number }}</span>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('Y/m/d') }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $order->customer->full_name }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $order->store->name }}</td>
                            <td class="px-4 py-3 font-bold">{{ number_format($order->total, 0) }} ₪</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusConfig = [
                                        'pending' => ['⏳ قيد المراجعة', 'text-yellow-600'],
                                        'processing' => ['⚙️ التجهيز', 'text-blue-600'],
                                        'shipped' => ['🚚 الشحن', 'text-purple-600'],
                                        'delivering' => ['📍 التوصيل', 'text-orange-600'],
                                        'delivered' => ['✅ التسليم', 'text-green-600'],
                                        'cancelled' => ['❌ ملغى', 'text-red-600'],
                                    ];
                                    [$label, $color] = $statusConfig[$order->status] ?? ['—', 'text-gray-600'];
                                @endphp
                                <span class="text-xs {{ $color }}">{{ $label }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-indigo-600 hover:text-indigo-900 text-xs">عرض →</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-4 py-3 border-t bg-gray-50">{{ $orders->links() }}</div>
        @else
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📦</div>
                <p class="text-gray-500">لا توجد طلبات</p>
            </div>
        @endif
    </div>

@endsection