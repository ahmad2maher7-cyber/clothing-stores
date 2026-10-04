<header class="h-16 bg-white dark:bg-zinc-900 border-b border-stone-200 dark:border-stone-800 flex items-center justify-between px-6">

    {{-- Right --}}
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" 
                class="text-ink-muted hover:text-forest-700 dark:text-cream/60 dark:hover:text-gold-400 transition">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        <h1 class="text-base font-medium text-ink dark:text-cream">
            @yield('page-title', 'لوحة المشرف')
        </h1>
    </div>

    {{-- Left --}}
    <div class="flex items-center gap-2">

        <a href="{{ route('home') }}" target="_blank" 
           class="hidden md:flex items-center gap-2 text-sm text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400 px-3 py-2 rounded hover:bg-stone-100 dark:hover:bg-zinc-800 transition">
            <i class="fa-solid fa-eye"></i>
            <span>زيارة الموقع</span>
        </a>

        <div class="hidden md:block w-px h-6 bg-stone-200 dark:bg-stone-800"></div>

        {{-- Notifications Bell --}}
        @include('partials.notifications-bell')

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" 
                    class="flex items-center gap-2 p-1 rounded hover:bg-stone-100 dark:hover:bg-zinc-800 transition">
                <div class="w-8 h-8 rounded-full bg-forest-900 dark:bg-gold-500 text-white dark:text-ink flex items-center justify-center text-sm font-bold">
                    {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                </div>
                <span class="hidden md:block text-sm text-ink dark:text-cream">{{ auth()->user()->full_name }}</span>
                <i class="fa-solid fa-chevron-down text-xs text-ink-muted transition-transform" 
                   :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open" @click.outside="open = false" x-cloak
                 class="absolute left-0 mt-2 w-52 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg py-2 z-50">

                <div class="px-4 py-2 border-b border-stone-100 dark:border-stone-800">
                    <p class="font-medium text-sm text-ink dark:text-cream">{{ auth()->user()->full_name }}</p>
                    <p class="text-xs text-ink-muted dark:text-cream/50 truncate">{{ auth()->user()->email }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-800 transition">
                    <i class="fa-solid fa-user w-4 text-center"></i>
                    الملف الشخصي
                </a>

                <div class="border-t border-stone-100 dark:border-stone-800 mt-1 pt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 transition">
                            <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>