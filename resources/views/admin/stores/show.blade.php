@extends('admin.layouts.app')

@section('title', $store->name)
@section('page-title', 'تفاصيل المتجر')

@section('content')

    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('admin.stores.index') }}" class="hover:text-indigo-600">المتاجر</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800">{{ $store->name }}</span>
    </nav>

    {{-- Store Header --}}
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        @if($store->banner)
            <img src="{{ asset('storage/' . $store->banner) }}" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gradient-to-l from-indigo-400 to-purple-500"></div>
        @endif

        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="w-20 h-20 rounded-xl bg-white border-4 border-white shadow-lg flex items-center justify-center text-4xl -mt-12">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" class="w-full h-full object-cover rounded-xl">
                    @else
                        🏪
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold mb-1">{{ $store->name }}</h1>
                            <p class="text-sm text-gray-500 mb-2">{{ $store->description }}</p>
                            <div class="flex flex-wrap gap-3 text-sm">
                                <span>👤 {{ $store->merchant->full_name }}</span>
                                <span>📧 {{ $store->merchant->email }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            @if($store->status === 'pending')
                                <form action="{{ route('admin.stores.approve', $store) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                        ✅ اعتماد المتجر
                                    </button>
                                </form>
                            @elseif($store->status === 'active')
                                <form action="{{ route('admin.stores.suspend', $store) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">
                                        ⛔ إيقاف مؤقت
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t">
                <div class="text-center">
                    <p class="text-3xl font-bold text-indigo-600">{{ $store->products_count }}</p>
                    <p class="text-xs text-gray-500">منتج</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $store->orders_count }}</p>
                    <p class="text-xs text-gray-500">طلب</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-600">
                        {{ number_format($store->orders()->where('payment_status', 'paid')->sum('total'), 0) }}
                    </p>
                    <p class="text-xs text-gray-500">₪ إيرادات</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-yellow-600">{{ $store->reviews()->count() }}</p>
                    <p class="text-xs text-gray-500">تقييم</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Products --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-5 border-b">
            <h3 class="font-bold">👕 آخر المنتجات ({{ $store->products->count() }})</h3>
        </div>
        @if($store->products->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المنتج</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">السعر</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($store->products as $product)
                            <tr>
                                <td class="px-4 py-3">{{ $product->name }}</td>
                                <td class="px-4 py-3">{{ number_format($product->base_price, 0) }} ₪</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs {{ $product->status === 'active' ? 'text-green-600' : 'text-gray-500' }}">
                                        {{ $product->status === 'active' ? '✅ نشط' : '📝 مسودة' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-lg shadow">
        <div class="p-5 border-b">
            <h3 class="font-bold">📦 آخر الطلبات ({{ $store->orders->count() }})</h3>
        </div>
        @if($store->orders->count() > 0)
            <div class="divide-y">
                @foreach($store->orders as $order)
                    <a href="{{ route('admin.orders.show', $order) }}"
                       class="p-4 flex justify-between items-center hover:bg-gray-50">
                        <div>
                            <p class="font-mono font-medium text-indigo-600">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('Y/m/d') }}</p>
                        </div>
                        <p class="font-bold">{{ number_format($order->total, 0) }} ₪</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

@endsection
