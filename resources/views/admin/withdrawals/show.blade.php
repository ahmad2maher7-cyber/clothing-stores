@extends('admin.layouts.app')

@section('title', 'طلب سحب #' . $withdrawal->id)
@section('page-title', 'تفاصيل طلب السحب')

@section('content')

    {{-- ═══ Breadcrumb ═══ --}}
    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
        <a href="{{ route('admin.withdrawals.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            طلبات السحب
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">#{{ $withdrawal->id }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ═══ Main Info ═══ --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Amount Card --}}
            <div class="bg-forest-900 dark:bg-forest-950 text-white rounded-lg p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gold-500/10 rounded-full blur-2xl pointer-events-none"></div>
                <p class="relative text-xs tracking-widest uppercase text-gold-400 mb-2">💰 المبلغ المطلوب</p>
                <p class="relative font-display text-4xl font-bold">{{ number_format($withdrawal->amount, 2) }} ₪</p>
                <div class="relative mt-4 flex items-center gap-2">
                    <span class="badge bg-white/10 text-white border-white/20">
                        {{ $withdrawal->status_label }}
                    </span>
                </div>
            </div>

            {{-- Store Info --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-5 border-b border-stone-200 dark:border-stone-800">
                    <h3 class="font-display font-bold text-ink dark:text-cream">🏪 معلومات المتجر</h3>
                </div>
                <div class="p-5 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-ink-muted dark:text-cream/50">المتجر:</span>
                        <span class="font-medium text-ink dark:text-cream">{{ $withdrawal->store->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-muted dark:text-cream/50">التاجر:</span>
                        <span class="font-medium text-ink dark:text-cream">{{ $withdrawal->store->merchant?->full_name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-muted dark:text-cream/50">البريد:</span>
                        <span class="font-medium text-ink dark:text-cream text-xs" dir="ltr">
                            {{ $withdrawal->store->merchant?->email ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Payment Details --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-5 border-b border-stone-200 dark:border-stone-800">
                    <h3 class="font-display font-bold text-ink dark:text-cream">🏦 تفاصيل الحساب</h3>
                </div>
                <div class="p-5 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-ink-muted dark:text-cream/50">طريقة السحب:</span>
                        <span class="font-medium text-ink dark:text-cream">{{ $withdrawal->payment_method_label }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-muted dark:text-cream/50">اسم صاحب الحساب:</span>
                        <span class="font-medium text-ink dark:text-cream">{{ $withdrawal->account_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-muted dark:text-cream/50">رقم الحساب:</span>
                        <span class="font-mono text-xs text-ink dark:text-cream" dir="ltr">
                            {{ $withdrawal->account_number }}
                        </span>
                    </div>
                    @if($withdrawal->bank_name)
                        <div class="flex justify-between">
                            <span class="text-ink-muted dark:text-cream/50">البنك:</span>
                            <span class="font-medium text-ink dark:text-cream">{{ $withdrawal->bank_name }}</span>
                        </div>
                    @endif
                    @if($withdrawal->notes)
                        <div class="pt-3 border-t border-stone-100 dark:border-stone-800">
                            <p class="text-xs text-ink-muted dark:text-cream/50 mb-1">ملاحظات التاجر:</p>
                            <p class="text-ink-soft dark:text-cream/70">{{ $withdrawal->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Admin Actions --}}
            @if($withdrawal->status === 'pending')
                <div class="bg-white dark:bg-zinc-900 border-2 border-amber-500 dark:border-amber-600 rounded-lg overflow-hidden">
                    <div class="p-5 border-b border-amber-200 dark:border-amber-900/50 bg-amber-50 dark:bg-amber-950/20">
                        <h3 class="font-display font-bold text-amber-800 dark:text-amber-300">⚡ إجراءات المشرف</h3>
                        <p class="text-xs text-amber-700 dark:text-amber-400 mt-1">
                            اختر ما تريد فعله بهذا الطلب
                        </p>
                    </div>

                    <div class="p-5 space-y-4">

                        {{-- Approve --}}
                        <form action="{{ route('admin.withdrawals.approve', $withdrawal) }}" method="POST" class="space-y-3">
                            @csrf @method('PUT')
                            <div>
                                <label class="form-label text-xs">ملاحظة داخلية (اختياري)</label>
                                <input type="text" name="admin_note" placeholder="مثال: جاهز للتحويل"
                                       class="form-input text-sm">
                            </div>
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 h-11 px-6 text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors">
                                ✅ الموافقة على الطلب
                            </button>
                        </form>

                        <div class="border-t border-stone-200 dark:border-stone-800"></div>

                        {{-- Mark as Paid --}}
                        <form action="{{ route('admin.withdrawals.pay', $withdrawal) }}" method="POST" class="space-y-3">
                            @csrf @PUT
                            <div>
                                <label class="form-label text-xs">
                                    تفاصيل التحويل <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="admin_note" required
                                       placeholder="رقم الحوالة / رقم التحويل البنكي"
                                       class="form-input text-sm">
                            </div>
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 h-11 px-6 text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded transition-colors">
                                💰 تأكيد تحويل المبلغ
                            </button>
                            <p class="text-xs text-ink-muted dark:text-cream/50 text-center">
                                ⚠️ استخدم هذا بعد إتمام التحويل الفعلي للتاجر
                            </p>
                        </form>

                        <div class="border-t border-stone-200 dark:border-stone-800"></div>

                        {{-- Reject --}}
                        <form action="{{ route('admin.withdrawals.reject', $withdrawal) }}" method="POST" class="space-y-3"
                              onsubmit="return confirm('هل أنت متأكد من رفض الطلب؟')">
                            @csrf @PUT
                            <div>
                                <label class="form-label text-xs">
                                    سبب الرفض <span class="text-red-500">*</span>
                                </label>
                                <textarea name="admin_note" rows="2" required maxlength="500"
                                          placeholder="اذكر السبب بوضوح..."
                                          class="form-input text-sm h-auto py-2 resize-none"></textarea>
                            </div>
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 h-11 px-6 text-sm font-semibold bg-white hover:bg-red-50 text-red-600 border border-red-300 hover:border-red-400 rounded transition-colors">
                                ❌ رفض الطلب
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- Processed Info --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                    <div class="p-5 border-b border-stone-200 dark:border-stone-800">
                        <h3 class="font-display font-bold text-ink dark:text-cream">📋 معالجة الطلب</h3>
                    </div>
                    <div class="p-5 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-ink-muted dark:text-cream/50">الحالة:</span>
                            <span class="font-medium text-ink dark:text-cream">{{ $withdrawal->status_label }}</span>
                        </div>
                        @if($withdrawal->processedBy)
                            <div class="flex justify-between">
                                <span class="text-ink-muted dark:text-cream/50">عولج بواسطة:</span>
                                <span class="font-medium text-ink dark:text-cream">{{ $withdrawal->processedBy->full_name }}</span>
                            </div>
                        @endif
                        @if($withdrawal->processed_at)
                            <div class="flex justify-between">
                                <span class="text-ink-muted dark:text-cream/50">تاريخ المعالجة:</span>
                                <span class="font-medium text-ink dark:text-cream">{{ $withdrawal->processed_at->format('Y/m/d H:i') }}</span>
                            </div>
                        @endif
                        @if($withdrawal->admin_note)
                            <div class="pt-3 border-t border-stone-100 dark:border-stone-800">
                                <p class="text-xs text-ink-muted dark:text-cream/50 mb-1">ملاحظة المشرف:</p>
                                <p class="text-ink-soft dark:text-cream/70">{{ $withdrawal->admin_note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- ═══ Sidebar ═══ --}}
        <div class="space-y-6">

            {{-- Wallet Info --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
                <h3 class="font-display font-bold text-ink dark:text-cream mb-4">💼 حالة المحفظة</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-ink-muted dark:text-cream/50">الرصيد الحالي:</span>
                        <span class="font-medium text-ink dark:text-cream">
                            {{ number_format($withdrawal->wallet->balance, 2) }} ₪
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-muted dark:text-cream/50">المعلق:</span>
                        <span class="font-medium text-amber-600 dark:text-amber-400">
                            {{ number_format($withdrawal->wallet->pending_withdrawal, 2) }} ₪
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-muted dark:text-cream/50">إجمالي الأرباح:</span>
                        <span class="font-medium text-emerald-600 dark:text-emerald-400">
                            {{ number_format($withdrawal->wallet->total_earned, 2) }} ₪
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink-muted dark:text-cream/50">إجمالي المسحوب:</span>
                        <span class="font-medium text-ink dark:text-cream">
                            {{ number_format($withdrawal->wallet->total_withdrawn, 2) }} ₪
                        </span>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
                <h3 class="font-display font-bold text-ink dark:text-cream mb-4">⏱️ الجدول الزمني</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 mt-1.5 shrink-0"></div>
                        <div>
                            <p class="text-xs font-medium text-ink dark:text-cream">تم إنشاء الطلب</p>
                            <p class="text-xs text-ink-muted dark:text-cream/50">
                                {{ $withdrawal->created_at->format('Y/m/d H:i') }}
                            </p>
                        </div>
                    </div>
                    @if($withdrawal->processed_at)
                        <div class="flex items-start gap-2">
                            <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 shrink-0"></div>
                            <div>
                                <p class="text-xs font-medium text-ink dark:text-cream">تمت المعالجة</p>
                                <p class="text-xs text-ink-muted dark:text-cream/50">
                                    {{ $withdrawal->processed_at->format('Y/m/d H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection