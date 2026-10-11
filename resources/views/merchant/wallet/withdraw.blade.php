@extends('merchant.layouts.app')

@section('title', 'طلب سحب')
@section('page-title', 'طلب سحب')

@section('content')

    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">
        <a href="{{ route('merchant.wallet.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            المحفظة
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">طلب سحب</span>
    </nav>

    <div class="max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="font-display text-2xl font-bold text-ink dark:text-cream mb-2">💸 طلب سحب جديد</h1>
            <p class="text-sm text-ink-muted dark:text-cream/60">اطلب تحويل أرباحك إلى حسابك البنكي أو محفظة PalPay</p>
        </div>

        {{-- Available Balance Card --}}
        <div class="bg-forest-900 dark:bg-forest-950 text-white rounded-lg p-6 mb-6">
            <p class="text-xs tracking-widest uppercase text-gold-400 mb-1">✅ الرصيد المتاح للسحب</p>
            <p class="font-display text-4xl font-bold">{{ number_format($availableBalance, 2) }} ₪</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('merchant.wallet.withdraw') }}" method="POST" class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden" x-data="{ paymentMethod: '{{ old('payment_method', 'bank_transfer') }}' }">
            @csrf

            <div class="p-6 space-y-5">

                {{-- Amount --}}
                <div>
                    <label class="form-label">المبلغ المطلوب <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="amount" value="{{ old('amount') }}"
                               min="50" max="{{ $availableBalance }}" step="0.01" required
                               placeholder="50.00"
                               class="form-input pl-12 text-lg font-bold">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-ink-muted dark:text-cream/50 font-bold">₪</span>
                    </div>
                    <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">
                        الحد الأدنى: 50 ₪ — المتاح: {{ number_format($availableBalance, 2) }} ₪
                    </p>
                    @error('amount') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Payment Method --}}
                <div>
                    <label class="form-label">طريقة السحب <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="bank_transfer" x-model="paymentMethod" class="sr-only">
                            <div class="border-2 rounded p-4 text-center transition-all"
                                 :class="paymentMethod === 'bank_transfer'
                                        ? 'border-forest-700 bg-forest-50 dark:border-gold-500 dark:bg-forest-950/40'
                                        : 'border-stone-200 dark:border-stone-800 hover:border-forest-500'">
                                <div class="text-3xl mb-2">🏦</div>
                                <p class="text-sm font-bold text-ink dark:text-cream">تحويل بنكي</p>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="palpay" x-model="paymentMethod" class="sr-only">
                            <div class="border-2 rounded p-4 text-center transition-all"
                                 :class="paymentMethod === 'palpay'
                                        ? 'border-forest-700 bg-forest-50 dark:border-gold-500 dark:bg-forest-950/40'
                                        : 'border-stone-200 dark:border-stone-800 hover:border-forest-500'">
                                <div class="text-3xl mb-2">📱</div>
                                <p class="text-sm font-bold text-ink dark:text-cream">PalPay</p>
                            </div>
                        </label>
                    </div>
                    @error('payment_method') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Account Name --}}
                <div>
                    <label class="form-label">اسم صاحب الحساب <span class="text-red-500">*</span></label>
                    <input type="text" name="account_name" value="{{ old('account_name', auth()->user()->full_name) }}"
                           required placeholder="أحمد محمد"
                           class="form-input">
                    @error('account_name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Account Number --}}
                <div>
                    <label class="form-label">
                        <span x-show="paymentMethod === 'bank_transfer'">رقم الحساب / IBAN <span class="text-red-500">*</span></span>
                        <span x-show="paymentMethod === 'palpay'">رقم محفظة PalPay <span class="text-red-500">*</span></span>
                    </label>
                    <input type="text" name="account_number" value="{{ old('account_number') }}"
                           required placeholder="PS00 XXXX 0000 0000 0000 0000 00"
                           dir="ltr"
                           class="form-input text-left">
                    @error('account_number') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Bank Name (only for bank_transfer) --}}
                <div x-show="paymentMethod === 'bank_transfer'" x-cloak>
                    <label class="form-label">اسم البنك <span class="text-red-500">*</span></label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                           placeholder="بنك فلسطين"
                           class="form-input">
                    @error('bank_name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Notes --}}
                <div>
                    <label class="form-label">ملاحظات (اختياري)</label>
                    <textarea name="notes" rows="3" maxlength="500"
                              placeholder="أي معلومات إضافية..."
                              class="form-input h-auto py-3 resize-none">{{ old('notes') }}</textarea>
                    @error('notes') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Warning --}}
                <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-lg p-4 flex items-start gap-3">
                    <span class="text-2xl shrink-0">⚠️</span>
                    <div class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                        <p class="font-semibold mb-1">تنبيه</p>
                        <ul class="space-y-1">
                            <li>• سيُخصم المبلغ من رصيدك فوراً ويصبح "معلقاً"</li>
                            <li>• ستتم مراجعة الطلب من قبل الإدارة</li>
                            <li>• مدة المعالجة: 1-3 أيام عمل</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="p-6 border-t border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-zinc-950 flex justify-end gap-3">
                <a href="{{ route('merchant.wallet.index') }}" class="btn-outline">إلغاء</a>
                <button type="submit" class="btn-solid">
                    💸 إرسال طلب السحب
                </button>
            </div>
        </form>
    </div>

@endsection