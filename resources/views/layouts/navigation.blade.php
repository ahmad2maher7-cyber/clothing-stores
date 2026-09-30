<nav x-data="{ open: false, mobileOpen: false }" class="bg-white dark:bg-zinc-900 border-b border-stone-200 dark:border-stone-800 sticky top-0 z-40">

    {{-- ═══ Main Header ═══ --}}
    <div class="container-x">
        <div class="flex justify-between items-center h-16">

            {{-- ═══ Left: Logo + Nav Links ═══ --}}
            <div class="flex items-center gap-8">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 flex items-center justify-center bg-forest-900 dark:bg-gold-500 text-white dark:text-forest-950 rounded transition-transform group-hover:scale-105">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <span class="font-display font-bold text-ink dark:text-cream hidden sm:block">متجر الملابس</span>
                </a>

                {{-- Desktop Navigation Links --}}
                <div class="hidden md:flex items-center gap-1">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                <i class="fas fa-shield-alt"></i>
                                لوحة المشرف
                            </x-nav-link>
                        @elseif(auth()->user()->role === 'merchant')
                            <x-nav-link :href="route('merchant.dashboard')" :active="request()->routeIs('merchant.*')">
                                <i class="fas fa-store"></i>
                                لوحة التاجر
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.*')">
                                <i class="fas fa-user"></i>
                                حسابي
                            </x-nav-link>
                            <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                                <i class="fas fa-home"></i>
                                الرئيسية
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- ═══ Right: User Menu + Dark Mode ═══ --}}
            <div class="hidden md:flex items-center gap-2">

                {{-- Dark Mode Toggle --}}
                <button onclick="toggleDarkMode()"
                        title="تبديل الوضع الليلي"
                        class="w-9 h-9 flex items-center justify-center text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400 hover:bg-stone-100 dark:hover:bg-zinc-800 rounded transition-colors">
                    <i class="fas fa-sun text-lg hidden dark:block"></i>
                    <i class="fas fa-moon text-lg block dark:hidden"></i>
                </button>

                {{-- User Dropdown --}}
                @auth
                    <x-dropdown align="left" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 pl-2 pr-3 h-10 rounded hover:bg-stone-100 dark:hover:bg-zinc-800 transition-colors">
                                <div class="w-8 h-8 flex items-center justify-center bg-forest-900 dark:bg-gold-500 text-white dark:text-forest-950 rounded-full text-xs font-bold">
                                    {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-ink dark:text-cream max-w-[100px] truncate">
                                    {{ auth()->user()->full_name }}
                                </span>
                                <i class="fas fa-chevron-down text-xs text-ink-muted transition-transform" 
                                   :class="open ? 'rotate-180' : ''"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            {{-- User Info --}}
                            <div class="px-4 py-3 border-b border-stone-100 dark:border-stone-800">
                                <p class="font-medium text-sm text-ink dark:text-cream truncate">{{ auth()->user()->full_name }}</p>
                                <p class="text-xs text-ink-muted dark:text-cream/50 truncate mt-0.5">{{ auth()->user()->email }}</p>
                            </div>

                            {{-- Links --}}
                            <div class="py-1.5">
                                @if(auth()->user()->role === 'merchant')
                                    <x-dropdown-link :href="route('merchant.settings.index')">
                                        <i class="fas fa-cog w-4 text-center"></i>
                                        إعدادات المتجر
                                    </x-dropdown-link>
                                @endif

                                <x-dropdown-link :href="route('profile.edit')">
                                    <i class="fas fa-user w-4 text-center"></i>
                                    الملف الشخصي
                                </x-dropdown-link>
                            </div>

                            {{-- Logout --}}
                            <div class="border-t border-stone-100 dark:border-stone-800 pt-1.5">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        <i class="fas fa-sign-out-alt text-red-600 dark:text-red-400 w-4 text-center"></i>
                                        <span class="text-red-600 dark:text-red-400">تسجيل الخروج</span>
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            {{-- ═══ Mobile Menu Button ═══ --}}
            <div class="flex md:hidden items-center gap-2">

                {{-- Dark Mode (Mobile) --}}
                <button onclick="toggleDarkMode()"
                        class="w-9 h-9 flex items-center justify-center text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400 rounded transition-colors">
                    <i class="fas fa-sun text-lg hidden dark:block"></i>
                    <i class="fas fa-moon text-lg block dark:hidden"></i>
                </button>

                {{-- Hamburger --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="w-9 h-9 flex items-center justify-center text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400 rounded transition-colors">
                    <i class="fas fa-bars text-lg" x-show="!mobileOpen"></i>
                    <i class="fas fa-times text-lg" x-show="mobileOpen" x-cloak></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ═══ Mobile Menu ═══ --}}
    <div x-show="mobileOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="md:hidden border-t border-stone-200 dark:border-stone-800">

        @auth
            <div class="p-4 bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-forest-900 dark:bg-gold-500 text-white dark:text-forest-950 flex items-center justify-center font-bold">
                        {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-sm text-ink dark:text-cream truncate">{{ auth()->user()->full_name }}</p>
                        <p class="text-xs text-ink-muted dark:text-cream/50 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        @endauth

        <div class="py-2">
            @auth
                @if(auth()->user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        <i class="fas fa-shield-alt w-5 text-center"></i>
                        لوحة المشرف
                    </x-responsive-nav-link>
                @elseif(auth()->user()->role === 'merchant')
                    <x-responsive-nav-link :href="route('merchant.dashboard')" :active="request()->routeIs('merchant.*')">
                        <i class="fas fa-store w-5 text-center"></i>
                        لوحة التاجر
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.*')">
                        <i class="fas fa-user w-5 text-center"></i>
                        حسابي
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
            <div class="border-t border-stone-200 dark:border-stone-800 pt-2 pb-4 px-4 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="fas fa-user w-5 text-center"></i>
                    الملف الشخصي
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt text-red-600 dark:text-red-400 w-5 text-center"></i>
                        <span class="text-red-600 dark:text-red-400">تسجيل الخروج</span>
                    </x-responsive-nav-link>
                </form>
            </div>
        @endauth
    </div>
</nav>