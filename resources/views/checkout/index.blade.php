@extends('layouts.public')

@section('title', 'إتمام الطلب')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8" x-data="checkoutForm()">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-gray-900">الرئيسية</a>
            <span class="mx-2 text-gray-300">/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-gray-900">السلة</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-900">إتمام الطلب</span>
        </nav>

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">إتمام الطلب</h1>
            <p class="text-sm text-gray-500">أكمل البيانات التالية لتأكيد طلبك</p>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ============ Main Form ============ --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Shipping --}}
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="p-5 border-b border-gray-200">
                            <h2 class="font-bold text-gray-900">📍 عنوان التوصيل</h2>
                        </div>
                        <div class="p-5 space-y-5">

                            <div>
                                <label class="form-label">رقم الهاتف *</label>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                                       placeholder="0599000000"
                                       class="form-input">
                                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="form-label">العنوان الكامل *</label>
                                <textarea name="shipping_address" rows="3" required
                                          placeholder="المدينة - الحي - الشارع - رقم المبنى"
                                          class="form-input">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="form-label">منطقة الشحن *</label>
                                <select name="shipping_zone_id" required x-model="shippingZoneId"
                                        @change="updateShipping()"
                                        class="form-input">
                                    <option value="">— اختر منطقة —</option>
                                    @foreach($shippingZones as $zone)
                                        <option value="{{ $zone->id }}" data-cost="{{ $zone->cost }}">
                                            {{ $zone->city }} - {{ $zone->cost }} ₪ ({{ $zone->estimated_days }} أيام)
                                        </option>
                                    @endforeach
                                </select>
                                @error('shipping_zone_id') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="form-label">ملاحظات (اختياري)</label>
                                <textarea name="notes" rows="2" maxlength="500"
                                          placeholder="أي تفاصيل إضافية..."
                                          class="form-input">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="p-5 border-b border-gray-200">
                            <h2 class="font-bold text-gray-900">💳 طريقة الدفع</h2>
                        </div>
                        <div class="p-5 space-y-3">
                            @foreach($paymentMethods as $method)
                                <label class="flex items-center gap-4 p-4 border border-gray-300 rounded-md cursor-pointer transition
                                              has-[:checked]:border-gray-900 has-[:checked]:bg-gray-50">
                                    <input type="radio" name="payment_method_id" value="{{ $method->id }}" required
                                           {{ $loop->first ? 'checked' : '' }}
                                           class="text-gray-900 focus:ring-gray-500">
                                    <span class="text-2xl">
                                        @switch($method->code)
                                            @case('cod') 💰 @break
                                            @case('card') 💳 @break
                                            @case('wallet') 📱 @break
                                            @case('installments') 📅 @break
                                        @endswitch
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm text-gray-900">{{ $method->name }}</p>
                                        @if($method->code === 'cod')
                                            <p class="text-xs text-gray-500">تدفع عند استلام الطلب</p>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                            @error('payment_method_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- ============ Order Summary ============ --}}
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-24">
                        <h3 class="font-bold text-gray-900 mb-5">ملخص الطلب</h3>

                        {{-- Items --}}
                        <div class="space-y-3 mb-5 pb-5 border-b border-gray-200 max-h-64 overflow-y-auto">
                            @foreach($cart->items as $item)
                                <div class="flex gap-3">
                                    <div class="w-12 h-12 bg-gray-50 border border-gray-200 rounded overflow-hidden shrink-0">
                                        @if($item->variant->product->primaryImage)
                                            <img src="{{ asset('storage/' . $item->variant->product->primaryImage->image_url) }}" 
                                                 class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-xs text-gray-900 line-clamp-1">
                                            {{ $item->variant->product->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $item->variant->size }} / {{ $item->variant->color }}</p>
                                        <p class="text-xs text-gray-500">× {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-xs font-bold text-gray-900 shrink-0">
                                        {{ number_format($item->price * $item->quantity, 0) }} ₪
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Coupon --}}
                        <div class="mb-5">
                            <label class="form-label text-xs">كود الخصم</label>
                            <div class="flex gap-2">
                                <input type="text" name="coupon_code" x-model="couponCode"
                                       placeholder="ادخل الكود"
                                       style="text-transform: uppercase"
                                       class="form-input text-sm flex-1">
                                <button type="button" @click="applyCoupon()"
                                        class="btn-secondary btn-sm">
                                    تطبيق
                                </button>
                            </div>
                            <p x-show="couponMessage" x-text="couponMessage"
                               :class="couponSuccess ? 'text-green-600' : 'text-red-600'"
                               class="text-xs mt-1"></p>
                        </div>

                        {{-- Summary --}}
                        <div class="space-y-2.5 pb-5 border-b border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">المجموع الفرعي:</span>
                                <span class="font-medium text-gray-900">{{ number_format($subtotal, 0) }} ₪</span>
                            </div>
                            <div class="flex justify-between text-sm" x-show="discount > 0">
                                <span class="text-gray-600">الخصم:</span>
                                <span class="text-green-600">- <span x-text="discount"></span> ₪</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">الشحن:</span>
                                <span class="font-medium text-gray-900" x-text="shippingCost + ' ₪'"></span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center my-5">
                            <span class="font-bold text-gray-900">الإجمالي:</span>
                            <span class="text-2xl font-bold text-gray-900">
                                <span x-text="total"></span> ₪
                            </span>
                        </div>

                        <button type="submit" class="btn-primary btn-lg w-full">
                            تأكيد الطلب ←
                        </button>

                        <p class="text-xs text-gray-400 text-center mt-4">
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