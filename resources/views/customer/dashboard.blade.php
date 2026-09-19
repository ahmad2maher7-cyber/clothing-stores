@extends('customer.layouts.app')

@section('title', 'لوحة التحكم')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Welcome --}}
        <div class="bg-gradient-to-l from-indigo-600 to-purple-700 text-white rounded-xl p-6 md:p-8 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-white/20 flex items-center justify-center text-3xl md:text-4xl font-bold backdrop-blur">
                    {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold mb-1">مرحباً {{ auth()->user()->full_name }} 👋</h1>
                    <p class="text-indigo-100 text-sm md:text-base">
                        نتمنى لك تجربة تسوق ممتعة
                    </p>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-5 border-r-4 border-indigo-500">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-3xl">📦</span>
                </div>
                <p class="text-xs text-gray-500 mb-1">إجمالي الطلبات</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['orders'] }}</p>
            </div>

            <div class="bg-white rounded-xl shadow p-5 border-r-4 border-orange-500">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-3xl">⏳</span>
                </div>
                <p class="text-xs text-gray-500 mb-1">قيد المعالجة</p>
                <p class="text-3xl font-bold text-orange-600">{{ $stats['pending'] }}</p>
            </div>

            <div class="bg-white rounded-xl shadow p-5 border-r-4 border-green-500">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-3xl">✅</span>
                </div>
                <p class="text-xs text-gray-500 mb-1">تم التسليم</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['delivered'] }}</p>
            </div>

            <div class="bg-white rounded-xl shadow p-5 border-r-4 border-red-500">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-3xl">❤️</span>
                </div>
                <p class="text-xs text-gray-500 mb-1">المفضلة</p>
                <p class="text-3xl font-bold text-red-600">{{ $stats['wishlist'] }}</p>
            </div>
        </div>

        {{-- Total Spent --}}
        <div class="bg-gradient-to-l from-purple-600 to-indigo-700 text-white rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm mb-1">💰 إجمالي مشترياتك</p>
                    <p class="text-3xl md:text-4xl font-bold">{{ number_format($stats['total_spent'], 0) }} ₪</p>
                </div>
                <span class="text-6xl">💳</span>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="bg-white rounded-xl shadow mb-6">
            <div class="p-5 border-b flex justify-between items-center">
                <h2 class="font-bold text-lg">📦 آخر الطلبات</h2>
                <a href="{{ route('customer.orders.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm">
                    عرض الكل →
                </a>
            </div>

            @if($recentOrders->count() > 0)
                <div class="divide-y">
                    @foreach($recentOrders as $order)
                        <a href="{{ route('customer.orders.show', $order) }}"
                           class="flex items-center p-4 hover:bg-gray-50 transition">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-xl ml-4">
                                📦
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-mono font-medium text-gray-800">{{ $order->order_number }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $order->store->name }} • {{ $order->created_at->format('Y/m/d') }}
                                </p>
                            </div>
                            <div class="text-left">
                                <p class="font-bold text-indigo-600">{{ number_format($order->total, 0) }} ₪</p>
                                @php
                                    $statusConfig = [
                                        'pending' => ['⏳ قيد المراجعة', 'text-yellow-600'],
                                        'processing' => ['⚙️ جاري التجهيز', 'text-blue-600'],
                                        'shipped' => ['🚚 تم الشحن', 'text-purple-600'],
                                        'delivering' => ['📍 جاري التوصيل', 'text-orange-600'],
                                        'delivered' => ['✅ تم التسليم', 'text-green-600'],
                                        'cancelled' => ['❌ ملغى', 'text-red-600'],
                                    ];
                                    [$label, $color] = $statusConfig[$order->status] ?? ['غير معروف', 'text-gray-600'];
                                @endphp
                                <p class="text-xs {{ $color }}">{{ $label }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-5xl mb-3">📭</div>
                    <p class="text-gray-500 mb-4">لا توجد طلبات بعد</p>
                    <a href="{{ route('products.index') }}"
                       class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                        🛍️ ابدأ التسوق
                    </a>
                </div>
            @endif
        </div>

        {{-- Recent Wishlist --}}
        @if($recentWishlist->count() > 0)
            <div class="bg-white rounded-xl shadow">
                <div class="p-5 border-b flex justify-between items-center">
                    <h2 class="font-bold text-lg">❤️ من المفضلة</h2>
                    <a href="{{ route('customer.wishlist') }}" class="text-indigo-600 hover:text-indigo-700 text-sm">
                        عرض الكل →
                    </a>
                </div>

                <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($recentWishlist as $item)
                        @if($item->product)
                            @include('partials.product-card', ['product' => $item->product])
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

@endsection