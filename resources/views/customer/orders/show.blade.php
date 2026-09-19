@extends('customer.layouts.app')

@section('title', 'تفاصيل الطلب ' . $order->order_number)

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('customer.orders.index') }}" class="hover:text-indigo-600">طلباتي</a>
            <span class="mx-2">›</span>
            <span class="text-gray-800 font-mono">{{ $order->order_number }}</span>
        </nav>

        {{-- Status Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            @php
                $statusConfig = [
                    'pending' => ['⏳ قيد المراجعة', 'bg-yellow-50 border-yellow-300 text-yellow-800', 'سنتواصل معك لتأكيد الطلب'],
                    'processing' => ['⚙️ جاري التجهيز', 'bg-blue-50 border-blue-300 text-blue-800', 'يتم تجهيز طلبك الآن'],
                    'shipped' => ['🚚 تم الشحن', 'bg-purple-50 border-purple-300 text-purple-800', 'طلبك في الطريق إليك'],
                    'delivering' => ['📍 جاري التوصيل', 'bg-orange-50 border-orange-300 text-orange-800', 'سيتصل بك المندوب قريباً'],
                    'delivered' => ['✅ تم التسليم', 'bg-green-50 border-green-300 text-green-800', 'نتمنى أن تكون راضياً عن طلبك'],
                    'cancelled' => ['❌ ملغى', 'bg-red-50 border-red-300 text-red-800', 'تم إلغاء الطلب'],
                    'returned' => ['↩️ مُرجع', 'bg-gray-50 border-gray-300 text-gray-800', 'تم إرجاع الطلب'],
                ];
                [$label, $classes, $desc] = $statusConfig[$order->status] ?? ['غير معروف', '', ''];
            @endphp

            <div class="{{ $classes }} border-2 rounded-lg p-6 text-center">
                <div class="text-5xl mb-3">
                    @switch($order->status)
                        @case('pending') ⏳ @break
                        @case('processing') ⚙️ @break
                        @case('shipped') 🚚 @break
                        @case('delivering') 📍 @break
                        @case('delivered') ✅ @break
                        @case('cancelled') ❌ @break
                        @default 📦
                    @endswitch
                </div>
                <h2 class="text-2xl font-bold mb-2">{{ $label }}</h2>
                <p class="text-sm">{{ $desc }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Items --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-5 border-b">
                        <h3 class="font-bold text-lg">🛍️ المنتجات ({{ $order->items->count() }})</h3>
                    </div>
                    <div class="divide-y">
                        @foreach($order->items as $item)
                            <div class="p-4 flex items-center gap-4">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-3xl">
                                    👕
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium">{{ $item->product_name }}</p>
                                    <p class="text-xs text-gray-500">
                                        المقاس: <span class="bg-indigo-100 px-2 rounded">{{ $item->size }}</span>
                                        | اللون: {{ $item->color }}
                                    </p>
                                </div>
                                <div class="text-center text-sm">
                                    <p class="text-gray-500 text-xs">الكمية</p>
                                    <p class="font-medium">× {{ $item->quantity }}</p>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs text-gray-500">الإجمالي</p>
                                    <p class="font-bold text-indigo-600">{{ number_format($item->total_price, 0) }} ₪</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Timeline --}}
                @if($order->statusHistory->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-lg mb-4">📜 سجل الطلب</h3>

                        <div class="space-y-4">
                            @foreach($order->statusHistory->sortBy('created_at') as $history)
                                @php
                                    $histLabels = [
                                        'pending' => '⏳ قيد المراجعة',
                                        'processing' => '⚙️ جاري التجهيز',
                                        'shipped' => '🚚 تم الشحن',
                                        'delivering' => '📍 جاري التوصيل',
                                        'delivered' => '✅ تم التسليم',
                                        'cancelled' => '❌ ملغى',
                                        'returned' => '↩️ مُرجع',
                                    ];
                                @endphp

                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div class="w-3 h-3 rounded-full bg-indigo-500 mt-1"></div>
                                        @if(!$loop->last)
                                            <div class="w-0.5 h-full bg-gray-200 my-1"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 pb-4">
                                        <p class="font-medium">{{ $histLabels[$history->status] ?? $history->status }}</p>
                                        @if($history->note)
                                            <p class="text-sm text-gray-600 mt-1">{{ $history->note }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $history->created_at->format('Y/m/d - H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Order Info --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-gray-800 mb-4">📋 تفاصيل الطلب</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">رقم الطلب:</span>
                            <span class="font-mono font-medium">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">التاريخ:</span>
                            <span>{{ $order->created_at->format('Y/m/d') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">المتجر:</span>
                            <a href="{{ route('stores.show', $order->store) }}" class="text-indigo-600 hover:underline">
                                {{ $order->store->name }}
                            </a>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">طريقة الدفع:</span>
                            <span>{{ $order->paymentMethod?->name ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">حالة الدفع:</span>
                            @if($order->payment_status === 'paid')
                                <span class="text-green-600 font-medium">✅ مدفوع</span>
                            @else
                                <span class="text-yellow-600 font-medium">⏳ غير مدفوع</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t">
                        <p class="text-xs text-gray-500 mb-1">عنوان التوصيل</p>
                        <p class="text-sm">{{ $order->shipping_address }}</p>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-gray-800 mb-4">💰 ملخص الفاتورة</h3>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">المجموع الفرعي:</span>
                            <span>{{ number_format($order->subtotal, 2) }} ₪</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>الخصم:</span>
                                <span>- {{ number_format($order->discount, 2) }} ₪</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600">الشحن:</span>
                            <span>{{ number_format($order->shipping_cost, 2) }} ₪</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t text-lg">
                            <span class="font-bold">الإجمالي:</span>
                            <span class="font-bold text-indigo-600">{{ number_format($order->total, 0) }} ₪</span>
                        </div>
                    </div>
                </div>

                {{-- Cancel Order --}}
                @if($order->status === 'pending')
                    <form action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('هل أنت متأكد من إلغاء الطلب؟')"
                                class="w-full bg-red-50 hover:bg-red-100 text-red-600 border-2 border-red-200 py-3 rounded-lg font-medium transition">
                            ❌ إلغاء الطلب
                        </button>
                    </form>
                @endif

                {{-- Contact Store --}}
                <a href="{{ route('stores.show', $order->store) }}"
                   class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg">
                    💬 التواصل مع المتجر
                </a>
            </div>
        </div>
    </div>

@endsection