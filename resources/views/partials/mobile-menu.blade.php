<div x-data="{ open: false }" 
     @toggle-mobile-menu.window="open = !open"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 md:hidden">

    {{-- Backdrop --}}
    <div @click="open = false" 
         class="absolute inset-0 bg-black bg-opacity-50"></div>

    {{-- Menu --}}
    <div class="absolute right-0 top-0 h-full w-80 max-w-[85%] bg-white shadow-xl flex flex-col">
        
        {{-- Header --}}
        <div class="p-4 border-b flex justify-between items-center">
            <div class="flex items-center space-x-2 space-x-reverse">
                <span class="text-2xl">👕</span>
                <span class="font-bold text-indigo-700">متجر الملابس</span>
            </div>
            <button @click="open = false" class="text-2xl text-gray-500">×</button>
        </div>

        {{-- User Info --}}
        @auth
            <div class="p-4 bg-indigo-50 border-b">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-lg">
                        {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium truncate">{{ auth()->user()->full_name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        @endauth

        {{-- Links --}}
        <nav class="flex-1 overflow-y-auto py-2">
            <a href="{{ route('home') }}" class="flex items-center px-4 py-3 hover:bg-gray-50">
                <span class="text-xl ml-3">🏠</span> الرئيسية
            </a>
            <a href="{{ route('products.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-50">
                <span class="text-xl ml-3">🛍️</span> كل المنتجات
            </a>
            <a href="{{ route('stores.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-50">
                <span class="text-xl ml-3">🏪</span> المتاجر
            </a>
            <a href="{{ route('offers.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-50 text-red-600">
                <span class="text-xl ml-3">🔥</span> العروض
            </a>

            <div class="border-t my-2"></div>

            <p class="px-4 py-2 text-xs text-gray-500 font-medium">تسوق حسب</p>

            <a href="{{ route('products.index', ['gender' => 'men']) }}" class="flex items-center px-4 py-3 hover:bg-gray-50">
                <span class="text-xl ml-3">👔</span> رجالي
            </a>
            <a href="{{ route('products.index', ['gender' => 'women']) }}" class="flex items-center px-4 py-3 hover:bg-gray-50">
                <span class="text-xl ml-3">👗</span> نسائي
            </a>
            <a href="{{ route('products.index', ['gender' => 'kids']) }}" class="flex items-center px-4 py-3 hover:bg-gray-50">
                <span class="text-xl ml-3">🧒</span> أطفال
            </a>

            @auth
                @if(auth()->user()->role === 'customer')
                    <div class="border-t my-2"></div>

                    <a href="{{ route('customer.wishlist') }}" class="flex items-center px-4 py-3 hover:bg-gray-50">
                        <span class="text-xl ml-3">❤️</span> المفضلة
                    </a>
                    <a href="{{ route('cart.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-50">
                        <span class="text-xl ml-3">🛒</span> السلة
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-50">
                        <span class="text-xl ml-3">📦</span> طلباتي
                    </a>
                @endif
            @endauth
        </nav>

        {{-- Footer --}}
        <div class="border-t p-4">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded-lg text-sm">
                        🚪 تسجيل الخروج
                    </button>
                </form>
            @else
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="block text-center bg-gray-100 hover:bg-gray-200 py-2 rounded-lg text-sm">
                        دخول
                    </a>
                    <a href="{{ route('register') }}" class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg text-sm">
                        إنشاء حساب
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>