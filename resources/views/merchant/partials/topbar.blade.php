<header class="bg-white dark:bg-zinc-900 border-b border-stone-200 dark:border-stone-800 sticky top-0 z-30">
    <div class="flex items-center justify-between h-16 px-4 lg:px-6">

        {{-- Right: Hamburger + Title --}}
        <div class="flex items-center gap-3">
            {{-- ═══ زر ☰ (يظهر فقط على الموبايل) ═══ --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    type="button"
                    class="lg:hidden w-10 h-10 flex items-center justify-center text-ink dark:text-cream hover:bg-stone-100 dark:hover:bg-zinc-800 rounded transition-colors"
                    aria-label="فتح القائمة">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>

            {{-- Title --}}
            <div>
                <h1 class="font-display text-base md:text-lg font-bold text-ink dark:text-cream">
                    @yield('page-title', 'لوحة التاجر')
                </h1>
                <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50 hidden sm:block">
                    {{ $store->name ?? 'متجري' }}
                </p>
            </div>
        </div>

        {{-- Left: Actions --}}
        <div class="flex items-center gap-2">

            {{-- Visit Store --}}
            @if(isset($store))
                <a href="{{ route('stores.show', $store) }}" target="_blank"
                   class="hidden md:flex items-center gap-2 h-9 px-3 text-xs font-medium text-ink-soft dark:text-cream/70 hover:text-forest-700 dark:hover:text-gold-400 hover:bg-stone-100 dark:hover:bg-zinc-800 rounded transition-colors">
                    <i class="fa-solid fa-eye"></i>
                    <span>زيارة المتجر</span>
                </a>
            @endif

            {{-- Dark Mode --}}
            <button onclick="toggleDarkMode()"
                    type="button"
                    class="w-9 h-9 flex items-center justify-center text-ink-muted hover:text-forest-700 dark:text-cream/60 dark:hover:text-gold-400 hover:bg-stone-100 dark:hover:bg-zinc-800 rounded transition-colors">
                <i class="fa-solid fa-sun text-sm hidden dark:block"></i>
                <i class="fa-solid fa-moon text-sm block dark:hidden"></i>
            </button>

            {{-- User Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        type="button"
                        class="flex items-center gap-2 h-9 pl-2 pr-1 rounded hover:bg-stone-100 dark:hover:bg-zinc-800 transition-colors">
                    <div class="w-7 h-7 flex items-center justify-center bg-forest-900 dark:bg-gold-500 text-white dark:text-forest-950 rounded-full text-xs font-bold">
                        {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] text-ink-muted hidden md:block"></i>
                </button>

                <div x-show="open" @click.outside="open = false" x-cloak
                     class="absolute left-0 mt-2 w-56 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg shadow-lg py-1 z-50">

                    <div class="px-4 py-2.5 border-b border-stone-100 dark:border-stone-800">
                        <p class="font-medium text-sm text-ink dark:text-cream truncate">
                            {{ auth()->user()->full_name }}
                        </p>
                        <p class="text-xs text-ink-muted dark:text-cream/50 truncate">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    @if(isset($store))
                        <a href="{{ route('merchant.settings.index') }}"
                           class="flex items-center gap-2 px-4 py-2 text-sm text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-800 transition">
                            <i class="fa-solid fa-gear text-xs"></i>
                            الإعدادات
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 transition">
                            <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>