<header x-data="{ searchOpen: false, userMenu: false, notificationsOpen: false }" 
        class="bg-white dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800 sticky top-0 z-40">

    {{-- Main Header --}}
    <div class="container-x py-4">
        <div class="flex items-center justify-between gap-6">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-900 dark:bg-gold-500 text-white dark:text-ink rounded transition-transform group-hover:scale-105">
                    <i class="fa-solid fa-shirt text-lg"></i>
                </div>
                <div class="hidden sm:block">
                    <h1 class="font-display text-lg font-bold text-ink dark:text-cream leading-tight">متجر الملابس</h1>
                    <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50">أزياء عصرية</p>
                </div>
            </a>

            {{-- Search --}}
            <div class="hidden md:flex flex-1 max-w-xl">
                <form action="{{ route('products.search') }}" method="GET" class="w-full relative">
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="ابحث عن منتج..."
                           class="w-full h-11 pl-11 pr-4 text-sm bg-stone-50 dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 focus:border-forest-600 dark:focus:border-gold-400 focus:ring-0 transition-colors">
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-ink-muted hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-1">

                {{-- Dark Mode --}}
                <button onclick="toggleDarkMode()" 
                        class="hidden md:flex w-10 h-10 items-center justify-center text-ink-muted hover:text-forest-700 dark:text-cream/60 dark:hover:text-gold-400 hover:bg-stone-100 dark:hover:bg-zinc-900 rounded transition-colors">
                    <i class="fa-solid fa-sun text-lg hidden dark:block"></i>
                    <i class="fa-solid fa-moon text-lg block dark:hidden"></i>
                </button>

                {{-- Notifications Bell --}}
                @include('partials.notifications-bell')

                {{-- User Menu --}}
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
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-800 transition-colors">
                                        <i class="fa-solid fa-shield-halved w-4 text-center"></i>
                                        لوحة المشرف
                                    </a>
                                @elseif(auth()->user()->role === 'merchant')
                                    <a href="{{ route('merchant.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-800 transition-colors">
                                        <i class="fa-solid fa-store w-4 text-center"></i>
                                        لوحة التاجر
                                    </a>
                                @else
                                    <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-800 transition-colors">
                                        <i class="fa-solid fa-user w-4 text-center"></i>
                                        حسابي
                                    </a>
                                    <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-800 transition-colors">
                                        <i class="fa-solid fa-box w-4 text-center"></i>
                                        طلباتي
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
                @endauth
            </div>
        </div>
    </div>
</header>