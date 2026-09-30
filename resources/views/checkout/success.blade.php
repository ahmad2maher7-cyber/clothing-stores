@extends('layouts.public')

@section('title', 'تم الطلب بنجاح')

@section('content')

    <div class="container-x py-16 lg:py-24">
        <div class="max-w-2xl mx-auto">

            {{-- ═══ Success Card ═══ --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">

                {{-- Header --}}
                <div class="bg-gradient-to-br from-forest-700 to-forest-900 dark:from-forest-800 dark:to-forest-950 p-10 text-center relative overflow-hidden">

                    {{-- Decorative --}}
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    {{-- Icon --}}
                    <div class="relative w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-white/10 backdrop-blur rounded-full">
                        <svg class="w-10 h-10 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <span class="relative inline-block text-[10px] font-semibold tracking-widest uppercase text-gold-400 mb-3">
                        — تم بنجاح
                    </span>
                    <h1 class="relative font-display text-3xl md:text-4xl font-bold text-white mb-3">
                        شكراً لك!
                    </h1>
                    <p class="relative text-sm text-cream/70 max-w-md mx-auto">
                        تم استلام طلبك بنجاح، سنتواصل معك قريباً لتأكيد التفاصيل
                    </p>
                </div>

                {{-- Order Details --}}
                <div class="p-6 lg:p-8">

                    {{-- Info Grid --}}
                    <div class="grid grid-cols-2 gap-5 pb-6 mb-6 border-b border-stone-200 dark:border-stone-800">

                        {{-- Order Number --}}
                        <div>
                            <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">رقم الطلب</p>
                            <p class="font-mono font-bold text-forest-700 dark:text-gold-400">
                                {{ $order->order_number }}
                            </p>
                        </div>

                        {{-- Status --}}
                        <div>
                            <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">حالة الطلب</p>
                            <span class="badge badge-stone">⏳ قيد المراجعة</span>
                        </div>

                        {{-- Store --}}
                        <div>
                            <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">المتجر</p>
                            <p class="font-semibold text-sm text-ink dark:text-cream">{{ $order->store->name }}</p>
                        </div>

                        {{-- Payment --}}
                        <div>
                            <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">طريقة الدفع</p>
                            <p class="font-semibold text-sm text-ink dark:text-cream">{{ $order->paymentMethod?->name ?? '—' }}</p>
                        </div>

                        {{-- Address --}}
                        <div class="col-span-2">
                            <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">عنوان التوصيل</p>
                            <p class="font-semibold text-sm text-ink dark:text-cream leading-relaxed">{{ $order->shipping_address }}</p>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="flex items-center justify-between mb-8 p-4 bg-stone-50 dark:bg-zinc-950 rounded border border-stone-200 dark:border-stone-800">
                        <span class="font-display font-bold text-ink dark:text-cream">الإجمالي المدفوع</span>
                        <span class="font-display text-2xl font-bold text-forest-700 dark:text-gold-400">
                            {{ number_format($order->total, 0) }} ₪
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('customer.orders.index') }}" class="btn-solid flex-1 justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            تتبع الطلب
                        </a>
                        <a href="{{ route('products.index') }}" class="btn-outline flex-1 justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            متابعة التسوق
                        </a>
                    </div>
                </div>
            </div>

            {{-- ═══ Info Banner ═══ --}}
            <div class="mt-6 p-5 bg-stone-50 dark:bg-zinc-900/50 border border-stone-200 dark:border-stone-800 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-forest-700 dark:text-gold-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-ink-soft dark:text-cream/70">
                    <p class="font-semibold text-ink dark:text-cream mb-1">ماذا بعد؟</p>
                    <p class="text-xs leading-relaxed">
                        سيتواصل معك فريق المتجر لتأكيد الطلب وموعد التوصيل. يمكنك تتبع حالة الطلب من صفحة "طلباتي".
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection