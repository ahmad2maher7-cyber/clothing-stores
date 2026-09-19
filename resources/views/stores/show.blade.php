@extends('layouts.public')

@section('title', $store->name)

@section('content')

    {{-- Store Banner --}}
    <div class="relative">
        @if($store->banner)
            <img src="{{ asset('storage/' . $store->banner) }}" class="w-full h-64 md:h-80 object-cover">
        @else
            <div class="w-full h-64 md:h-80 bg-gradient-to-l from-indigo-500 via-indigo-600 to-purple-700"></div>
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

        <div class="absolute bottom-0 right-0 left-0 p-6 max-w-7xl mx-auto">
            <div class="flex items-end gap-4">
                <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-2xl shadow-xl flex items-center justify-center text-6xl border-4 border-white">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" class="w-full h-full object-cover rounded-2xl">
                    @else
                        🏪
                    @endif
                </div>
                <div class="flex-1 text-white">
                    <h1 class="text-2xl md:text-4xl font-bold mb-1">{{ $store->name }}</h1>
                    <p class="text-sm md:text-base opacity-90 mb-2">{{ $store->description }}</p>
                    <div class="flex flex-wrap gap-4 text-xs md:text-sm">
                        @if($store->address)
                            <span>📍 {{ $store->address }}</span>
                        @endif
                        <span>🛍️ {{ $store->products()->where('status', 'active')->count() }} منتج</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Working Hours --}}
        @if($store->working_hours)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="font-bold text-lg mb-3">⏰ أوقات العمل</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                    @php
                        $days = [
                            'saturday' => 'السبت', 'sunday' => 'الأحد', 'monday' => 'الاثنين',
                            'tuesday' => 'الثلاثاء', 'wednesday' => 'الأربعاء', 'thursday' => 'الخميس', 'friday' => 'الجمعة',
                        ];
                    @endphp
                    @foreach($days as $key => $label)
                        @if(!empty($store->working_hours[$key]))
                            <div class="bg-gray-50 rounded p-3">
                                <p class="font-medium text-gray-700">{{ $label }}</p>
                                <p class="text-indigo-600">{{ $store->working_hours[$key] }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="🔍 ابحث..."
                       class="border-gray-300 rounded-lg focus:border-indigo-500">

                <select name="category" class="border-gray-300 rounded-lg focus:border-indigo-500">
                    <option value="">كل التصنيفات</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->products_count }})
                        </option>
                    @endforeach
                </select>

                <select name="sort" class="border-gray-300 rounded-lg focus:border-indigo-500">
                    <option value="">الأحدث</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>الأرخص</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>الأغلى</option>
                </select>

                <div class="flex space-x-2 space-x-reverse">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex-1">تطبيق</button>
                    <a href="{{ route('stores.show', $store) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">إعادة</a>
                </div>
            </form>
        </div>

        {{-- Products --}}
        <h2 class="text-2xl font-bold text-gray-800 mb-4">منتجات المتجر</h2>

        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-8">{{ $products->links() }}</div>
        @else
            <div class="bg-white rounded-lg shadow text-center py-16">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد منتجات</h3>
            </div>
        @endif
    </div>

@endsection