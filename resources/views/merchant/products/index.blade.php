@extends('merchant.layouts.app')

@section('title', 'المنتجات')
@section('page-title', 'إدارة المنتجات')

@section('content')

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">المنتجات</h2>
            <p class="text-gray-500 text-sm">إجمالي: {{ $products->total() }} منتج</p>
        </div>
        <a href="{{ route('merchant.products.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition">
            <span>➕</span>
            <span>إضافة منتج</span>
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 ابحث باسم المنتج..."
                   class="border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">

            <select name="category_id" class="border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">كل التصنيفات</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">كل الحالات</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>نفد المخزون</option>
            </select>

            <div class="flex space-x-2 space-x-reverse">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex-1">
                    بحث
                </button>
                <a href="{{ route('merchant.products.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                    إعادة
                </a>
            </div>
        </form>
    </div>

    {{-- Products Grid --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden group">
                    {{-- Image --}}
                    <div class="relative aspect-w-1 aspect-h-1 h-48 bg-gray-100">
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_url) }}"
                                 class="w-full h-full object-cover" alt="{{ $product->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-5xl">👕</div>
                        @endif

                        {{-- Status Badge --}}
                        <div class="absolute top-2 right-2">
                            @if($product->status === 'active')
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">✅ نشط</span>
                            @elseif($product->status === 'draft')
                                <span class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">📝 مسودة</span>
                            @else
                                <span class="bg-red-500 text-white px-2 py-1 rounded text-xs">⛔ نفد</span>
                            @endif
                        </div>

                        {{-- Gender Badge --}}
                        <div class="absolute top-2 left-2">
                            <span class="bg-indigo-500 text-white px-2 py-1 rounded text-xs">
                                @switch($product->gender)
                                    @case('men') 👔 رجالي @break
                                    @case('women') 👗 نسائي @break
                                    @case('kids') 🧒 أطفال @break
                                    @default 🧥 للجنسين
                                @endswitch
                            </span>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <h3 class="font-bold text-gray-800 mb-1 truncate">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500 mb-2">{{ $product->category?->name ?? '—' }}</p>

                        <div class="flex justify-between items-center mb-3">
                            <div>
                                @if($product->discount_price)
                                    <span class="text-lg font-bold text-red-600">{{ $product->discount_price }} ₪</span>
                                    <span class="text-xs text-gray-400 line-through mr-1">{{ $product->base_price }} ₪</span>
                                @else
                                    <span class="text-lg font-bold text-indigo-600">{{ $product->base_price }} ₪</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $product->variants_count }} متغير
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex space-x-2 space-x-reverse">
                            <a href="{{ route('merchant.products.show', $product) }}"
                               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center py-2 rounded text-sm">
                                👁️ عرض
                            </a>
                            <a href="{{ route('merchant.products.edit', $product) }}"
                               class="flex-1 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-center py-2 rounded text-sm">
                                ✏️ تعديل
                            </a>
                            <form action="{{ route('merchant.products.destroy', $product) }}"
                                  method="POST"
                                  onsubmit="return confirm('هل أنت متأكد من حذف المنتج؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded text-sm">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow text-center py-16">
            <div class="text-6xl mb-4">📦</div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد منتجات بعد</h3>
            <p class="text-gray-500 mb-4">ابدأ بإضافة أول منتج لمتجرك</p>
            <a href="{{ route('merchant.products.create') }}"
               class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                ➕ إضافة منتج
            </a>
        </div>
    @endif

@endsection