<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">

    {{-- Right --}}
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" 
                class="text-gray-500 hover:text-gray-900 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <h1 class="text-base font-medium text-gray-900">@yield('page-title', 'لوحة المشرف')</h1>
    </div>

    {{-- Left --}}
    <div class="flex items-center gap-2">
        <a href="{{ route('home') }}" target="_blank" 
           class="hidden md:flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md hover:bg-gray-100 transition">
            <span>👁️</span>
            <span>زيارة الموقع</span>
        </a>

        <div class="hidden md:block w-px h-6 bg-gray-200"></div>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" 
                    class="flex items-center gap-2 p-1 rounded-md hover:bg-gray-100 transition">
                <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-sm font-medium">
                    {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                </div>
                <span class="hidden md:block text-sm text-gray-700">{{ auth()->user()->full_name }}</span>
            </button>

            <div x-show="open" @click.outside="open = false" x-cloak
                 class="absolute left-0 mt-2 w-52 bg-white border border-gray-200 rounded-lg py-2 z-50">

                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="font-medium text-sm text-gray-900">{{ auth()->user()->full_name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    👤 الملف الشخصي
                </a>

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
    </div>
</header>ئ