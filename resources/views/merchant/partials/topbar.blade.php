<header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 border-b border-gray-200">

    <!-- Right: Toggle + Breadcrumb -->
    <div class="flex items-center space-x-4 space-x-reverse">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-indigo-600 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <h1 class="text-lg font-bold text-gray-800">@yield('page-title', 'لوحة التاجر')</h1>
    </div>

    <!-- Left: Actions -->
    <div class="flex items-center space-x-4 space-x-reverse">

        {{-- زيارة المتجر --}}
        <a href="#" target="_blank" class="hidden md:flex items-center text-sm text-gray-600 hover:text-indigo-600">
            <span class="ml-1">👁️</span>
            زيارة المتجر
        </a>

        {{-- الإشعارات --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="relative text-gray-600 hover:text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
            </button>
        </div>

        {{-- User Dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 space-x-reverse">
                <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                    {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                </div>
                <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->full_name }}</span>
            </button>

            <div x-show="open" @click.outside="open = false"
                 class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    👤 الملف الشخصي
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-right block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        🚪 تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>