@extends('layouts.public')

@section('title', 'إتمام الطلب')

@section('content')

    {{-- ═══════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════ --}}
    <div class="bg-stone-50 dark:bg-zinc-900/50 border-b border-stone-200 dark:border-stone-800">
        <div class="container-x py-10 lg:py-12">
            <nav class="flex items-center gap-2 text-xs font-medium tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-6">
                <a href="{{ route('home') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">الرئيسية</a>
                <span class="opacity-40">/</span>
                <a href="{{ route('cart.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">السلة</a>
                <span class="opacity-40">/</span>
                <span class="text-ink dark:text-cream">إتمام الطلب</span>
            </nav>

            <span class="eyebrow block mb-3">— الخطوة الأخيرة</span>
            <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-ink dark:text-cream tracking-tight mb-3">
                إتمام الطلب
            </h1>
            <p class="text-sm text-ink-muted dark:text-cream/60">
                أكمل البيانات التالية لتأكيد طلبك
            </p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         CHECKOUT FORM
    ═══════════════════════════════════════ --}}
    <div class="container-x py-10 lg:py-14" x-data="checkoutForm()">
        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="grid lg:grid-cols-12 gap-8">

                {{-- ═══════ MAIN FORM ═══════ --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- ─── SHIPPING ─── --}}
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                            <div class="w-8 h-8 flex items-center justify-center bg-forest-700 dark:bg-gold-500 text-white dark:text-ink rounded text-sm font-bold">1</div>
                            <h2 class="font-display font-bold text-ink dark:text-cream">عنوان التوصيل</h2>
                        </div>

                        <div class="p-6 space-y-5">

                            {{-- Phone --}}
                            <div>
                                <label class="form-label">رقم الهاتف *</label>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                                       placeholder="0599000000"
                                       class="form-input">
                                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Address --}}
                            <div>
                                <label class="form-label">العنوان الكامل *</label>
                                <textarea name="shipping_address" rows="3" required maxlength="500"
                                          placeholder="المدينة - الحي - الشارع - رقم المبنى"
                                          class="form-input h-auto py-3 resize-none">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Shipping Zone --}}
                            <div>
                                <label class="form-label">منطقة الشحن *</label>
                                <select name="shipping_zone_id" required x-model="shippingZoneId"
                                        @change="updateShipping()"
                                        class="form-input">
                                    <option value="">— اختر منطقة الشحن —</option>
                                    @foreach($shippingZones as $zone)
                                        <option value="{{ $zone->id }}" data-cost="{{ $zone->cost }}">
                                            {{ $zone->city }} — {{ $zone->cost }} ₪ ({{ $zone->estimated_days }} أيام)
                                        </option>
                                    @endforeach
                                </select>
                                @error('shipping_zone_id') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label class="form-label">ملاحظات إضافية (اختياري)</label>
                                <textarea name="notes" rows="2" maxlength="500"
                                          placeholder="أي تفاصيل إضافية للتوصيل..."
                                          class="form-input h-auto py-3 resize-none">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ─── PAYMENT ─── --}}
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                            <div class="w-8 h-8 flex items-center justify-center bg-forest-700 dark:bg-gold-500 text-white dark:text-ink rounded text-sm font-bold">2</div>
                            <h2 class="font-display font-bold text-ink dark:text-cream">طريقة الدفع</h2>
                        </div>

                        <div class="p-6 space-y-3">
                            @foreach($paymentMethods as $method)
                                <label class="flex items-center gap-4 p-4 border-2 border-stone-200 dark:border-stone-800 rounded cursor-pointer transition-all
                                              has-[:checked]:border-forest-700 has-[:checked]:bg-forest-50 dark:has-[:checked]:border-gold-500 dark:has-[:checked]:bg-forest-950/30 hover:border-forest-500 dark:hover:border-gold-400">
                                    <input type="radio" name="payment_method_id" value="{{ $method->id }}" required
                                           {{ $loop->first ? 'checked' : '' }}
                                           class="w-4 h-4 text-forest-700 focus:ring-forest-500">
                                    <div class="w-10 h-10 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded shrink-0">
                                        @switch($method->code)
                                            @case('cod')
                                                <svg class="w-5 h-5 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                @break
                                            @case('card')
                                                <svg class="w-5 h-5 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                </svg>
                                                @break
                                            @default
                                                <svg class="w-5 h-5 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                        @endswitch
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-sm text-ink dark:text-cream">{{ $method->name }}</p>
                                        @if($method->code === 'cod')
                                            <p class="text-xs text-ink-muted dark:text-cream/50 mt-0.5">تدفع عند استلام الطلب</p>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                            @error('payment_method_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- ═══════ ORDER SUMMARY ═══════ --}}
                <div class="lg:col-span-4">
                    <div class="sticky top-24 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">

                        {{-- Header --}}
                        <div class="px-6 py-4 border-b border-stone-200 dark:border-stone-800">
                            <span class="eyebrow">— ملخص الطلب</span>
                        </div>

                        {{-- Items --}}
                        <div class="p-6 pb-4 max-h-72 overflow-y-auto space-y-4 border-b border-stone-200 dark:border-stone-800">
                            @foreach($cart->items as $item)
                                <div class="flex gap-3">
                                    <div class="w-14 h-14 bg-stone-100 dark:bg-zinc-800 rounded overflow-hidden shrink-0">
                                        @if($item->variant->product->primaryImage)
                                            <img src="{{ asset('storage/' . $item->variant->product->primaryImage->image_url) }}" 
                                                 class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-ink dark:text-cream line-clamp-1 mb-1">
                                            {{ $item->variant->product->name }}
                                        </p>
                                        <p class="text-[10px] text-ink-muted dark:text-cream/50 mb-1">
                                            {{ $item->variant->size }} / {{ $item->variant->color }}
                                        </p>
                                        <p class="text-[10px] text-ink-muted dark:text-cream/50">
                                            × {{ $item->quantity }}
                                        </p>
                                    </div>
                                    <div class="text-xs font-bold text-ink dark:text-cream shrink-0">
                                        {{ number_format($item->price * $item->quantity, 0) }} ₪
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Coupon --}}
                        <div class="p-6 pb-4 border-b border-stone-200 dark:border-stone-800">
                            <label class="form-label">كود الخصم</label>
                            <div class="flex gap-2">
                                <input type="text" name="coupon_code" x-model="couponCode"
                                       placeholder="أدخل الكود"
                                       class="form-input flex-1 uppercase">
                                <button type="button" @click="applyCoupon()"
                                        class="inline-flex items-center justify-center h-12 px-4 bg-stone-100 dark:bg-zinc-800 hover:bg-stone-200 dark:hover:bg-zinc-700 text-ink dark:text-cream text-xs font-semibold tracking-widest uppercase rounded transition-colors">
                                    تطبيق
                                </button>
                            </div>
                            <p x-show="couponMessage" x-text="couponMessage"
                               :class="couponSuccess ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                               class="text-xs mt-2"></p>
                        </div>

                        {{-- Summary --}}
                        <div class="p-6 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-ink-muted dark:text-cream/60">المجموع الفرعي</span>
                                <span class="font-semibold text-ink dark:text-cream">{{ number_format($subtotal, 0) }} ₪</span>
                            </div>

                            <div class="flex justify-between text-sm" x-show="discount > 0">
                                <span class="text-ink-muted dark:text-cream/60">الخصم</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">− <span x-text="discount"></span> ₪</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-ink-muted dark:text-cream/60">الشحن</span>
                                <span class="font-semibold text-ink dark:text-cream" x-text="shippingCost + ' ₪'"></span>
                            </div>

                            <div class="pt-4 border-t border-stone-200 dark:border-stone-800 flex justify-between items-baseline">
                                <span class="font-display font-bold text-ink dark:text-cream">الإجمالي</span>
                                <span class="font-display text-2xl font-bold text-forest-700 dark:text-gold-400">
                                    <span x-text="total"></span> ₪
                                </span>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="p-6 pt-0">
                            <button type="submit" class="btn-solid w-full">
                                تأكيد الطلب
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </button>

                            <p class="text-[10px] text-ink-muted dark:text-cream/40 text-center mt-4">
                                بالمتابعة أنت توافق على سياسة المتجر
                            </p>
                        </div>
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
            return Math.max(0, Math.round(t)).toLocaleString('en');
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