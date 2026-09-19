@extends('merchant.layouts.app')

@section('title', 'تفاصيل الطلب ' . $order->order_number)
@section('page-title', 'تفاصيل الطلب')

@section('content')

    {{-- Breadcrumb --}}
    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('merchant.dashboard') }}" class="hover:text-indigo-600">لوحة التحكم</a>
        <span class="mx-2">›</span>
        <a href="{{ route('merchant.orders.index') }}" class="hover:text-indigo-600">الطلبات</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800 font-mono">{{ $order->order_number }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Order Header --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 font-mono">{{ $order->order_number }}</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $order->created_at->format('Y/m/d - H:i') }}
                        </p>
                    </div>
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
                        [$statusLabel, $statusClass] = $statusConfig[$order->status] ?? ['غير معروف', ''];
                    @endphp
                    <span class="{{ $statusClass }} border px-4 py-2 rounded-lg text-sm font-medium">
                        {{ $statusLabel }}
                    </span>
                </div>

                {{-- Order Info --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">حالة الدفع</p>
                        <p class="font-medium">
                            @if($order->payment_status === 'paid')
                                <span class="text-green-600">✅ مدفوع</span>
                            @elseif($order->payment_status === 'refunded')
                                <span class="text-gray-600">↩️ مسترد</span>
                            @else
                                <span class="text-yellow-600">⏳ غير مدفوع</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">طريقة الدفع</p>
                        <p class="font-medium">{{ $order->paymentMethod?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">عدد المنتجات</p>
                        <p class="font-medium">{{ $order->items->count() }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">الإجمالي</p>
                        <p class="font-bold text-indigo-600 text-lg">{{ number_format($order->total, 2) }} ₪</p>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="font-bold text-gray-800">🛍️ المنتجات ({{ $order->items->count() }})</h3>
                </div>
                <div class="divide-y">
                    @foreach($order->items as $item)
                        <div class="p-4 flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-2xl">
                                👕
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $item->product_name }}</p>
                                <p class="text-sm text-gray-500">
                                    المقاس: <span class="bg-indigo-100 px-2 rounded">{{ $item->size }}</span>
                                    | اللون: {{ $item->color }}
                                </p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500">الكمية</p>
                                <p class="font-medium">{{ $item->quantity }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500">سعر الوحدة</p>
                                <p class="font-medium">{{ number_format($item->unit_price, 2) }} ₪</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">الإجمالي</p>
                                <p class="font-bold text-indigo-600">{{ number_format($item->total_price, 2) }} ₪</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Order Summary --}}
                <div class="p-6 bg-gray-50 border-t">
                    <div class="space-y-2 max-w-xs mr-auto">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">المجموع الفرعي:</span>
                            <span class="font-medium">{{ number_format($order->subtotal, 2) }} ₪</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-sm text-green-600">
                                <span>الخصم ({{ $order->coupon?->code }}):</span>
                                <span class="font-medium">- {{ number_format($order->discount, 2) }} ₪</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">الشحن:</span>
                            <span class="font-medium">{{ number_format($order->shipping_cost, 2) }} ₪</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t text-lg">
                            <span class="font-bold">الإجمالي:</span>
                            <span class="font-bold text-indigo-600">{{ number_format($order->total, 2) }} ₪</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Timeline --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-gray-800 mb-4">📜 سجل الحالات</h3>
                @if($order->statusHistory->count() > 0)
                    <div class="space-y-4">
                        @foreach($order->statusHistory->sortByDesc('created_at') as $history)
                            @php
                                $histConfig = $statusConfig[$history->status] ?? ['غير معروف', ''];
                            @endphp
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                                    @if(!$loop->last)
                                        <div class="w-0.5 h-full bg-gray-200 my-1"></div>
                                    @endif
                                </div>
                                <div class="flex-1 pb-4">
                                    <p class="font-medium text-gray-800">{{ $histConfig[0] }}</p>
                                    @if($history->note)
                                        <p class="text-sm text-gray-600 mt-1">{{ $history->note }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $history->created_at->format('Y/m/d - H:i') }}
                                        @if($history->changedBy)
                                            • بواسطة {{ $history->changedBy->full_name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">لا يوجد سجل حالات</p>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Update Status --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-gray-800 mb-4">🔄 تحديث الحالة</h3>

                @if(in_array($order->status, ['cancelled', 'returned']))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-red-700 text-sm">
                        ⚠️ لا يمكن تعديل حالة طلب {{ $order->status === 'cancelled' ? 'ملغى' : 'مُرجع' }}
                    </div>
                @else
                    <form action="{{ route('merchant.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <label class="block text-sm font-medium text-gray-700 mb-2">الحالة الجديدة</label>
                        <select name="status" required
                                class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 mb-3">
                            <option value="">— اختر الحالة —</option>
                            <option value="pending" {{ $order->status == 'pending' ? 'disabled' : '' }}>⏳ قيد المراجعة</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'disabled' : '' }}>⚙️ جاري التجهيز</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'disabled' : '' }}>🚚 تم الشحن</option>
                            <option value="delivering" {{ $order->status == 'delivering' ? 'disabled' : '' }}>📍 جاري التوصيل</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'disabled' : '' }}>✅ تم التسليم</option>
                            <option value="cancelled">❌ إلغاء الطلب</option>
                        </select>

                        <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظة (اختياري)</label>
                        <textarea name="note" rows="3" maxlength="500"
                                  placeholder="مثال: تم التواصل مع الزبون"
                                  class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 mb-3"></textarea>

                        <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-medium">
                            💾 تحديث الحالة
                        </button>
                    </form>
                @endif
            </div>

            {{-- Customer Info --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-gray-800 mb-4">👤 معلومات الزبون</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs">الاسم</p>
                        <p class="font-medium">{{ $order->customer->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">البريد</p>
                        <p class="font-medium break-all">{{ $order->customer->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">الهاتف</p>
                        <p class="font-medium">{{ $order->customer->phone ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-gray-800 mb-4">📍 عنوان التوصيل</h3>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $order->shipping_address }}</p>

                @if($order->shippingZone)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-xs text-gray-500 mb-1">منطقة الشحن</p>
                        <p class="font-medium">{{ $order->shippingZone->city }}, {{ $order->shippingZone->country }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            المدة التقديرية: {{ $order->shippingZone->estimated_days }} أيام
                        </p>
                    </div>
                @endif

                @if($order->notes)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-xs text-gray-500 mb-1">ملاحظات الزبون</p>
                        <p class="text-sm text-gray-700 italic">"{{ $order->notes }}"</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
