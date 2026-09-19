@extends('layouts.public')

@section('title', 'إتمام الطلب')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8" x-data="checkoutForm()">

        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">الرئيسية</a>
            <span class="mx-2">›</span>
            <a href="{{ route('cart.index') }}" class="hover:text-indigo-600">السلة</a>
            <span class="mx-2">›</span>
            <span class="text-gray-800">إتمام الطلب</span>
        </nav>

        <h1 class="text-3xl font-bold text-gray-800 mb-8">💳 إتمام الطلب</h1>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main Form --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- 1. Shipping Info --}}
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-5 border-b">
                            <h2 class="font-bold text-lg">📍 عنوان التوصيل</h2>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف *</label>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                                       placeholder="0599000000"
                                       class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">العنوان الكامل *</label>
                                <textarea name="shipping_address" rows="3" required
                                          placeholder="المدينة - الحي - الشارع - رقم المبنى"
                                          class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">منطقة الشحن *</label>
                                <select name="shipping_zone_id" required x-model="shippingZoneId"
                                        @change="updateShipping()"
                                        class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">— اختر منطقة —</option>
                                    @foreach($shippingZones as $zone)
                                        <option value="{{ $zone->id }}" data-cost="{{ $zone->cost }}">
                                            {{ $zone->city }} - {{ $zone->cost }} ₪ ({{ $zone->estimated_days }} أيام)
                                        </option>
                                    @endforeach
                                </select>
                                @error('shipping_zone_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات (اختياري)</label>
                                <textarea name="notes" rows="2" maxlength="500"
                                          placeholder="أي تفاصيل إضافية..."
                                          class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Payment Method --}}
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-5 border-b">
                            <h2 class="font-bold text-lg">💳 طريقة الدفع</h2>
                        </div>
                        <div class="p-5 space-y-3">
                            @foreach($paymentMethods as $method)
                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition
                                              has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="payment_method_id" value="{{ $method->id }}" required
                                           {{ $loop->first ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="mr-3 text-2xl">
                                        @switch($method->code)
                                            @case('cod') 💰 @break
                                            @case('card') 💳 @break
                                            @case('wallet') 📱 @break
                                            @case('installments') 📅 @break
                                        @endswitch
                                    </span>
                                    <div>
                                        <p class="font-medium">{{ $method->name }}</p>
                                        @if($method->code === 'cod')
                                            <p class="text-xs text-gray-500">تدفع عند استلام الطلب</p>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                            @error('payment_method_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                        <h3 class="font-bold text-lg mb-4">📋 ملخص الطلب</h3>

                        {{-- Items --}}
                        <div class="space-y-3 mb-4 pb-4 border-b max-h-64 overflow-y-auto">
                            @foreach($cart->items as $item)
                                <div class="flex gap-2 text-sm">
                                    <div class="w-12 h-12 bg-gray-100 rounded overflow-hidden shrink-0">
                                        @if($item->variant->product->primaryImage)
                                            <img src="{{ asset('storage/' . $item->variant->product->primaryImage->image_url) }}" 
                                                 class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium truncate">{{ $item->variant->product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->variant->size }} / {{ $item->variant->color }}</p>
                                        <p class="text-xs">× {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-indigo-600 font-medium">
                                        {{ number_format($item->price * $item->quantity, 0) }} ₪
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Coupon --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">كود الخصم</label>
                            <div class="flex gap-2">
                                <input type="text" name="coupon_code" x-model="couponCode"
                                       placeholder="ادخل الكود"
                                       style="text-transform: uppercase"
                                       class="flex-1 border-gray-300 rounded-lg text-sm focus:border-indigo-500">
                                <button type="button" @click="applyCoupon()"
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 rounded-lg text-sm">
                                    تطبيق
                                </button>
                            </div>
                            <p x-show="couponMessage" x-text="couponMessage"
                               :class="couponSuccess ? 'text-green-600' : 'text-red-500'"
                               class="text-xs mt-1"></p>
                        </div>

                        {{-- Summary --}}
                        <div class="space-y-2 pb-4 border-b">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">المجموع الفرعي:</span>
                                <span>{{ number_format($subtotal, 0) }} ₪</span>
                            </div>
                            <div class="flex justify-between text-sm" x-show="discount > 0">
                                <span class="text-gray-600">الخصم:</span>
                                <span class="text-green-600">- <span x-text="discount"></span> ₪</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">الشحن:</span>
                                <span x-text="shippingCost + ' ₪'"></span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-6 pt-4">
                            <span class="font-bold">الإجمالي:</span>
                            <span class="text-2xl font-bold text-indigo-600">
                                <span x-text="total"></span> ₪
                            </span>
                        </div>

                        <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-bold transition">
                            ✅ تأكيد الطلب
                        </button>

                        <p class="text-xs text-gray-500 text-center mt-3">
                            بالمتابعة أنت توافق على سياسة المتجر
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
function checkoutForm() {
    return {
        shippingZoneId: '',
        shippingCost: 0,
        discount: 0,
        couponCode: '',
        couponMessage: '',
        couponSuccess: false,
        subtotal: {{ $subtotal }},

        get total() {
            const t = this.subtotal - this.discount + parseFloat(this.shippingCost || 0);
            return Math.max(0, Math.round(t));
        },

        updateShipping() {
            const select = document.querySelector('select[name="shipping_zone_id"]');
            const option = select.options[select.selectedIndex];
            this.shippingCost = option.dataset.cost || 0;
        },

        applyCoupon() {
            if (!this.couponCode) {
                this.couponMessage = 'ادخل كود الخصم';
                this.couponSuccess = false;
                return;
            }

            fetch('{{ route('checkout.applyCoupon') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    code: this.couponCode,
                    subtotal: this.subtotal,
                }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    this.discount = data.discount;
                    this.couponMessage = data.message;
                    this.couponSuccess = true;
                } else {
                    this.discount = 0;
                    this.couponMessage = data.message;
                    this.couponSuccess = false;
                }
            })
            .catch(() => {
                this.couponMessage = 'حدث خطأ';
                this.couponSuccess = false;
            });
        }
    }
}
</script>
@endpush