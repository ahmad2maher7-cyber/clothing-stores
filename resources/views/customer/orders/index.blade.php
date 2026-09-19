@extends('customer.layouts.app')

@section('title', 'طلباتي')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-1">📦 طلباتي</h1>
            <p class="text-gray-500">تتبع وإدارة جميع طلباتك</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <a href="{{ route('customer.orders.index') }}"
               class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition
                      {{ !request('status') ? 'ring-2 ring-indigo-500' : '' }}">
                <p class="text-xs text-gray-500 mb-1">الكل</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['all'] }}</p>
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'pending']) }}"
               class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition border-r-4 border-orange-500
                      {{ request('status') == 'pending' ? 'ring-2 ring-orange-500' : '' }}">
                <p class="text-xs text-gray-500 mb-1">⏳ قيد المعالجة</p>
                <p class="text-2xl font-bold text-orange-600">{{ $stats['pending'] }}</p>
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'delivering']) }}"
               class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition border-r-4 border-purple-500
                      {{ request('status') == 'delivering' ? 'ring-2 ring-purple-500' : '' }}">
                <p class="text-xs text-gray-500 mb-1">🚚 قيد التوصيل</p>
                <p class="text-2xl font-bold text-purple-600">{{ $stats['delivering'] }}</p>
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'delivered']) }}"
               class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition border-r-4 border-green-500
                      {{ request('status') == 'delivered' ? 'ring-2 ring-green-500' : '' }}">
                <p class="text-xs text-gray-500 mb-1">✅ تم التسليم</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['delivered'] }}</p>
            </a>
        </div>

        {{-- Search --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="🔍 ابحث برقم الطلب..."
                       class="flex-1 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 rounded-lg">
                    بحث
                </button>
            </form>
        </div>

        {{-- Orders --}}
        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    @php
                        $statusConfig = [
                            'pending' => ['⏳ قيد المراجعة', 'bg-yellow-100 text-yellow-800 border-yellow-300'],
                            'processing' => ['⚙️ جاري التجهيز', 'bg-blue-100 text-blue-800 border-blue-300'],
                            'shipped' => ['🚚 تم الشحن', 'bg-purple-100 text-purple-800 border-purple-300'],
                            'delivering' => ['📍 جاري التوصيل', 'bg-orange-100 text-orange-800 border-orange-300'],
                            'delivered' => ['✅ تم التسليم', 'bg-green-100 text-green-800 border-green-300'],
                            'cancelled' => ['❌ ملغى', 'bg-red-100 text-red-800 border-red-300'],
                            'returned' => ['↩️ مُرجع', 'bg-gray-100 text-gray-800 border-gray-300'],
                        ];
                        [$label, $classes] = $statusConfig[$order->status] ?? ['غير معروف', 'bg-gray-100'];
                    @endphp

                    <div class="bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden">
                        {{-- Header --}}
                        <div class="p-4 bg-gray-50 border-b flex flex-wrap justify-between items-center gap-3">
                            <div class="flex items-center gap-4">
                                <div>
                                    <p class="text-xs text-gray-500">رقم الطلب</p>
                                    <p class="font-mono font-bold text-indigo-600">{{ $order->order_number }}</p>
                                </div>
                                <div class="border-r pr-4">
                                    <p class="text-xs text-gray-500">التاريخ</p>
                                    <p class="text-sm font-medium">{{ $order->created_at->format('Y/m/d') }}</p>
                                </div>
                            </div>
                            <span class="{{ $classes }} border px-3 py-1 rounded-full text-xs font-medium">
                                {{ $label }}
                            </span>
                        </div>

                        {{-- Body --}}
                        <div class="p-4">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-2xl">
                                    🏪
                                </div>
                                <div>
                                    <p class="font-medium">{{ $order->store->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->items_count }} منتج</p>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-3 border-t">
                                <div>
                                    <p class="text-xs text-gray-500">الإجمالي</p>
                                    <p class="text-xl font-bold text-indigo-600">{{ number_format($order->total, 0) }} ₪</p>
                                </div>
                                <a href="{{ route('customer.orders.show', $order) }}"
                                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
                                    عرض التفاصيل →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $orders->links() }}</div>
        @else
            <div class="bg-white rounded-lg shadow text-center py-16">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد طلبات</h3>
                <p class="text-gray-500 mb-6">ابدأ التسوق لإنشاء أول طلب</p>
                <a href="{{ route('products.index') }}"
                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-bold">
                    🛍️ تسوق الآن
                </a>
            </div>
        @endif
    </div>

@endsection