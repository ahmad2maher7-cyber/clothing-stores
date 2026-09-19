@extends('layouts.public')

@section('title', 'كل المتاجر')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">الرئيسية</a>
            <span class="mx-2">›</span>
            <span class="text-gray-800">المتاجر</span>
        </nav>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-1">🏪 المتاجر</h1>
            <p class="text-gray-500">{{ $stores->total() }} متجر متاح</p>
        </div>

        {{-- Search --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="🔍 ابحث باسم المتجر..."
                       class="flex-1 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 rounded-lg">
                    بحث
                </button>
            </form>
        </div>

        @if($stores->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($stores as $store)
                    <a href="{{ route('stores.show', $store) }}"
                       class="bg-white rounded-xl overflow-hidden shadow hover:shadow-lg transition group">
                        @if($store->banner)
                            <img src="{{ asset('storage/' . $store->banner) }}" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gradient-to-l from-indigo-400 to-purple-500"></div>
                        @endif
                        <div class="p-5 -mt-10 relative">
                            <div class="w-20 h-20 bg-white rounded-full shadow-md flex items-center justify-center text-4xl mb-3 border-4 border-white">
                                @if($store->logo)
                                    <img src="{{ asset('storage/' . $store->logo) }}" class="w-full h-full object-cover rounded-full">
                                @else
                                    🏪
                                @endif
                            </div>
                            <h3 class="font-bold text-xl group-hover:text-indigo-600 mb-1">{{ $store->name }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-2 mb-3">{{ $store->description }}</p>
                            <div class="flex justify-between items-center text-sm">
                                <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full">
                                    {{ $store->products_count }} منتج
                                </span>
                                <span class="text-indigo-600 font-medium">زيارة المتجر →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">{{ $stores->links() }}</div>
        @else
            <div class="bg-white rounded-lg shadow text-center py-16">
                <div class="text-6xl mb-4">🏪</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد متاجر</h3>
            </div>
        @endif
    </div>

@endsection