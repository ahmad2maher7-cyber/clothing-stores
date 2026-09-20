@extends('admin.layouts.app')

@section('title', $order->order_number)
@section('page-title', 'تفاصيل الطلب')

@section('content')

    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('admin.orders.index') }}" class="hover:text-indigo-600">الطلبات</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800 font-mono">{{ $order->order_number }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            {{-- Order Info --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-2xl font-bold font-mono">{{ $order->order_number }}</h2>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                    <span class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg">
                        {{ $order->status }}
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t text-sm">
                    <div>
                        <p class="text-gray-500 text-xs mb-1">الزبون</p>
                        <p class="font-medium">{{ $order->customer->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs mb-1">المتجر</p>
                        <p class="font-medium">{{ $order->store->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs mb-1">الدفع</p>
                        <p class="font-medium">{{ $order->paymentMethod?->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs mb-1">حالة الدفع</p>
                        <p class="font-medium">
                            {{ $order->payment_status === 'paid' ? '✅ مدفوع' : '⏳ غير مدفوع' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-5 border-b">
                    <h3 class="font-bold">🛍️ المنتجات ({{ $order->items->count() }})</h3>
                </div>
                <div class="divide-y">
                    @foreach($order->items as $item)
                        <div class="p-4 flex justify-between">
                            <div>
                                <p class="font-medium">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $item->size }} / {{ $item->color }} × {{ $item->quantity }}
                                </p>
                            </div>
                            <p class="font-bold">{{ number_format($item->total_price, 0) }} ₪</p>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 bg-gray-50 border-t">
                    <div class="flex justify-between text-sm mb-1">
                        <span>المجموع الفرعي</span>
                        <span>{{ number_format($order->subtotal, 0) }} ₪</span>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>الشحن</span>
                        <span>{{ number_format($order->shipping_cost, 0) }} ₪</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-sm text-green-600 mb-1">
                            <span>خصم</span>
                            <span>- {{ number_format($order->discount, 0) }} ₪</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-lg pt-2 border-t">
                        <span>الإجمالي</span>
                        <span class="text-indigo-600">{{ number_format($order->total, 0) }} ₪</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Customer --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold mb-4">👤 الزبون</h3>
                <div class="space-y-2 text-sm">
                    <p class="font-medium">{{ $order->customer->full_name }}</p>
                    <p class="text-gray-500">{{ $order->customer->email }}</p>
                    @if($order->customer->phone)
                        <p class="text-gray-500">{{ $order->customer->phone }}</p>
                    @endif
                    <a href="{{ route('admin.users.show', $order->customer) }}"
                       class="inline-block mt-3 text-indigo-600 text-sm">عرض الملف →</a>
                </div>
            </div>

            {{-- Shipping --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold mb-4">📍 عنوان التوصيل</h3>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $order->shipping_address }}</p>

                @if($order->shippingZone)
                    <div class="mt-3 pt-3 border-t text-xs text-gray-500">
                        {{ $order->shippingZone->city }} • {{ $order->shippingZone->estimated_days }} أيام
                    </div>
                @endif
            </div>

            {{-- Store --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold mb-4">🏪 المتجر</h3>
                <p class="font-medium">{{ $order->store->name }}</p>
                <a href="{{ route('admin.stores.show', $order->store) }}"
                   class="inline-block mt-2 text-indigo-600 text-sm">عرض المتجر →</a>
            </div>
        </div>
    </div>

@endsection
