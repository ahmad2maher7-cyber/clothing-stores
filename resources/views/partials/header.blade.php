<header x-data="{ searchOpen: false, userMenu: false }" 
        class="bg-white shadow-sm sticky top-0 z-40">

    {{-- Top Bar --}}
    <div class="bg-indigo-700 text-white text-xs py-2">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <span>🚚 توصيل مجاني للطلبات فوق 200 ₪</span>
            <div class="hidden md:flex items-center space-x-4 space-x-reverse">
                <a href="#" class="hover:text-yellow-300">📞 تواصل معنا</a>
                <span>|</span>
                <a href="#" class="hover:text-yellow-300">💰 كن تاجراً</a>
            </div>
        </div>
    </div>

    {{-- Main Header --}}
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between gap-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center space-x-2 space-x-reverse shrink-0">
                <span class="text-3xl">👕</span>
                <div class="hidden sm:block">
                    <h1 class="text-xl font-bold text-indigo-700">متجر الملابس</h1>
                    <p class="text-xs text-gray-500">أفضل الأزياء بأفضل الأسعار</p>
                </div>
            </a>

            {{-- Search (Desktop) --}}
            <div class="hidden md:flex flex-1 max-w-xl">
                <form action="{{ route('products.search') }}" method="GET" class="w-full flex">
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="ابحث عن ملابس، ماركات، متاجر..."
                           class="flex-1 border-2 border-indigo-500 rounded-r-lg px-4 py-2 focus:outline-none focus:border-indigo-700">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 rounded-l-lg transition">
                        🔍
                    </button>
                </form>
            </div>

            {{-- Actions --}}
            <div class="flex items-center space-x-2 space-x-reverse">

                {{-- Search (Mobile) --}}
                <button @click="searchOpen = !searchOpen" 
                        class="md:hidden text-gray-600 hover:text-indigo-600 p-2">
                    🔍
                </button>

                {{-- Wishlist --}}
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.wishlist') }}"
                           class="hidden md:block text-gray-600 hover:text-red-500 p-2 relative">
                            ❤️
                            @php
                                $wishlistCount = auth()->user()->wishlists()->count();
                            @endphp
                            @if($wishlistCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                                    {{ $wishlistCount }}
                                </span>
                            @endif
                        </a>
                    @endif
                @endauth

                {{-- Cart --}}
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('cart.index') }}"
                           class="text-gray-600 hover:text-indigo-600 p-2 relative">
                            🛒
                            @php
                                $cart = auth()->user()->carts()->latest()->first();
                                $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
                                @endphp
                            @if($cartCount > 0)
                                <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    @endif
                @endauth

                {{-- User Menu --}}
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center space-x-2 space-x-reverse text-gray-600 hover:text-indigo-600 p-2">
                            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">
                                {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                            </div>
                            <span class="hidden md:block text-sm">{{ explode(' ', auth()->user()->full_name)[0] }}</span>
                            <span class="text-xs">▼</span>
                        </button>

                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50">
                            <div class="px-4 py-2 border-b">
                                <p class="font-medium text-sm">{{ auth()->user()->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>

                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    🛡️ لوحة المشرف
                                </a>
                            @elseif(auth()->user()->role === 'merchant')
                                <a href="{{ route('merchant.dashboard') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    🏪 لوحة التاجر
                                </a>
                            @else
                                <a href="{{ route('customer.dashboard') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    👤 حسابي
                                </a>
                                <a href="{{ route('customer.orders.index') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    📦 طلباتي
                                </a>
                                <a href="{{ route('customer.wishlist') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    ❤️ المفضلة
                                </a>
                            @endif

                            <hr class="my-1">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-right block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    🚪 تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden md:block text-gray-700 hover:text-indigo-600 text-sm font-medium px-3">
                        دخول
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        إنشاء حساب
                    </a>
                @endauth

                {{-- Mobile Menu Button --}}
                <button @click="$dispatch('toggle-mobile-menu')"
                        class="md:hidden text-gray-600 hover:text-indigo-600 p-2">
                    ☰
                </button>
            </div>
        </div>

        {{-- Mobile Search --}}
        <div x-show="searchOpen" x-cloak class="md:hidden mt-3">
            <form action="{{ route('products.search') }}" method="GET" class="flex">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="ابحث..."
                       class="flex-1 border-2 border-indigo-500 rounded-r-lg px-4 py-2 text-sm focus:outline-none">
                <button type="submit"
                        class="bg-indigo-600 text-white px-4 rounded-l-lg">
                    🔍
                </button>
            </form>
        </div>
    </div>

    {{-- Nav Bar (Desktop) --}}
    <nav class="border-t bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <ul class="hidden md:flex items-center space-x-6 space-x-reverse py-3 text-sm">
                <li>
                    <a href="{{ route('home') }}" 
                       class="text-gray-700 hover:text-indigo-600 font-medium">
                        🏠 الرئيسية
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}" 
                       class="text-gray-700 hover:text-indigo-600 font-medium">
                        🛍️ كل المنتجات
                    </a>
                </li>
                <li>
                    <a href="{{ route('stores.index') }}" 
                       class="text-gray-700 hover:text-indigo-600 font-medium">
                        🏪 المتاجر
                    </a>
                </li>
                <li>
                    <a href="{{ route('offers.index') }}" 
                       class="text-red-600 hover:text-red-700 font-medium">
                        🔥 العروض
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index', ['gender' => 'men']) }}" 
                       class="text-gray-700 hover:text-indigo-600">👔 رجالي</a>
                </li>
                <li>
                    <a href="{{ route('products.index', ['gender' => 'women']) }}" 
                       class="text-gray-700 hover:text-indigo-600">👗 نسائي</a>
                </li>
                <li>
                    <a href="{{ route('products.index', ['gender' => 'kids']) }}" 
                       class="text-gray-700 hover:text-indigo-600">🧒 أطفال</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
