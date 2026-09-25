<div x-data="{ open: false }" 
     @toggle-mobile-menu.window="open = !open"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 md:hidden">

    {{-- Backdrop --}}
    <div @click="open = false" 
         class="absolute inset-0 bg-black bg-opacity-40"></div>

    {{-- Menu --}}
    <div class="absolute right-0 top-0 h-full w-80 max-w-[85%] bg-white border-l border-gray-200 flex flex-col">
        
        {{-- Header --}}
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="text-xl">👕</span>
                <span class="font-bold text-gray-900">متجر الملابس</span>
            </div>
            <button @click="open = false" class="text-2xl text-gray-400 hover:text-gray-600">×</button>
        </div>

        {{-- User Info --}}
        @auth
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold">
                        {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm truncate">{{ auth()->user()->full_name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        @endauth

        {{-- Links --}}
        <nav class="flex-1 overflow-y-auto py-2">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                <span>🏠</span> الرئيسية
            </a>
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                <span>🛍️</span> كل المنتجات
            </a>
            <a href="{{ route('stores.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                <span>🏪</span> المتاجر
            </a>
            <a href="{{ route('offers.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                <span>🔥</span> العروض
            </a>

            <div class="border-t border-gray-200 my-2"></div>
            <p class="px-4 py-1 text-xs text-gray-400 font-medium">تسوق حسب</p>

            <a href="{{ route('products.index', ['gender' => 'men']) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                <span>👔</span> رجالي
            </a>
            <a href="{{ route('products.index', ['gender' => 'women']) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                <span>👗</span> نسائي
            </a>
            <a href="{{ route('products.index', ['gender' => 'kids']) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                <span>🧒</span> أطفال
            </a>

            @auth
                @if(auth()->user()->role === 'customer')
                    <div class="border-t border-gray-200 my-2"></div>

                    <a href="{{ route('customer.wishlist') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <span>❤️</span> المفضلة
                    </a>
                    <a href="{{ route('cart.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <span>🛒</span> السلة
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <span>📦</span> طلباتي
                    </a>
                @endif
            @endauth
        </nav>

        {{-- Footer --}}
        <div class="border-t border-gray-200 p-4">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center text-sm text-gray-600 hover:text-gray-900 py-2 rounded-md border border-gray-300 hover:bg-gray-50 transition">
                        🚪 تسجيل الخروج
                    </button>
                </form>
            @else
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="block text-center text-sm text-gray-700 py-2.5 rounded-md border border-gray-300 hover:bg-gray-50 transition">
                        دخول
                    </a>
                    <a href="{{ route('register') }}" class="block text-center text-sm bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-md transition">
                        إنشاء حساب
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>