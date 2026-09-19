@extends('merchant.layouts.app')

@section('title', 'العروض')
@section('page-title', 'إدارة العروض')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">العروض</h2>
            <p class="text-gray-500 text-sm">إدارة العروض الترويجية الموسمية</p>
        </div>
        <a href="{{ route('merchant.offers.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse">
            <span>➕</span><span>إضافة عرض</span>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-r-4 border-indigo-500">
            <p class="text-xs text-gray-500 mb-1">إجمالي</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-r-4 border-green-500">
            <p class="text-xs text-gray-500 mb-1">🔥 نشطة</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-r-4 border-blue-500">
            <p class="text-xs text-gray-500 mb-1">⏰ قادمة</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['upcoming'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-r-4 border-red-500">
            <p class="text-xs text-gray-500 mb-1">⛔ منتهية</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['expired'] }}</p>
        </div>
    </div>

    @if($offers->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($offers as $offer)
                @php
                    $isActive = $offer->start_date <= now() && $offer->end_date >= now();
                    $isUpcoming = $offer->start_date > now();
                    $isExpired = $offer->end_date < now();
                @endphp
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                    @if($offer->banner)
                        <img src="{{ asset('storage/' . $offer->banner) }}" class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-gradient-to-l from-indigo-500 to-purple-600 flex items-center justify-center">
                            <span class="text-6xl">🔥</span>
                        </div>
                    @endif

                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-lg text-gray-800">{{ $offer->title }}</h3>
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-bold">
                                -{{ $offer->discount_percent }}%
                            </span>
                        </div>

                        @if($offer->description)
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $offer->description }}</p>
                        @endif

                        <div class="text-xs text-gray-500 mb-3">
                            <div>من: {{ $offer->start_date->format('Y/m/d') }}</div>
                            <div>إلى: {{ $offer->end_date->format('Y/m/d') }}</div>
                        </div>

                        <div class="flex justify-between items-center">
                            @if($isActive)
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">🔥 نشط الآن</span>
                            @elseif($isUpcoming)
                                <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs">⏰ قادم</span>
                            @else
                                <span class="bg-gray-400 text-white px-2 py-1 rounded text-xs">⛔ منتهي</span>
                            @endif

                            <div class="flex space-x-1 space-x-reverse">
                                <a href="{{ route('merchant.offers.edit', $offer) }}"
                                   class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-xs">✏️</a>
                                <form action="{{ route('merchant.offers.destroy', $offer) }}" method="POST"
                                      onsubmit="return confirm('حذف العرض؟')">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">🗑️</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $offers->links() }}</div>
    @else
        <div class="bg-white rounded-lg shadow text-center py-16">
            <div class="text-6xl mb-4">🔥</div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد عروض</h3>
            <a href="{{ route('merchant.offers.create') }}"
               class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg mt-2">
                ➕ إضافة عرض
            </a>
        </div>
    @endif

@endsection