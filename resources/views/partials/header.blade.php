<header x-data="{ searchOpen: false, userMenu: false }" 
        class="bg-white border-b border-gray-200 sticky top-0 z-40">

    {{-- Main Header --}}
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between gap-6">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <span class="text-2xl">👕</span>
                <div class="hidden sm:block">
                    <h1 class="text-lg font-bold text-gray-900 leading-tight">متجر الملابس</h1>
                    <p class="text-xs text-gray-500">أزياء عصرية</p>
                </div>
            </a>

            {{-- Search (Desktop) --}}
            <div class="hidden md:flex flex-1 max-w-lg">
                <form action="{{ route('products.search') }}" method="GET" class="w-full relative">
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="ابحث عن منتج..."
                           class="w-full border border-gray-300 rounded-md px-4 py-2.5 pr-10 text-sm
                                  focus:outline-none focus:border-gray-500 focus:ring-1 focus:ring-gray-500
                                  placeholder:text-gray-400">
                    <button type="submit" 
                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        🔍
                    </button>
                </form>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">

                {{-- Search (Mobile) --}}
                <button @click="searchOpen = !searchOpen" 
                        class="md:hidden p-2 text-gray-600 hover:text-gray-900">
                    🔍
                </button>

                {{-- Wishlist --}}
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.wishlist') }}"
                           class="hidden md:block p-2 text-gray-600 hover:text-gray-900 relative">
                            ❤️
                            @php
                                $wishlistCount = auth()->user()->wishlists()->count();
                            @endphp
                            @if($wishlistCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 bg-gray-900 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">
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
                           class="p-2 text-gray-600 hover:text-gray-900 relative">
                            🛒
                            @php
                                $cart = auth()->user()->carts()->latest()->first();
                                $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
                            @endphp
                            @if($cartCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 bg-gray-900 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">
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
                                class="flex items-center gap-2 p-1.5 rounded-md hover:bg-gray-100 transition">
                            <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-sm font-bold">
                                {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                            </div>
                            <span class="hidden md:block text-sm text-gray-700">
                                {{ explode(' ', auth()->user()->full_name)[0] }}
                            </span>
                        </button>

                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute left-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg py-2 shadow-sm z-50">

                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="font-medium text-sm text-gray-900">{{ auth()->user()->full_name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    🛡️ لوحة المشرف
                                </a>
                            @elseif(auth()->user()->role === 'merchant')
                                <a href="{{ route('merchant.dashboard') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    🏪 لوحة التاجر
                                </a>
                            @else
                                <a href="{{ route('customer.dashboard') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    👤 حسابي
                                </a>
                                <a href="{{ route('customer.orders.index') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    📦 طلباتي
                                </a>
                                <a href="{{ route('customer.wishlist') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    ❤️ المفضلة
                                </a>
                            @endif

                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full text-right block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                                        🚪 تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden md:inline-block text-sm text-gray-600 hover:text-gray-900 px-3 py-2">
                        دخول
                    </a>
                    <a href="{{ route('register') }}"
                       class="btn-primary btn-sm">
                        إنشاء حساب
                    </a>
                @endauth

                {{-- Mobile Menu Button --}}
                <button @click="$dispatch('toggle-mobile-menu')"
                        class="md:hidden p-2 text-gray-600 hover:text-gray-900">
                    ☰
                </button>
            </div>
        </div>

        {{-- Mobile Search --}}
        <div x-show="searchOpen" x-cloak class="md:hidden mt-3">
            <form action="{{ route('products.search') }}" method="GET" class="relative">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="ابحث..."
                       class="w-full border border-gray-300 rounded-md px-4 py-2.5 pr-10 text-sm
                              focus:outline-none focus:border-gray-500">
                <button type="submit" 
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    🔍
                </button>
            </form>
        </div>
    </div>

    {{-- Nav Bar --}}
    <nav class="border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4">
            <ul class="hidden md:flex items-center gap-1 py-2 text-sm">
                <li>
                    <a href="{{ route('home') }}" 
                       class="px-3 py-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition
                              {{ request()->routeIs('home') ? 'text-gray-900 font-medium bg-gray-100' : '' }}">
                        الرئيسية
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}" 
                       class="px-3 py-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition
                              {{ request()->routeIs('products.*') ? 'text-gray-900 font-medium bg-gray-100' : '' }}">
                        كل المنتجات
                    </a>
                </li>
                <li>
                    <a href="{{ route('stores.index') }}" 
                       class="px-3 py-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition
                              {{ request()->routeIs('stores.*') ? 'text-gray-900 font-medium bg-gray-100' : '' }}">
                        المتاجر
                    </a>
                </li>
                <li>
                    <a href="{{ route('offers.index') }}" 
                       class="px-3 py-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition
                              {{ request()->routeIs('offers.*') ? 'text-gray-900 font-medium bg-gray-100' : '' }}">
                        العروض
                    </a>
                </li>
                <li class="text-gray-300">|</li>
                <li>
                    <a href="{{ route('products.index', ['gender' => 'men']) }}" 
                       class="px-3 py-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                        رجالي
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index', ['gender' => 'women']) }}" 
                       class="px-3 py-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                        نسائي
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index', ['gender' => 'kids']) }}" 
                       class="px-3 py-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                        أطفال
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>