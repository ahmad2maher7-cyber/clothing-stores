<header x-data="{ searchOpen: false, userMenu: false, mobileMenuOpen: false }" 
        class="bg-white dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800 sticky top-0 z-40">

    {{-- ════════════════════════════════════
         TOP BAR
    ════════════════════════════════════ --}}
    <div class="hidden md:block bg-forest-900 dark:bg-forest-950 text-white text-xs">
        <div class="container-x flex items-center justify-between py-2.5">

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-truck-fast text-gold-400"></i>
                <span class="font-medium">توصيل مجاني للطلبات فوق 200 ₪</span>
            </div>

            <div class="flex items-center gap-6">
                <a href="{{ route('pages.contact') }}" class="hover:text-gold-400 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-headset"></i>
                    تواصل معنا
                </a>
                <span class="opacity-30">|</span>
                <a href="{{ route('register') }}?role=merchant" class="hover:text-gold-400 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-store"></i>
                    كن تاجراً
                </a>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════
         MAIN HEADER
    ════════════════════════════════════ --}}
    <div class="container-x py-4">
        <div class="flex items-center justify-between gap-6">

            {{-- ─── Logo ─── --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-900 dark:bg-gold-500 text-white dark:text-ink rounded transition-transform group-hover:scale-105">
                    <i class="fa-solid fa-shirt text-lg"></i>
                </div>
                <div class="hidden sm:block">
                    <h1 class="font-display text-lg font-bold text-ink dark:text-cream leading-tight">متجر الملابس</h1>
                    <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50">أزياء عصرية</p>
                </div>
            </a>

            {{-- ─── Search (Desktop) ─── --}}
            <div class="hidden md:flex flex-1 max-w-xl">
                <form action="{{ route('products.search') }}" method="GET" class="w-full relative">
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="ابحث عن منتج، ماركة، متجر..."
                           class="w-full h-11 pl-11 pr-4 text-sm bg-stone-50 dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 focus:border-forest-600 dark:focus:border-gold-400 focus:bg-white dark:focus:bg-zinc-900 focus:ring-0 transition-colors">
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-ink-muted hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>

            {{-- ─── Actions ─── --}}
            <div class="flex items-center gap-1">

                {{-- Dark Mode Toggle --}}
                <button onclick="toggleDarkMode()" 
                        class="hidden md:flex w-10 h-10 items-center justify-center text-ink-muted hover:text-forest-700 dark:text-cream/60 dark:hover:text-gold-400 hover:bg-stone-100 dark:hover:bg-zinc-900 rounded transition-colors"
                        title="تبديل الوضع الليلي">
                    <i class="fa-solid fa-sun text-lg hidden dark:block"></i>
                    <i class="fa-solid fa-moon text-lg block dark:hidden"></i>
                </button>

                {{-- Search (Mobile) --}}
                <button @click="searchOpen = !searchOpen" 
                        class="md:hidden w-10 h-10 flex items-center justify-center text-ink-muted hover:text-forest-700 rounded transition-colors">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

                {{-- Wishlist --}}
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.wishlist') }}"
                           class="hidden md:flex w-10 h-10 items-center justify-center text-ink-muted hover:text-forest-700 dark:text-cream/60 dark:hover:text-gold-400 hover:bg-stone-100 dark:hover:bg-zinc-900 rounded transition-colors relative">
                            <i class="fa-solid fa-heart"></i>
                            @php $wishlistCount = auth()->user()->wishlists()->count(); @endphp
                            @if($wishlistCount > 0)
                                <span data-wishlist-count
                                      class="absolute -top-0.5 -left-0.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center text-[10px] font-bold text-white bg-forest-700 dark:bg-gold-500 dark:text-ink rounded-full">
                                    {{ $wishlistCount > 9 ? '9+' : $wishlistCount }}
                                </span>
                            @endif
                        </a>
                    @endif
                @endauth

                {{-- Cart --}}
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('cart.index') }}"
                           class="w-10 h-10 flex items-center justify-center text-ink-muted hover:text-forest-700 dark:text-cream/60 dark:hover:text-gold-400 hover:bg-stone-100 dark:hover:bg-zinc-900 rounded transition-colors relative">
                            <i class="fa-solid fa-cart-shopping"></i>
                            @php
                                $cart = auth()->user()->carts()->latest()->first();
                                $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
                            @endphp
                            @if($cartCount > 0)
                                <span data-cart-count
                                      class="absolute -top-0.5 -left-0.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center text-[10px] font-bold text-white bg-forest-700 dark:bg-gold-500 dark:text-ink rounded-full">
                                    {{ $cartCount > 9 ? '9+' : $cartCount }}
                                </span>
                            @endif
                        </a>
                    @endif
                @endauth

                {{-- ─── User Menu ─── --}}
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-2 h-10 pl-2 pr-3 rounded hover:bg-stone-100 dark:hover:bg-zinc-900 transition-colors">
                            <div class="w-8 h-8 flex items-center justify-center bg-forest-900 dark:bg-gold-500 text-white dark:text-ink rounded-full text-xs font-bold">
                                {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-ink dark:text-cream max-w-[80px] truncate">
                                {{ explode(' ', auth()->user()->full_name)[0] }}
                            </span>
                            <i class="fa-solid fa-chevron-down text-xs text-ink-muted hidden md:block transition-transform" 
                               :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="open" @click.outside="open = false" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute left-0 mt-2 w-64 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded shadow-lg py-1.5 z-50">

                            <div class="px-4 py-3 border-b border-stone-100 dark:border-stone-800">
                                <p class="font-medium text-sm text-ink dark:text-cream truncate">
                                    {{ auth()->user()->full_name }}
                                </p>
                                <p class="text-xs text-ink-muted dark:text-cream/50 truncate mt-0.5">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>

                            <div class="py-1.5">
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                                        <i class="fa-solid fa-shield-halved w-4 text-center"></i>
                                        لوحة المشرف
                                    </a>
                                @elseif(auth()->user()->role === 'merchant')
                                    <a href="{{ route('merchant.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                                        <i class="fa-solid fa-store w-4 text-center"></i>
                                        لوحة التاجر
                                    </a>
                                @else
                                    <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                                        <i class="fa-solid fa-user w-4 text-center"></i>
                                        حسابي
                                    </a>
                                    <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                                        <i class="fa-solid fa-box w-4 text-center"></i>
                                        طلباتي
                                    </a>
                                    <a href="{{ route('customer.wishlist') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                                        <i class="fa-solid fa-heart w-4 text-center"></i>
                                        المفضلة
                                    </a>
                                @endif
                            </div>

                            <div class="border-t border-stone-100 dark:border-stone-800"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors">
                                    <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       class="hidden md:flex items-center gap-2 h-10 px-4 text-sm font-medium text-ink-soft dark:text-cream/80 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        دخول
                    </a>
                    <a href="{{ route('register') }}" 
                       class="hidden md:inline-flex items-center justify-center gap-2 h-10 px-5 text-xs font-semibold tracking-widest uppercase bg-forest-900 dark:bg-gold-500 text-white dark:text-ink hover:bg-forest-800 dark:hover:bg-gold-400 rounded transition-colors">
                        <i class="fa-solid fa-user-plus"></i>
                        إنشاء حساب
                    </a>
                @endauth

                {{-- Mobile Menu --}}
                <button @click="$dispatch('toggle-mobile-menu')"
                        class="md:hidden w-10 h-10 flex items-center justify-center text-ink-muted hover:text-forest-700 rounded transition-colors">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
            </div>
        </div>

        {{-- ─── Mobile Search ─── --}}
        <div x-show="searchOpen" x-cloak x-transition
             class="md:hidden mt-4 pb-2">
            <form action="{{ route('products.search') }}" method="GET" class="relative">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="ابحث..."
                       class="w-full h-11 pl-11 pr-4 text-sm bg-stone-50 dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded focus:border-forest-600 dark:focus:border-gold-400 focus:ring-0">
                <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-ink-muted">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════
         NAV BAR (Desktop)
    ════════════════════════════════════ --}}
    <nav class="hidden md:block border-t border-stone-200 dark:border-stone-800">
        <div class="container-x">
            <ul class="flex items-center gap-1 py-1.5">
                @php
                    $navItems = [
                        ['route' => 'home',            'label' => 'الرئيسية',  'icon' => 'fa-house'],
                        ['route' => 'products.index',  'label' => 'المنتجات',  'icon' => 'fa-shirt'],
                        ['route' => 'stores.index',    'label' => 'المتاجر',   'icon' => 'fa-store'],
                        ['route' => 'offers.index',    'label' => 'العروض',    'icon' => 'fa-fire', 'accent' => true],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php
                        $isActive = request()->routeIs($item['route']) || request()->routeIs(explode('.', $item['route'])[0] . '.*');
                    @endphp
                    <li>
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded transition-colors
                                  {{ $isActive 
                                     ? 'bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400' 
                                     : 'text-ink-soft dark:text-cream/70 hover:text-forest-700 dark:hover:text-gold-400 hover:bg-stone-50 dark:hover:bg-zinc-900' }}
                                  {{ isset($item['accent']) ? 'text-red-600 dark:text-red-400 hover:text-red-700' : '' }}">
                            <i class="fa-solid {{ $item['icon'] }}"></i>
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach

                <li class="mx-2 h-5 w-px bg-stone-200 dark:bg-stone-800"></li>

                @foreach(['men' => 'رجالي', 'women' => 'نسائي', 'kids' => 'أطفال'] as $gender => $label)
                    <li>
                        <a href="{{ route('products.index', ['gender' => $gender]) }}"
                           class="px-4 py-2 text-sm font-medium text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400 rounded transition-colors">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
</header>