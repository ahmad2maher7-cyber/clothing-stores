@extends('layouts.public')

@section('title', 'تم الطلب بنجاح')

@section('content')

    <div class="max-w-2xl mx-auto px-4 py-16">

        <div class="bg-white border border-gray-200 rounded-lg p-8 text-center">

            {{-- Icon --}}
            <div class="w-20 h-20 bg-green-50 border border-green-200 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                ✓
            </div>

            {{-- Title --}}
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
                تم استلام طلبك بنجاح!
            </h1>
            <p class="text-gray-600 mb-8">
                شكراً لك! سنتواصل معك قريباً لتأكيد الطلب
            </p>

            {{-- Order Info --}}
            <div class="bg-gray-50 rounded-lg p-6 text-right mb-8">
                <div class="grid grid-cols-2 gap-5 text-sm">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">رقم الطلب</p>
                        <p class="font-mono font-bold text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">حالة الطلب</p>
                        <p class="font-medium text-gray-700">⏳ قيد المراجعة</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">المتجر</p>
                        <p class="font-medium text-gray-700">{{ $order->store->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">طريقة الدفع</p>
                        <p class="font-medium text-gray-700">{{ $order->paymentMethod?->name }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 mb-1">عنوان التوصيل</p>
                        <p class="font-medium text-gray-700">{{ $order->shipping_address }}</p>
                    </div>
                    <div class="col-span-2 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">الإجمالي:</span>
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($order->total, 0) }} ₪</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('customer.orders.index') }}" class="btn-primary">
                    تتبع الطلب
                </a>
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    متابعة التسوق
                </a>
            </div>
        </div>
    </div>

@endsection