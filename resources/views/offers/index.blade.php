@extends('layouts.public')

@section('title', 'العروض')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">الرئيسية</a>
            <span class="mx-2">›</span>
            <span class="text-gray-800">العروض</span>
        </nav>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-1">🔥 العروض والتخفيضات</h1>
            <p class="text-gray-500">اكتشف أفضل العروض النشطة حالياً</p>
        </div>

        {{-- Active Offers --}}
        @if($activeOffers->count() > 0)
            <div class="mb-10">
                <h2 class="text-xl font-bold text-gray-700 mb-4 flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full ml-2 animate-pulse"></span>
                    عروض نشطة الآن ({{ $activeOffers->count() }})
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($activeOffers as $offer)
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition overflow-hidden">
                            @if($offer->banner)
                                <img src="{{ asset('storage/' . $offer->banner) }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-l from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-7xl">🔥</span>
                                </div>
                            @endif

                            <div class="p-5">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-xl">{{ $offer->title }}</h3>
                                    <span class="bg-red-500 text-white text-sm px-3 py-1 rounded-full font-bold whitespace-nowrap">
                                        -{{ $offer->discount_percent }}%
                                    </span>
                                </div>

                                @if($offer->description)
                                    <p class="text-sm text-gray-600 mb-3">{{ $offer->description }}</p>
                                @endif

                                <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                                    <a href="{{ route('stores.show', $offer->store) }}" class="hover:text-indigo-600">
                                        🏪 {{ $offer->store->name }}
                                    </a>
                                    <span>ينتهي {{ $offer->end_date->diffForHumans() }}</span>
                                </div>

                                <a href="{{ route('stores.show', $offer->store) }}"
                                   class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg">
                                    تسوق الآن →
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
                <h2 class="text-xl font-bold text-gray-700 mb-4 flex items-center">
                    <span class="w-3 h-3 bg-blue-500 rounded-full ml-2"></span>
                    عروض قادمة ({{ $upcomingOffers->count() }})
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($upcomingOffers as $offer)
                        <div class="bg-white rounded-xl shadow overflow-hidden opacity-80">
                            @if($offer->banner)
                                <img src="{{ asset('storage/' . $offer->banner) }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-l from-blue-400 to-cyan-500 flex items-center justify-center">
                                    <span class="text-7xl">⏰</span>
                                </div>
                            @endif

                            <div class="p-5">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-xl">{{ $offer->title }}</h3>
                                    <span class="bg-blue-500 text-white text-sm px-3 py-1 rounded-full font-bold whitespace-nowrap">
                                        -{{ $offer->discount_percent }}%
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    يبدأ {{ $offer->start_date->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($activeOffers->count() == 0 && $upcomingOffers->count() == 0)
            <div class="bg-white rounded-lg shadow text-center py-16">
                <div class="text-6xl mb-4">🔥</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد عروض حالياً</h3>
                <p class="text-gray-500 mb-4">تفقدنا قريباً للاطلاع على العروض الجديدة</p>
                <a href="{{ route('products.index') }}"
                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                    تسوق المنتجات →
                </a>
            </div>
        @endif
    </div>

@endsection