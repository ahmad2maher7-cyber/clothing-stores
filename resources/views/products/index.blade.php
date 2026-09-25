@extends('layouts.public')

@section('title', 'كل المنتجات')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-gray-900">الرئيسية</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-900">المنتجات</span>
        </nav>

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">كل المنتجات</h1>
            <p class="text-sm text-gray-500">
                {{ $products->total() }} منتج متاح
                @if(request('search'))
                    للبحث: "<strong class="text-gray-900">{{ request('search') }}</strong>"
                @endif
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            {{-- ============ Sidebar Filters ============ --}}
            <aside class="lg:col-span-1">
                <div class="bg-white border border-gray-200 rounded-lg sticky top-24" x-data="{ openFilters: true }">

                    <div class="p-5 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-medium text-gray-900">الفلاتر</h3>
                        <button @click="openFilters = !openFilters" class="lg:hidden text-gray-600">
                            <span x-text="openFilters ? '−' : '+'"></span>
                        </button>
                    </div>

                    <form method="GET" x-show="openFilters" x-cloak class="p-5 space-y-6">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        {{-- Categories --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">التصنيف</h4>
                            <div class="space-y-2 max-h-56 overflow-y-auto">
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input type="radio" name="category" value="" 
                                           {{ !request('category') ? 'checked' : '' }}
                                           class="text-gray-900 focus:ring-gray-500">
                                    <span class="text-gray-700">الكل</span>
                                </label>
                                @foreach($categories as $category)
                                    <div>
                                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                                            <input type="radio" name="category" value="{{ $category->id }}"
                                                   {{ request('category') == $category->id ? 'checked' : '' }}
                                                   class="text-gray-900 focus:ring-gray-500">
                                            <span class="text-gray-700 font-medium">{{ $category->name }}</span>
                                        </label>
                                        @if($category->children->count() > 0)
                                            <div class="mr-6 mt-1 space-y-1">
                                                @foreach($category->children as $child)
                                                    <label class="flex items-center gap-2 text-xs cursor-pointer">
                                                        <input type="radio" name="category" value="{{ $child->id }}"
                                                               {{ request('category') == $child->id ? 'checked' : '' }}
                                                               class="text-gray-900 focus:ring-gray-500">
                                                        <span class="text-gray-600">{{ $child->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Gender --}}
                        <div class="pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">الفئة</h4>
                            <div class="space-y-2">
                                @foreach(['men' => 'رجالي', 'women' => 'نسائي', 'kids' => 'أطفال', 'unisex' => 'للجنسين'] as $value => $label)
                                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                                        <input type="radio" name="gender" value="{{ $value }}"
                                               {{ request('gender') == $value ? 'checked' : '' }}
                                               class="text-gray-900 focus:ring-gray-500">
                                        <span class="text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">السعر</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}"
                                       placeholder="من" min="0"
                                       class="form-input text-sm">
                                <input type="number" name="max_price" value="{{ request('max_price') }}"
                                       placeholder="إلى" min="0"
                                       class="form-input text-sm">
                            </div>
                            <p class="text-xs text-gray-400 mt-2">
                                النطاق: {{ $priceRange['min'] }} - {{ $priceRange['max'] }} ₪
                            </p>
                        </div>

                        {{-- Brands --}}
                        <div class="pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">الماركة</h4>
                            <select name="brand" class="form-input text-sm">
                                <option value="">كل الماركات</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Stores --}}
                        <div class="pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">المتجر</h4>
                            <select name="store" class="form-input text-sm">
                                <option value="">كل المتاجر</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ request('store') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Has Discount --}}
                        <div class="pt-6 border-t border-gray-200">
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="has_discount" value="1"
                                       {{ request('has_discount') ? 'checked' : '' }}
                                       class="rounded text-gray-900 focus:ring-gray-500">
                                <span class="text-gray-700">عليها خصم فقط</span>
                            </label>
                        </div>

                        {{-- Buttons --}}
                        <div class="pt-6 border-t border-gray-200 space-y-2">
                            <button type="submit" class="btn-primary w-full">
                                تطبيق الفلاتر
                            </button>
                            <a href="{{ route('products.index') }}" class="btn-secondary w-full">
                                إعادة تعيين
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- ============ Products Grid ============ --}}
            <div class="lg:col-span-3">

                {{-- Sort Bar --}}
                <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6 flex justify-between items-center gap-3">
                    <p class="text-sm text-gray-600 hidden md:block">
                        عرض {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} من {{ $products->total() }}
                    </p>
                    <form method="GET" class="flex items-center gap-2">
                        @foreach(request()->except('sort') as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <label class="text-xs text-gray-500 hidden md:block">ترتيب حسب:</label>
                        <select name="sort" onchange="this.form.submit()"
                                class="form-input text-sm w-auto">
                            <option value="">الأحدث</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>الأرخص</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>الأغلى</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                        </select>
                    </form>
                </div>

                {{-- Products --}}
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-5 mb-8">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    <div class="flex justify-center">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="bg-white border border-gray-200 rounded-lg text-center py-16">
                        <div class="text-5xl mb-4">🔍</div>
                        <h3 class="text-base font-medium text-gray-900 mb-2">لا توجد منتجات</h3>
                        <p class="text-sm text-gray-500 mb-6">جرب تغيير الفلاتر أو البحث بكلمات مختلفة</p>
                        <a href="{{ route('products.index') }}" class="btn-primary">
                            إعادة تعيين الفلاتر
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection