@extends('merchant.layouts.app')

@section('title', 'العروض')
@section('page-title', 'العروض')

@section('content')

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">العروض</h2>
            <p class="text-sm text-ink-muted dark:text-cream/60">إدارة العروض الترويجية</p>
        </div>
        <a href="{{ route('merchant.offers.create') }}" class="btn-solid whitespace-nowrap">
            <i class="fa-solid fa-plus"></i>
            إضافة عرض
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">إجمالي</p>
            <p class="font-display text-3xl font-bold text-ink dark:text-cream">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">🔥 نشطة</p>
            <p class="font-display text-3xl font-bold text-forest-700 dark:text-gold-400">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">⏰ قادمة</p>
            <p class="font-display text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['upcoming'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">منتهية</p>
            <p class="font-display text-3xl font-bold text-ink-muted dark:text-cream/60">{{ $stats['expired'] }}</p>
        </div>
    </div>

    @if($offers->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
            @foreach($offers as $offer)
                @php
                    $isActive = $offer->start_date <= now() && $offer->end_date >= now();
                    $isUpcoming = $offer->start_date > now();
                @endphp
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden hover:border-forest-500 dark:hover:border-gold-500 transition-colors group">

                    <div class="h-40 overflow-hidden">
                        @if($offer->banner)
                            <img src="{{ asset('storage/' . $offer->banner) }}"
                                 alt="{{ $offer->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-forest-700 to-gold-500 flex items-center justify-center">
                                <i class="fa-solid fa-fire text-6xl text-white/40"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="flex justify-between items-start gap-3 mb-3">
                            <h3 class="font-display font-bold text-ink dark:text-cream line-clamp-2 group-hover:text-forest-700 dark:group-hover:text-gold-400 transition-colors">
                                {{ $offer->title }}
                            </h3>
                            <span class="badge badge-dark whitespace-nowrap shrink-0">-{{ $offer->discount_percent }}%</span>
                        </div>

                        @if($offer->description)
                            <p class="text-sm text-ink-muted dark:text-cream/60 mb-3 line-clamp-2">{{ $offer->description }}</p>
                        @endif

                        <div class="text-xs text-ink-muted dark:text-cream/50 mb-4 flex items-center gap-1.5">
                            <i class="fa-solid fa-calendar-days"></i>
                            {{ $offer->start_date->format('Y/m/d') }} <i class="fa-solid fa-arrow-left text-[8px]"></i> {{ $offer->end_date->format('Y/m/d') }}
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-stone-100 dark:border-stone-800">
                            @if($isActive)
                                <span class="badge badge-forest">🔥 نشط</span>
                            @elseif($isUpcoming)
                                <span class="badge badge-stone">⏰ قادم</span>
                            @else
                                <span class="badge badge-stone">منتهي</span>
                            @endif

                            <div class="flex items-center gap-3">
                                <a href="{{ route('merchant.offers.edit', $offer) }}"
                                   class="inline-flex items-center gap-1 text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                    تعديل
                                </a>
                                <form action="{{ route('merchant.offers.destroy', $offer) }}"
                                      method="POST" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف العرض؟')"
                                      class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 text-xs font-medium text-red-600 dark:text-red-400 hover:opacity-70 transition-opacity">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-center">{{ $offers->links() }}</div>
    @else
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg text-center py-20">
            <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                <i class="fa-solid fa-fire text-4xl text-ink-muted dark:text-cream/40"></i>
            </div>
            <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد عروض</h3>
            <p class="text-sm text-ink-muted dark:text-cream/60 mb-6">ابدأ بإضافة عرض ترويجي</p>
            <a href="{{ route('merchant.offers.create') }}" class="btn-solid inline-flex">
                <i class="fa-solid fa-plus"></i>
                إضافة عرض
            </a>
        </div>
    @endif

@endsection