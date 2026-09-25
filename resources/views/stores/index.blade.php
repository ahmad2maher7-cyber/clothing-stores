@extends('layouts.public')

@section('title', 'كل المتاجر')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-gray-900">الرئيسية</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-900">المتاجر</span>
        </nav>

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">المتاجر</h1>
            <p class="text-sm text-gray-500">{{ $stores->total() }} متجر متاح</p>
        </div>

        {{-- Search --}}
        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-8">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث باسم المتجر..."
                       class="form-input flex-1">
                <button type="submit" class="btn-primary">
                    بحث
                </button>
            </form>
        </div>

        {{-- Stores Grid --}}
        @if($stores->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($stores as $store)
                    <a href="{{ route('stores.show', $store) }}"
                       class="bg-white border border-gray-200 hover:border-gray-400 rounded-lg overflow-hidden transition group">

                        {{-- Banner --}}
                        @if($store->banner)
                            <img src="{{ asset('storage/' . $store->banner) }}" 
                                 class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-5xl">
                                🏪
                            </div>
                        @endif

                        {{-- Content --}}
                        <div class="p-5">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-14 h-14 rounded-full bg-white border-2 border-gray-200 flex items-center justify-center text-2xl shrink-0 -mt-10 shadow-sm">
                                    @if($store->logo)
                                        <img src="{{ asset('storage/' . $store->logo) }}" 
                                             class="w-full h-full object-cover rounded-full">
                                    @else
                                        🏪
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-gray-900 mb-1 group-hover:text-gray-600 transition">
                                        {{ $store->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        {{ $store->products_count }} منتج
                                    </p>
                                </div>
                            </div>

                            @if($store->description)
                                <p class="text-sm text-gray-600 line-clamp-2 mb-4">{{ $store->description }}</p>
                            @endif

                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <span class="text-xs text-gray-400">زيارة المتجر</span>
                                <span class="text-sm text-gray-600">←</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="flex justify-center">
                {{ $stores->links() }}
            </div>
        @else
            <div class="bg-white border border-gray-200 rounded-lg text-center py-16">
                <div class="text-5xl mb-4">🏪</div>
                <h3 class="text-base font-medium text-gray-900 mb-2">لا توجد متاجر</h3>
                <p class="text-sm text-gray-500">جرب البحث بكلمات مختلفة</p>
            </div>
        @endif
    </div>

@endsection