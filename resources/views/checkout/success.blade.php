@extends('layouts.public')

@section('title', 'تم الطلب بنجاح')

@section('content')

    <div class="max-w-3xl mx-auto px-4 py-16">

        <div class="bg-white rounded-lg shadow-lg p-8 text-center">

            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center text-5xl mx-auto mb-6">
                ✅
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-3">تم استلام طلبك بنجاح!</h1>
            <p class="text-gray-600 mb-8">
                شكراً لك! سنتواصل معك قريباً لتأكيد الطلب
            </p>

            {{-- Order Info --}}
            <div class="bg-gray-50 rounded-lg p-6 text-right mb-8">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">رقم الطلب</p>
                        <p class="font-mono font-bold text-indigo-600">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">حالة الطلب</p>
                        <p class="font-medium">⏳ قيد المراجعة</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">المتجر</p>
                        <p class="font-medium">{{ $order->store->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">طريقة الدفع</p>
                        <p class="font-medium">{{ $order->paymentMethod?->name }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500 mb-1">عنوان التوصيل</p>
                        <p class="font-medium">{{ $order->shipping_address }}</p>
                    </div>
                    <div class="col-span-2 pt-3 border-t">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">الإجمالي:</span>
                            <span class="text-2xl font-bold text-indigo-600">{{ number_format($order->total, 0) }} ₪</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('customer.orders.index') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium">
                    📦 تتبع الطلب
                </a>
                <a href="{{ route('products.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium">
                    🛍️ متابعة التسوق
                </a>
            </div>
        </div>
    </div>

@endsection