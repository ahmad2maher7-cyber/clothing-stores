@extends('layouts.public')

@section('title', $store->name)

@section('content')

    {{-- Store Banner --}}
    <div class="relative bg-gray-100">
        @if($store->banner)
            <img src="{{ asset('storage/' . $store->banner) }}" class="w-full h-56 md:h-72 object-cover">
        @else
            <div class="w-full h-56 md:h-72 bg-gray-100"></div>
        @endif
    </div>

    {{-- Store Info --}}
    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-white border border-gray-200 rounded-lg p-6 -mt-16 relative mb-8">
            <div class="flex flex-col md:flex-row items-start gap-6">

                {{-- Logo --}}
                <div class="w-24 h-24 rounded-lg bg-white border-2 border-gray-200 flex items-center justify-center text-5xl shrink-0 shadow-sm">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" 
                             class="w-full h-full object-cover rounded-lg">
                    @else
                        🏪
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $store->name }}</h1>

                    @if($store->description)
                        <p class="text-sm text-gray-600 leading-relaxed mb-4">{{ $store->description }}</p>
                    @endif

                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                        @if($store->address)
                            <span class="flex items-center gap-1">
                                <span class="text-gray-400">📍</span>
                                {{ $store->address }}
                            </span>
                        @endif
                        <span class="flex items-center gap-1">
                            <span class="text-gray-400">📦</span>
                            {{ $store->products()->where('status', 'active')->count() }} منتج
                        </span>
                    </div>
                </div>
            </div>

            {{-- Working Hours --}}
            @if($store->working_hours)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">أوقات العمل</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @php
                            $days = [
                                'saturday' => 'السبت', 'sunday' => 'الأحد', 'monday' => 'الاثنين',
                                'tuesday' => 'الثلاثاء', 'wednesday' => 'الأربعاء', 'thursday' => 'الخميس', 'friday' => 'الجمعة',
                            ];
                        @endphp
                        @foreach($days as $key => $label)
                            @if(!empty($store->working_hours[$key]))
                                <div class="text-xs">
                                    <span class="font-medium text-gray-700">{{ $label }}:</span>
                                    <span class="text-gray-500">{{ $store->working_hours[$key] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Products --}}
    <div class="max-w-7xl mx-auto px-4 py-4">

        <div class="flex justify-between items-end mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900">منتجات المتجر</h2>
            <a href="{{ route('products.index', ['store' => $store->id]) }}" 
               class="text-sm text-gray-600 hover:text-gray-900">
                عرض الكل ←
            </a>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-white border border-gray-200 rounded-lg text-center py-16">
                <div class="text-5xl mb-4">📦</div>
                <h3 class="text-base font-medium text-gray-900 mb-2">لا توجد منتجات</h3>
                <p class="text-sm text-gray-500">هذا المتجر لم يضف منتجات بعد</p>
            </div>
        @endif
    </div>

@endsection