@extends('merchant.layouts.app')

@section('title', 'إضافة كوبون جديد')
@section('page-title', 'إضافة كوبون جديد')

@section('content')

    {{-- ═══ Breadcrumb ═══ --}}
    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
        <a href="{{ route('merchant.dashboard') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            لوحة التحكم
        </a>
        <span class="opacity-40">/</span>
        <a href="{{ route('merchant.coupons.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            الكوبونات
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">إضافة جديد</span>
    </nav>

    <div class="max-w-3xl mx-auto" x-data="couponForm()">

        <form action="{{ route('merchant.coupons.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- ═══ Basic Info ═══ --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-display text-lg font-bold text-ink dark:text-cream">معلومات الكوبون</h3>
                        <p class="text-xs text-ink-muted dark:text-cream/60">أنشئ كود خصم لجذب المزيد من الزبائن</p>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Code --}}
                    <div class="md:col-span-2">
                        <label class="form-label">
                            كود الكوبون <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="text" name="code" x-model="code" required
                                   @input="code = code.toUpperCase().replace(/[^A-Z0-9-]/g, '')"
                                   placeholder="مثال: WELCOME10"
                                   maxlength="50"
                                   style="text-transform: uppercase"
                                   class="form-input flex-1 font-mono text-lg tracking-wider">
                            <button type="button" @click="generateCode()"
                                    class="inline-flex items-center gap-1.5 h-12 px-4 bg-stone-100 dark:bg-zinc-800 hover:bg-stone-200 dark:hover:bg-zinc-700 text-ink dark:text-cream text-xs font-semibold tracking-widest uppercase rounded transition-colors whitespace-nowrap">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                توليد
                            </button>
                        </div>
                        <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">أحرف إنجليزية كبيرة وأرقام فقط</p>
                        @error('code') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Type --}}
                    <div class="md:col-span-2">
                        <label class="form-label">
                            نوع الخصم <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="percentage" x-model="type" class="peer sr-only" checked>
                                <div class="border-2 border-stone-300 dark:border-stone-700 rounded-lg p-4 text-center peer-checked:border-forest-700 dark:peer-checked:border-gold-500 peer-checked:bg-forest-50 dark:peer-checked:bg-forest-950/40 transition">
                                    <div class="text-3xl font-display font-bold text-ink dark:text-cream mb-1">%</div>
                                    <div class="font-medium text-sm text-ink dark:text-cream">نسبة مئوية</div>
                                    <div class="text-xs text-ink-muted dark:text-cream/50 mt-1">مثال: خصم 20%</div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="fixed" x-model="type" class="peer sr-only">
                                <div class="border-2 border-stone-300 dark:border-stone-700 rounded-lg p-4 text-center peer-checked:border-forest-700 dark:peer-checked:border-gold-500 peer-checked:bg-forest-50 dark:peer-checked:bg-forest-950/40 transition">
                                    <div class="text-3xl font-display font-bold text-ink dark:text-cream mb-1">₪</div>
                                    <div class="font-medium text-sm text-ink dark:text-cream">مبلغ ثابت</div>
                                    <div class="text-xs text-ink-muted dark:text-cream/50 mt-1">مثال: خصم 50 ₪</div>
                                </div>
                            </label>
                        </div>
                        @error('type') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Value --}}
                    <div>
                        <label class="form-label">
                            قيمة الخصم <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" name="value" x-model="value" required min="0.01"
                                   :max="type === 'percentage' ? 100 : null"
                                   class="form-input pl-12">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-ink-muted dark:text-cream/60 font-bold"
                                  x-text="type === 'percentage' ? '%' : '₪'"></span>
                        </div>
                        <p class="text-xs text-ink-muted dark:text-cream/40 mt-2" x-show="type === 'percentage'">
                            النسبة من 1 إلى 100
                        </p>
                        @error('value') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Min Order Amount --}}
                    <div>
                        <label class="form-label">الحد الأدنى للطلب (₪)</label>
                        <input type="number" step="0.01" name="min_order_amount" min="0"
                               placeholder="اتركها فارغة لعدم التحديد"
                               class="form-input">
                        <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">الحد الأدنى لقيمة السلة</p>
                    </div>

                    {{-- Max Discount (percentage only) --}}
                    <div x-show="type === 'percentage'">
                        <label class="form-label">أقصى مبلغ خصم (₪)</label>
                        <input type="number" step="0.01" name="max_discount" min="0"
                               placeholder="اتركها فارغة بدون حد"
                               class="form-input">
                        <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">لن يتجاوز هذا المبلغ</p>
                    </div>

                    {{-- Usage Limit --}}
                    <div>
                        <label class="form-label">حد الاستخدام</label>
                        <input type="number" name="usage_limit" min="1"
                               placeholder="اتركها فارغة لاستخدام غير محدود"
                               class="form-input">
                        <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">عدد مرات الاستخدام الإجمالي</p>
                    </div>
                </div>
            </div>

            {{-- ═══ Date Range ═══ --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-display text-lg font-bold text-ink dark:text-cream">فترة صلاحية الكوبون</h3>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">
                            تاريخ البداية <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required
                               class="form-input">
                        @error('start_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">
                            تاريخ الانتهاء <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date"
                               value="{{ old('end_date', date('Y-m-d', strtotime('+30 days'))) }}" required
                               class="form-input">
                        @error('end_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Quick Durations --}}
                    <div class="md:col-span-2">
                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-3">
                            اختصارات سريعة
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="setDuration(7)"
                                    class="bg-stone-100 dark:bg-zinc-800 hover:bg-forest-50 dark:hover:bg-forest-950/40 hover:text-forest-700 dark:hover:text-gold-400 text-ink-soft dark:text-cream/70 text-xs font-medium px-3 py-2 rounded transition-colors">
                                📅 أسبوع
                            </button>
                            <button type="button" @click="setDuration(14)"
                                    class="bg-stone-100 dark:bg-zinc-800 hover:bg-forest-50 dark:hover:bg-forest-950/40 hover:text-forest-700 dark:hover:text-gold-400 text-ink-soft dark:text-cream/70 text-xs font-medium px-3 py-2 rounded transition-colors">
                                📅 أسبوعان
                            </button>
                            <button type="button" @click="setDuration(30)"
                                    class="bg-stone-100 dark:bg-zinc-800 hover:bg-forest-50 dark:hover:bg-forest-950/40 hover:text-forest-700 dark:hover:text-gold-400 text-ink-soft dark:text-cream/70 text-xs font-medium px-3 py-2 rounded transition-colors">
                                📅 شهر
                            </button>
                            <button type="button" @click="setDuration(90)"
                                    class="bg-stone-100 dark:bg-zinc-800 hover:bg-forest-50 dark:hover:bg-forest-950/40 hover:text-forest-700 dark:hover:text-gold-400 text-ink-soft dark:text-cream/70 text-xs font-medium px-3 py-2 rounded transition-colors">
                                📅 3 أشهر
                            </button>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2">
                        <label class="form-label">
                            حالة الكوبون <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="active" class="peer sr-only" checked>
                                <div class="border-2 border-stone-300 dark:border-stone-700 rounded-lg p-4 text-center peer-checked:border-forest-700 dark:peer-checked:border-gold-500 peer-checked:bg-forest-50 dark:peer-checked:bg-forest-950/40 transition">
                                    <div class="text-2xl mb-1">✅</div>
                                    <div class="font-medium text-sm text-ink dark:text-cream">نشط</div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="expired" class="peer sr-only">
                                <div class="border-2 border-stone-300 dark:border-stone-700 rounded-lg p-4 text-center peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-950/20 transition">
                                    <div class="text-2xl mb-1">⛔</div>
                                    <div class="font-medium text-sm text-ink dark:text-cream">معطل</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ Live Preview ═══ --}}
            <div class="bg-forest-900 dark:bg-forest-950 rounded-lg p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative">
                    <h3 class="font-display text-lg font-bold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        معاينة الكوبون
                    </h3>

                    <div class="bg-white/10 backdrop-blur rounded-lg p-5 border-2 border-dashed border-white/30">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs tracking-widest uppercase text-gold-400 mb-2">كود الخصم</p>
                                <p class="font-mono text-2xl font-bold tracking-widest" x-text="code || 'YOURCODE'"></p>
                            </div>
                            <div class="text-left">
                                <p class="text-xs tracking-widest uppercase text-gold-400 mb-2">الخصم</p>
                                <p class="font-display text-4xl font-bold">
                                    <span x-text="value || 0"></span><span x-text="type === 'percentage' ? '%' : ' ₪'"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ Actions ═══ --}}
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                <a href="{{ route('merchant.coupons.index') }}" class="btn-outline justify-center">
                    إلغاء
                </a>
                <button type="submit" class="btn-solid justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    إنشاء الكوبون
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
function couponForm() {
    return {
        code: '',
        type: 'percentage',
        value: '',

        generateCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < 8; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.code = result;
        },

        setDuration(days) {
            const start = new Date();
            const end = new Date();
            end.setDate(end.getDate() + days);

            const formatDate = (date) => date.toISOString().split('T')[0];

            document.querySelector('input[name="start_date"]').value = formatDate(start);
            document.querySelector('input[name="end_date"]').value = formatDate(end);
        }
    }
}
</script>
@endpush