@extends('layouts.public')

@section('title', 'الرئيسية')

@section('content')

    {{-- ============ Hero Section ============ --}}
    <section class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-16 md:py-24">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

                {{-- Text --}}
                <div>
                    <div class="inline-block border border-gray-300 rounded-full px-4 py-1.5 text-xs text-gray-600 mb-6">
                        ✨ تشكيلة جديدة وصلت
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-6">
                        تسوّق الأناقة<br>
                        <span class="text-gray-400">بأبسط طريقة</span>
                    </h1>

                    <p class="text-base text-gray-600 leading-relaxed mb-8 max-w-md">
                        اكتشف تشكيلة مختارة من الملابس العصرية من أفضل المتاجر الموثوقة، بأسعار منافسة وجودة عالية.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('products.index') }}" class="btn-primary">
                            تسوّق الآن
                        </a>
                        <a href="{{ route('pages.about') }}" class="btn-secondary">
                            تعرّف علينا
                        </a>
                    </div>
                </div>

                {{-- Visual --}}
                <div class="hidden md:flex justify-center">
                    <div class="relative">
                        <div class="w-80 h-80 md:w-96 md:h-96 bg-gray-50 border border-gray-200 rounded-2xl flex items-center justify-center text-[180px] md:text-[220px]">
                            👕
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ Trust Bar ============ --}}
    <section class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🚚</span>
                    <div>
                        <p class="text-sm font-medium text-gray-900">توصيل سريع</p>
                        <p class="text-xs text-gray-500">خلال 1-3 أيام</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🔄</span>
                    <div>
                        <p class="text-sm font-medium text-gray-900">إرجاع مجاني</p>
                        <p class="text-xs text-gray-500">خلال 14 يوماً</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🔒</span>
                    <div>
                        <p class="text-sm font-medium text-gray-900">دفع آمن</p>
                        <p class="text-xs text-gray-500">حماية 100%</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-2xl">💬</span>
                    <div>
                        <p class="text-sm font-medium text-gray-900">دعم 24/7</p>
                        <p class="text-xs text-gray-500">في خدمتك دائماً</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ Categories ============ --}}
    @if($mainCategories->count() > 0)
        <section class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 py-16">

                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">تسوّق حسب التصنيف</h2>
                        <p class="text-sm text-gray-500">اختر ما يناسبك من تصنيفاتنا</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        عرض الكل ←
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($mainCategories as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}"
                           class="group bg-white border border-gray-200 hover:border-gray-400 rounded-lg p-5 text-center transition">
                            <div class="text-4xl mb-3">📂</div>
                            <h3 class="text-sm font-medium text-gray-900 mb-1">{{ $category->name }}</h3>
                            <p class="text-xs text-gray-400">{{ $category->products_count }} منتج</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ Active Offers ============ --}}
    @if($activeOffers->count() > 0)
        <section class="bg-gray-50 border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 py-16">

                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">🔥 عروض نشطة</h2>
                        <p class="text-sm text-gray-500">لا تفوّت هذه العروض الحصرية</p>
                    </div>
                    <a href="{{ route('offers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        كل العروض ←
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($activeOffers as $offer)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:border-gray-400 transition">
                            @if($offer->banner)
                                <img src="{{ asset('storage/' . $offer->banner) }}" class="w-full h-40 object-cover">
                            @else
                                <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-5xl">
                                    🔥
                                </div>
                            @endif

                            <div class="p-5">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="font-bold text-gray-900">{{ $offer->title }}</h3>
                                    <span class="badge badge-dark whitespace-nowrap">
                                        -{{ $offer->discount_percent }}%
                                    </span>
                                </div>

                                @if($offer->description)
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $offer->description }}</p>
                                @endif

                                <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                                    <span>🏪 {{ $offer->store->name }}</span>
                                    <span>حتى {{ $offer->end_date->format('d/m') }}</span>
                                </div>

                                <a href="{{ route('stores.show', $offer->store) }}" 
                                   class="btn-secondary btn-sm w-full">
                                    تسوّق العرض
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ Discounted Products ============ --}}
    @if($discountedProducts->count() > 0)
        <section class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 py-16">

                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">💰 منتجات بخصم</h2>
                        <p class="text-sm text-gray-500">وفّر أكثر على مشترياتك</p>
                    </div>
                    <a href="{{ route('products.index', ['has_discount' => 1]) }}" class="text-sm text-gray-600 hover:text-gray-900">
                        عرض الكل ←
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                    @foreach($discountedProducts as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ Featured Products ============ --}}
    <section class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-16">

            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">✨ أحدث المنتجات</h2>
                    <p class="text-sm text-gray-500">جديدنا من الملابس العصرية</p>
                </div>
                <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    كل المنتجات ←
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                @foreach($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ Stores ============ --}}
    @if($stores->count() > 0)
        <section class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 py-16">

                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">🏪 متاجر مميزة</h2>
                        <p class="text-sm text-gray-500">تسوّق من أفضل المتاجر</p>
                    </div>
                    <a href="{{ route('stores.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        كل المتاجر ←
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($stores as $store)
                        <a href="{{ route('stores.show', $store) }}"
                           class="bg-white border border-gray-200 hover:border-gray-400 rounded-lg overflow-hidden transition">
                            @if($store->banner)
                                <img src="{{ asset('storage/' . $store->banner) }}" class="w-full h-32 object-cover">
                            @else
                                <div class="w-full h-32 bg-gray-100"></div>
                            @endif

                            <div class="p-5">
                                <div class="flex items-start gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-2xl shrink-0">
                                        @if($store->logo)
                                            <img src="{{ asset('storage/' . $store->logo) }}" class="w-full h-full object-cover rounded-full">
                                        @else
                                            🏪
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-gray-900 mb-1">{{ $store->name }}</h3>
                                        <p class="text-sm text-gray-500 line-clamp-1 mb-2">
                                            {{ $store->description }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $store->products_count }} منتج
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ CTA ============ --}}
    <section class="bg-white">
        <div class="max-w-3xl mx-auto px-4 py-20 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                جاهز لتبدأ التسوق؟
            </h2>
            <p class="text-gray-600 mb-8 max-w-lg mx-auto">
                آلاف المنتجات بانتظارك. اكتشف تشكيلتنا اليوم واحصل على أفضل الأسعار.
            </p>
            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('products.index') }}" class="btn-primary btn-lg">
                    ابدأ التسوّق الآن
                </a>
                <a href="{{ route('register') }}" class="btn-secondary btn-lg">
                    أنشئ حساب
                </a>
            </div>
        </div>
    </section>

@endsection