@extends('layouts.public')

@section('title', 'كل المنتجات')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">الرئيسية</a>
            <span class="mx-2">›</span>
            <span class="text-gray-800">المنتجات</span>
        </nav>

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-1">كل المنتجات</h1>
            <p class="text-gray-500">
                {{ $products->total() }} منتج متاح
                @if(request('search'))
                    للبحث: "<strong>{{ request('search') }}</strong>"
                @endif
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            {{-- Sidebar Filters --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-5 sticky top-4" x-data="{ openFilters: true }">
                    
                    <div class="flex justify-between items-center mb-4 lg:mb-4">
                        <h3 class="font-bold text-gray-800">🔍 الفلاتر</h3>
                        <button @click="openFilters = !openFilters" class="lg:hidden text-gray-600">
                            <span x-text="openFilters ? '▲' : '▼'"></span>
                        </button>
                    </div>

                    <form method="GET" x-show="openFilters" x-cloak>
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        {{-- Categories --}}
                        <div class="mb-5 pb-5 border-b">
                            <h4 class="font-medium text-gray-700 mb-3">📂 التصنيف</h4>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                <label class="flex items-center text-sm cursor-pointer">
                                    <input type="radio" name="category" value="" 
                                           {{ !request('category') ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    <span class="mr-2">الكل</span>
                                </label>
                                @foreach($categories as $category)
                                    <div>
                                        <label class="flex items-center text-sm cursor-pointer">
                                            <input type="radio" name="category" value="{{ $category->id }}"
                                                   {{ request('category') == $category->id ? 'checked' : '' }}
                                                   class="text-indigo-600">
                                            <span class="mr-2 font-medium">{{ $category->name }}</span>
                                        </label>
                                        @if($category->children->count() > 0)
                                            <div class="mr-4 mt-1 space-y-1">
                                                @foreach($category->children as $child)
                                                    <label class="flex items-center text-xs cursor-pointer text-gray-600">
                                                        <input type="radio" name="category" value="{{ $child->id }}"
                                                               {{ request('category') == $child->id ? 'checked' : '' }}
                                                               class="text-indigo-600">
                                                        <span class="mr-2">{{ $child->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Gender --}}
                        <div class="mb-5 pb-5 border-b">
                            <h4 class="font-medium text-gray-700 mb-3">👥 الفئة</h4>
                            <div class="space-y-2">
                                @foreach(['men' => '👔 رجالي', 'women' => '👗 نسائي', 'kids' => '🧒 أطفال', 'unisex' => '🧥 للجنسين'] as $value => $label)
                                    <label class="flex items-center text-sm cursor-pointer">
                                        <input type="radio" name="gender" value="{{ $value }}"
                                               {{ request('gender') == $value ? 'checked' : '' }}
                                               class="text-indigo-600">
                                        <span class="mr-2">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Price Range --}}
                        <div class="mb-5 pb-5 border-b">
                            <h4 class="font-medium text-gray-700 mb-3">💰 السعر</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}"
                                       placeholder="من" min="0"
                                       class="border-gray-300 rounded text-sm focus:border-indigo-500">
                                <input type="number" name="max_price" value="{{ request('max_price') }}"
                                       placeholder="إلى" min="0"
                                       class="border-gray-300 rounded text-sm focus:border-indigo-500">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                النطاق: {{ $priceRange['min'] }} - {{ $priceRange['max'] }} ₪
                            </p>
                        </div>

                        {{-- Brands --}}
                        <div class="mb-5 pb-5 border-b">
                            <h4 class="font-medium text-gray-700 mb-3">🏷️ الماركة</h4>
                            <select name="brand" class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500">
                                <option value="">كل الماركات</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Stores --}}
                        <div class="mb-5 pb-5 border-b">
                            <h4 class="font-medium text-gray-700 mb-3">🏪 المتجر</h4>
                            <select name="store" class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500">
                                <option value="">كل المتاجر</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ request('store') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Has Discount --}}
                        <div class="mb-5 pb-5 border-b">
                            <label class="flex items-center text-sm cursor-pointer">
                                <input type="checkbox" name="has_discount" value="1"
                                       {{ request('has_discount') ? 'checked' : '' }}
                                       class="rounded text-indigo-600">
                                <span class="mr-2">💰 عليها خصم فقط</span>
                            </label>
                        </div>

                        {{-- Buttons --}}
                        <div class="space-y-2">
                            <button type="submit"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-medium">
                                تطبيق الفلاتر
                            </button>
                            <a href="{{ route('products.index') }}"
                               class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg text-sm">
                                إعادة تعيين
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- Products Grid --}}
            <div class="lg:col-span-3">

                {{-- Sort Bar --}}
                <div class="bg-white rounded-lg shadow p-4 mb-4 flex justify-between items-center">
                    <p class="text-sm text-gray-600">
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
                        <label class="text-sm text-gray-600 hidden md:block">ترتيب حسب:</label>
                        <select name="sort" onchange="this.form.submit()"
                                class="border-gray-300 rounded-lg text-sm focus:border-indigo-500">
                            <option value="">الأحدث</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>الأرخص</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>الأغلى</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                        </select>
                    </form>
                </div>

                {{-- Products --}}
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow text-center py-16">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد منتجات</h3>
                        <p class="text-gray-500 mb-4">جرب تغيير الفلاتر أو البحث بكلمات مختلفة</p>
                        <a href="{{ route('products.index') }}"
                           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                            إعادة تعيين الفلاتر
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection