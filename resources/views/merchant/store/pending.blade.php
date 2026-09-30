@extends('merchant.layouts.app')

@section('title', 'قيد المراجعة')
@section('page-title', 'حالة المتجر')

@section('content')

    <div class="max-w-3xl mx-auto">

        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">

            @if($store->banner)
                <img src="{{ asset('storage/' . $store->banner) }}"
                     alt="{{ $store->name }}"
                     class="w-full h-40 object-cover">
            @else
                <div class="w-full h-40 bg-gradient-to-br from-forest-700 to-gold-500"></div>
            @endif

            <div class="p-8 text-center">

                <div class="w-24 h-24 mx-auto -mt-20 mb-4 rounded-full bg-white dark:bg-zinc-900 shadow-lg flex items-center justify-center border-4 border-white dark:border-zinc-900 overflow-hidden">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}"
                             alt="{{ $store->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-store text-forest-700 dark:text-gold-400 text-3xl"></i>
                    @endif
                </div>

                <h1 class="font-display text-2xl font-bold text-ink dark:text-cream mb-4">
                    {{ $store->name }}
                </h1>

                @if($store->status === 'pending')
                    <div class="inline-flex items-center gap-2 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 text-amber-800 dark:text-amber-300 px-4 py-2 rounded-full mb-5">
                        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                        <span class="font-medium text-sm">
                            <i class="fa-solid fa-clock"></i> قيد المراجعة
                        </span>
                    </div>
                @elseif($store->status === 'inactive')
                    <div class="inline-flex items-center gap-2 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-300 px-4 py-2 rounded-full mb-5">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        <span class="font-medium text-sm">
                            <i class="fa-solid fa-circle-xmark"></i> متجر مرفوض
                        </span>
                    </div>
                @elseif($store->status === 'active')
                    <div class="inline-flex items-center gap-2 bg-forest-50 dark:bg-forest-950/30 border border-forest-200 dark:border-forest-900/50 text-forest-800 dark:text-forest-300 px-4 py-2 rounded-full mb-5">
                        <span class="w-2 h-2 bg-forest-500 rounded-full"></span>
                        <span class="font-medium text-sm">
                            <i class="fa-solid fa-circle-check"></i> متجر معتمد
                        </span>
                    </div>
                @endif

                <p class="text-ink-muted dark:text-cream/70 leading-relaxed mb-6 max-w-lg mx-auto">
                    @if($store->status === 'pending')
                        طلب إنشاء متجرك قيد المراجعة من قبل الإدارة.<br>
                        سيتم تفعيل متجرك خلال <strong class="text-ink dark:text-cream">24-48 ساعة</strong>.
                    @elseif($store->status === 'inactive')
                        للأسف، لم يتم اعتماد متجرك.<br>
                        للاستفسار، يرجى التواصل مع الدعم الفني.
                    @else
                        متجرك جاهز للبيع! يمكنك البدء بإضافة منتجاتك الآن.
                    @endif
                </p>

                <div class="bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded-lg p-6 text-right mb-6">
                    <h3 class="font-display font-bold text-ink dark:text-cream mb-5 flex items-center gap-2 justify-end">
                        <i class="fa-solid fa-list-check text-forest-700 dark:text-gold-400 text-sm"></i>
                        مراحل الإنشاء
                    </h3>

                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center shrink-0">
                                <div class="w-8 h-8 flex items-center justify-center bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 rounded-full">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </div>
                                <div class="w-0.5 h-full bg-forest-300 dark:bg-forest-800 my-1"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="font-medium text-forest-700 dark:text-gold-400">تم إنشاء الحساب</p>
                                <p class="text-xs text-ink-muted dark:text-cream/50 mt-1">
                                    {{ $store->created_at->format('Y/m/d — H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="flex flex-col items-center shrink-0">
                                @if($store->status === 'pending')
                                    <div class="w-8 h-8 flex items-center justify-center bg-amber-500 text-white rounded-full animate-pulse">
                                        <i class="fa-solid fa-clock text-xs"></i>
                                    </div>
                                @elseif($store->status === 'active')
                                    <div class="w-8 h-8 flex items-center justify-center bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 rounded-full">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </div>
                                @else
                                    <div class="w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-full">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </div>
                                @endif
                                <div class="w-0.5 h-full bg-stone-300 dark:bg-stone-700 my-1"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="font-medium
                                          @if($store->status === 'pending') text-amber-700 dark:text-amber-400
                                          @elseif($store->status === 'active') text-forest-700 dark:text-gold-400
                                          @else text-red-700 dark:text-red-400 @endif">
                                    مراجعة الإدارة
                                </p>
                                <p class="text-xs text-ink-muted dark:text-cream/50 mt-1">
                                    @if($store->status === 'pending')
                                        جاري المراجعة...
                                    @elseif($store->status === 'active')
                                        تم الاعتماد بنجاح
                                    @else
                                        تم الرفض — تواصل مع الدعم
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="flex flex-col items-center shrink-0">
                                @if($store->status === 'active')
                                    <div class="w-8 h-8 flex items-center justify-center bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 rounded-full">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </div>
                                @else
                                    <div class="w-8 h-8 flex items-center justify-center bg-stone-300 dark:bg-zinc-800 text-ink-muted dark:text-cream/40 rounded-full">
                                        <i class="fa-solid fa-lock text-xs"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-medium {{ $store->status === 'active' ? 'text-forest-700 dark:text-gold-400' : 'text-ink-faint dark:text-cream/40' }}">
                                    تفعيل المتجر
                                </p>
                                <p class="text-xs text-ink-muted dark:text-cream/50 mt-1">
                                    {{ $store->status === 'active' ? 'متجرك جاهز للبيع 🎉' : 'في انتظار المراجعة' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 justify-center">
                    @if($store->status === 'active')
                        <a href="{{ route('merchant.dashboard') }}" class="btn-solid">
                            <i class="fa-solid fa-bolt"></i>
                            الذهاب للوحة التحكم
                        </a>
                    @else
                        <a href="{{ route('pages.contact') }}" class="btn-solid">
                            <i class="fa-solid fa-headset"></i>
                            تواصل مع الدعم
                        </a>
                        <a href="{{ route('home') }}" class="btn-outline">
                            <i class="fa-solid fa-house"></i>
                            الرئيسية
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection