<header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 border-b border-gray-200">
    <div class="flex items-center space-x-4 space-x-reverse">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-indigo-600 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <h1 class="text-lg font-bold text-gray-800">@yield('page-title', 'لوحة المشرف')</h1>
    </div>

    <div class="flex items-center space-x-4 space-x-reverse">
        <a href="{{ route('home') }}" target="_blank" 
           class="hidden md:flex items-center text-sm text-gray-600 hover:text-indigo-600">
            👁️ زيارة الموقع
        </a>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 space-x-reverse">
                <div class="w-9 h-9 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold">
                    {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                </div>
                <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->full_name }}</span>
            </button>

            <div x-show="open" @click.outside="open = false" x-cloak
                 class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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