@extends('customer.layouts.app')

@section('title', 'المفضلة')

@section('content')

    <div class="max-w-7xl mx-auto px-4 py-8">

        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">الرئيسية</a>
            <span class="mx-2">›</span>
            <span class="text-gray-800">المفضلة</span>
        </nav>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-1">❤️ قائمة المفضلة</h1>
            <p class="text-gray-500">{{ $wishlists->count() }} منتج</p>
        </div>

        @if($wishlists->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($wishlists as $wishlist)
                    <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition group relative">
                        {{-- Remove Button --}}
                        <form action="{{ route('customer.wishlist.remove', $wishlist) }}" method="POST"
                              class="absolute top-2 left-2 z-10">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-9 h-9 bg-white rounded-full shadow flex items-center justify-center hover:bg-red-50 transition text-red-500">
                                ✖️
                            </button>
                        </form>

                        @include('partials.product-card', ['product' => $wishlist->product])
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow text-center py-16">
                <div class="text-7xl mb-4">💔</div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">المفضلة فارغة</h3>
                <p class="text-gray-500 mb-6">أضف منتجات تحبها لترجع إليها لاحقاً</p>
                <a href="{{ route('products.index') }}"
                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-bold">
                    🛍️ تسوق الآن
                </a>
            </div>
        @endif
    </div>

@endsection
