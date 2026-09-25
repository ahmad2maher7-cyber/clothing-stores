@php
    $user = auth()->user();
    $store = $user->stores()->first();
    $unreadNotifications = 0;
    if ($user) {
       $unreadNotifications = \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count();
    }
@endphp

<header class="h-16 flex items-center justify-between px-4 md:px-6 shrink-0 border-b"
        style="background-color: var(--bg-primary); border-color: var(--border-light);">

    {{-- Right: Toggle + Title --}}
    <div class="flex items-center gap-3 min-w-0">
        {{-- Mobile Toggle --}}
        <button @click="sidebarOpen = true"
        class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg transition"
                style="color: var(--text-secondary);"
                onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                onmouseout="this.style.backgroundColor='transparent';">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Page Title --}}
        <div class="min-w-0">
            <h1 class="text-[15px] md:text-lg font-bold truncate" style="color: var(--text-primary);">
                @yield('page-title', 'لوحة التحكم')
            </h1>
            @hasSection('page-subtitle')
                <p class="text-[11px] truncate hidden md:block" style="color: var(--text-tertiary);">
                    @yield('page-subtitle')
                </p>
            @endif
        </div>
    </div>

    {{-- Left: Actions --}}
    <div class="flex items-center gap-1 md:gap-2">

        {{-- Visit Store (Desktop) --}}
        <a href="{{ route('home') }}" target="_blank"
           class="hidden md:flex items-center gap-2 h-9 px-3 rounded-lg text-[12px] font-medium transition"
           style="color: var(--text-secondary);"
           onmouseover="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span>زيارة المتجر</span>
        </a>

        {{-- Dark Mode --}}
        <button onclick="toggleDarkMode()" title="الوضع الليلي"
                class="w-9 h-9 flex items-center justify-center rounded-lg transition"
                style="color: var(--text-secondary);"
                onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                onmouseout="this.style.backgroundColor='transparent';">
            <svg class="w-4 h-4 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <svg class="w-4 h-4 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </button>

        {{-- Notifications --}}
        <a href="#" title="الإشعارات"
   onclick="event.preventDefault(); alert('الإشعارات قيد التطوير');"
           class="relative w-9 h-9 flex items-center justify-center rounded-lg transition"
           style="color: var(--text-secondary);"
           onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
           onmouseout="this.style.backgroundColor='transparent';">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            @if($unreadNotifications > 0)
                <span class="absolute top-1 right-1 min-w-[16px] h-4 px-1 text-white text-[9px] font-bold rounded-full flex items-center justify-center"
                      style="background-color: #dc2626;">
                    {{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}
                </span>
            @endif
        </a>

        {{-- User Menu --}}
        <div x-data="{ userMenu: false }" class="relative">
            <button @click="userMenu = !userMenu"
                    class="flex items-center gap-2 h-9 px-2 rounded-lg transition"
                    onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                    onmouseout="this.style.backgroundColor='transparent';">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[11px] font-bold"
                     style="background-color: var(--gold);">
                    {{ mb_substr($user->full_name, 0, 1) }}
                </div>
                <span class="hidden md:block text-[13px] font-medium" style="color: var(--text-primary);">
                    {{ explode(' ', $user->full_name)[0] }}
                </span>
                <svg class="w-3 h-3 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     style="color: var(--text-tertiary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Dropdown --}}
            <div x-show="userMenu" @click.outside="userMenu = false" x-cloak x-transition
                 class="absolute left-0 mt-2 w-56 rounded-xl shadow-lg border py-1 z-50"
                 style="background-color: var(--bg-primary); border-color: var(--border-light);">

                <div class="px-4 py-3 border-b" style="border-color: var(--border-light);">
                    <p class="text-[13px] font-semibold" style="color: var(--text-primary);">
                        {{ $user->full_name }}
                    </p>
                    <p class="text-[11px] truncate" style="color: var(--text-tertiary);">{{ $user->email }}</p>
                </div>

                <a href="{{ route('merchant.settings.index') }}"
                   class="block px-4 py-2.5 text-[13px] transition"
                   style="color: var(--text-primary);"
                   onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                   onmouseout="this.style.backgroundColor='transparent';">
                    ⚙️ إعدادات المتجر
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2.5 text-[13px] transition"
                   style="color: var(--text-primary);"
                   onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                   onmouseout="this.style.backgroundColor='transparent';">
                    👤 الملف الشخصي
                </a>

                <div class="border-t my-1" style="border-color: var(--border-light);"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-right px-4 py-2.5 text-[13px] transition"
                            style="color: #dc2626;"
                            onmouseover="this.style.backgroundColor='#fef2f2';"
                            onmouseout="this.style.backgroundColor='transparent';">
                        🚪 تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleDarkMode() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        if (isDark) {
            html.classList.remove('dark');
            localStorage.setItem('darkMode', 'false');
        } else {
            html.classList.add('dark');
            localStorage.setItem('darkMode', 'true');
        }
    }
</script>