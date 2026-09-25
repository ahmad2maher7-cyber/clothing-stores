@extends('layouts.public')

@section('title', 'العروض')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-gray-900">الرئيسية</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-900">العروض</span>
        </nav>

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">🔥 العروض والتخفيضات</h1>
            <p class="text-sm text-gray-500">اكتشف أفضل العروض النشطة حالياً</p>
        </div>

        {{-- Active Offers --}}
        @if($activeOffers->count() > 0)
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <h2 class="text-lg font-bold text-gray-900">عروض نشطة الآن ({{ $activeOffers->count() }})</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($activeOffers as $offer)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:border-gray-400 transition">
                            @if($offer->banner)
                                <img src="{{ asset('storage/' . $offer->banner) }}" 
                                     class="w-full h-44 object-cover">
                            @else
                                <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-6xl">
                                    🔥
                                </div>
                            @endif

                            <div class="p-5">
                                <div class="flex justify-between items-start gap-3 mb-3">
                                    <h3 class="font-bold text-gray-900">{{ $offer->title }}</h3>
                                    <span class="badge badge-dark whitespace-nowrap">
                                        -{{ $offer->discount_percent }}%
                                    </span>
                                </div>

                                @if($offer->description)
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $offer->description }}</p>
                                @endif

                                <div class="flex justify-between items-center text-xs text-gray-500 mb-4 pt-3 border-t border-gray-100">
                                    <a href="{{ route('stores.show', $offer->store) }}" class="hover:text-gray-900">
                                        🏪 {{ $offer->store->name }}
                                    </a>
                                    <span>ينتهي {{ $offer->end_date->diffForHumans() }}</span>
                                </div>

                                <a href="{{ route('stores.show', $offer->store) }}" 
                                   class="btn-primary btn-sm w-full">
                                    تسوّق الآن
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Upcoming Offers --}}
        @if($upcomingOffers->count() > 0)
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    <h2 class="text-lg font-bold text-gray-900">عروض قادمة ({{ $upcomingOffers->count() }})</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($upcomingOffers as $offer)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden opacity-75">
                            @if($offer->banner)
                                <img src="{{ asset('storage/' . $offer->banner) }}" 
                                     class="w-full h-44 object-cover">
                            @else
                                <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-6xl">
                                    ⏰
                                </div>
                            @endif

                            <div class="p-5">
                                <div class="flex justify-between items-start gap-3 mb-3">
                                    <h3 class="font-bold text-gray-900">{{ $offer->title }}</h3>
                                    <span class="badge badge-gray whitespace-nowrap">
                                        -{{ $offer->discount_percent }}%
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">
                                    يبدأ {{ $offer->start_date->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Empty State --}}
        @if($activeOffers->count() == 0 && $upcomingOffers->count() == 0)
            <div class="bg-white border border-gray-200 rounded-lg text-center py-16">
                <div class="text-5xl mb-4">🔥</div>
                <h3 class="text-base font-medium text-gray-900 mb-2">لا توجد عروض حالياً</h3>
                <p class="text-sm text-gray-500 mb-6">تفقدنا قريباً للاطلاع على العروض الجديدة</p>
                <a href="{{ route('products.index') }}" class="btn-primary">
                    تسوّق المنتجات
                </a>
            </div>
        @endif
    </div>

@endsection