@extends('merchant.layouts.app')

@section('title', 'الطلبات')
@section('page-title', 'إدارة الطلبات')

@section('content')

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">الطلبات</h2>
        <p class="text-gray-500 text-sm">إدارة ومتابعة جميع طلبات متجرك</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3 mb-6">
        <a href="{{ route('merchant.orders.index') }}"
           class="bg-white rounded-lg shadow p-3 text-center hover:shadow-md transition {{ !request('status') ? 'ring-2 ring-indigo-500' : '' }}">
            <p class="text-xs text-gray-500 mb-1">الكل</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['all'] }}</p>
        </a>

        <a href="{{ route('merchant.orders.index', ['status' => 'pending']) }}"
           class="bg-white rounded-lg shadow p-3 text-center hover:shadow-md transition border-r-4 border-yellow-500 {{ request('status') == 'pending' ? 'ring-2 ring-yellow-500' : '' }}">
            <p class="text-xs text-gray-500 mb-1">⏳ قيد المراجعة</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </a>

        <a href="{{ route('merchant.orders.index', ['status' => 'processing']) }}"
           class="bg-white rounded-lg shadow p-3 text-center hover:shadow-md transition border-r-4 border-blue-500 {{ request('status') == 'processing' ? 'ring-2 ring-blue-500' : '' }}">
            <p class="text-xs text-gray-500 mb-1">⚙️ التجهيز</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['processing'] }}</p>
        </a>

        <a href="{{ route('merchant.orders.index', ['status' => 'shipped']) }}"
           class="bg-white rounded-lg shadow p-3 text-center hover:shadow-md transition border-r-4 border-purple-500 {{ request('status') == 'shipped' ? 'ring-2 ring-purple-500' : '' }}">
            <p class="text-xs text-gray-500 mb-1">🚚 تم الشحن</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['shipped'] }}</p>
        </a>

        <a href="{{ route('merchant.orders.index', ['status' => 'delivering']) }}"
           class="bg-white rounded-lg shadow p-3 text-center hover:shadow-md transition border-r-4 border-orange-500 {{ request('status') == 'delivering' ? 'ring-2 ring-orange-500' : '' }}">
            <p class="text-xs text-gray-500 mb-1">📍 التوصيل</p>
            <p class="text-2xl font-bold text-orange-600">{{ $stats['delivering'] }}</p>
        </a>

        <a href="{{ route('merchant.orders.index', ['status' => 'delivered']) }}"
           class="bg-white rounded-lg shadow p-3 text-center hover:shadow-md transition border-r-4 border-green-500 {{ request('status') == 'delivered' ? 'ring-2 ring-green-500' : '' }}">
            <p class="text-xs text-gray-500 mb-1">✅ تم التسليم</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['delivered'] }}</p>
        </a>

        <div class="bg-gradient-to-l from-indigo-600 to-indigo-800 text-white rounded-lg shadow p-3 text-center">
            <p class="text-xs mb-1">💰 الإيرادات</p>
            <p class="text-xl font-bold">{{ number_format($stats['total_revenue'], 0) }} ₪</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 رقم الطلب أو اسم الزبون..."
                   class="md:col-span-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">

            <select name="payment_status" class="border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">كل حالات الدفع</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>مسترد</option>
            </select>

            <div class="flex space-x-2 space-x-reverse">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex-1">
                    بحث
                </button>
                <a href="{{ route('merchant.orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                    إعادة
                </a>
            </div>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الطلب</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الزبون</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المنتجات</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجمالي</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الدفع</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <span class="font-mono font-medium text-indigo-600">{{ $order->order_number }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $order->customer->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->customer->phone }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                        {{ $order->items_count }} منتج
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-bold text-gray-800">{{ number_format($order->total, 2) }} ₪</td>
                                <td class="px-4 py-3">
                                    @if($order->payment_status === 'paid')
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">✅ مدفوع</span>
                                    @elseif($order->payment_status === 'refunded')
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">↩️ مسترد</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">⏳ غير مدفوع</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['⏳ قيد المراجعة', 'bg-yellow-100 text-yellow-700'],
                                            'processing' => ['⚙️ جاري التجهيز', 'bg-blue-100 text-blue-700'],
                                            'shipped' => ['🚚 تم الشحن', 'bg-purple-100 text-purple-700'],
                                            'delivering' => ['📍 جاري التوصيل', 'bg-orange-100 text-orange-700'],
                                            'delivered' => ['✅ تم التسليم', 'bg-green-100 text-green-700'],
                                            'cancelled' => ['❌ ملغى', 'bg-red-100 text-red-700'],
                                            'returned' => ['↩️ مُرجع', 'bg-gray-100 text-gray-700'],
                                        ];
                                        [$label, $classes] = $statusConfig[$order->status] ?? ['غير معروف', 'bg-gray-100'];
                                    @endphp
                                    <span class="{{ $classes }} px-2 py-1 rounded text-xs whitespace-nowrap">{{ $label }}</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                                    {{ $order->created_at->format('Y/m/d') }}<br>
                                    <span class="text-gray-400">{{ $order->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('merchant.orders.show', $order) }}"
                                       class="text-indigo-600 hover:text-indigo-900 text-sm">عرض →</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-4 py-3 border-t bg-gray-50">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد طلبات بعد</h3>
                <p class="text-gray-500">عندما يقوم الزبائن بالشراء، ستظهر طلباتهم هنا</p>
            </div>
        @endif
    </div>

@endsection