@extends('layouts.public')

@section('title', 'الرئيسية')

@section('content')

    {{-- Hero Banner --}}
    <section class="bg-gradient-to-l from-indigo-700 via-indigo-600 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 py-16 md:py-24">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <span class="inline-block bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-sm font-bold mb-4">
                        🔥 تخفيضات تصل إلى 50%
                    </span>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
                        تسوّق أحدث صيحات الموضة
                    </h1>
                    <p class="text-lg text-indigo-100 mb-6">
                        اكتشف تشكيلة واسعة من الملابس الرجالية والنسائية والأطفال من أفضل المتاجر.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('products.index') }}"
                           class="bg-white text-indigo-700 hover:bg-gray-100 px-6 py-3 rounded-lg font-bold transition">
                            🛍️ تسوق الآن
                        </a>
                        <a href="{{ route('offers.index') }}"
                           class="bg-yellow-400 text-gray-900 hover:bg-yellow-300 px-6 py-3 rounded-lg font-bold transition">
                            🔥 شاهد العروض
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex justify-center">
                    <div class="text-[200px]">👕</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    @if($mainCategories->count() > 0)
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">تسوق حسب التصنيف</h2>
                    <p class="text-gray-500">اختر ما يناسبك من تصنيفاتنا المتنوعة</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($mainCategories as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}"
                           class="group bg-gray-50 hover:bg-indigo-50 border border-gray-200 hover:border-indigo-300 rounded-xl p-4 text-center transition">
                            <div class="text-5xl mb-3 group-hover:scale-110 transition">📂</div>
                            <h3 class="font-bold text-gray-800 text-sm mb-1">{{ $category->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $category->products_count }} منتج</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Active Offers --}}
    @if($activeOffers->count() > 0)
        <section class="py-12 bg-gradient-to-l from-red-50 to-yellow-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">🔥 عروض نشطة</h2>
                        <p class="text-gray-500">لا تفوّت هذه العروض الحصرية!</p>
                    </div>
                    <a href="{{ route('offers.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                        كل العروض →
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($activeOffers as $offer)
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition overflow-hidden">
                            @if($offer->banner)
                                <img src="{{ asset('storage/' . $offer->banner) }}" class="w-full h-40 object-cover">
                            @else
                                <div class="w-full h-40 bg-gradient-to-l from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-6xl">🔥</span>
                                </div>
                            @endif
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-lg">{{ $offer->title }}</h3>
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-bold whitespace-nowrap">
                                        -{{ $offer->discount_percent }}%
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $offer->description }}</p>
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>🏪 {{ $offer->store->name }}</span>
                                    <span>حتى {{ $offer->end_date->format('d/m') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Discounted Products --}}
    @if($discountedProducts->count() > 0)
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">💰 منتجات بخصم</h2>
                        <p class="text-gray-500">وفّر أكثر على مشترياتك</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($discountedProducts as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured Products --}}
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">✨ أحدث المنتجات</h2>
                    <p class="text-gray-500">جديدنا من الملابس العصرية</p>
                </div>
                <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                    كل المنتجات →
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    {{-- Stores --}}
    @if($stores->count() > 0)
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">🏪 متاجر مميزة</h2>
                        <p class="text-gray-500">تسوّق من أفضل المتاجر</p>
                    </div>
                    <a href="{{ route('stores.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                        كل المتاجر →
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($stores as $store)
                        <a href="{{ route('stores.show', $store) }}"
                           class="bg-white border rounded-xl overflow-hidden hover:shadow-lg transition group">
                            @if($store->banner)
                                <img src="{{ asset('storage/' . $store->banner) }}" class="w-full h-32 object-cover">
                            @else
                                <div class="w-full h-32 bg-gradient-to-l from-indigo-400 to-purple-500"></div>
                            @endif
                            <div class="p-4 -mt-8">
                                <div class="w-16 h-16 bg-white rounded-full shadow-md flex items-center justify-center text-3xl mb-2 border-4 border-white">
                                    @if($store->logo)
                                        <img src="{{ asset('storage/' . $store->logo) }}" class="w-full h-full object-cover rounded-full">
                                    @else
                                        🏪
                                    @endif
                                </div>
                                <h3 class="font-bold text-lg group-hover:text-indigo-600">{{ $store->name }}</h3>
                                <p class="text-sm text-gray-500 line-clamp-1 mb-2">{{ $store->description }}</p>
                                <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded">
                                    {{ $store->products_count }} منتج
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Features --}}
    <section class="py-12 bg-indigo-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-5xl mb-3">🚚</div>
                    <h3 class="font-bold mb-1">توصيل سريع</h3>
                    <p class="text-xs text-gray-600">خلال 1-3 أيام</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl mb-3">🔄</div>
                    <h3 class="font-bold mb-1">إرجاع مجاني</h3>
                    <p class="text-xs text-gray-600">خلال 14 يوماً</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl mb-3">🔒</div>
                    <h3 class="font-bold mb-1">دفع آمن</h3>
                    <p class="text-xs text-gray-600">حماية 100%</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl mb-3">💬</div>
                    <h3 class="font-bold mb-1">دعم 24/7</h3>
                    <p class="text-xs text-gray-600">في خدمتك دائماً</p>
                </div>
            </div>
        </div>
    </section>

@endsection
