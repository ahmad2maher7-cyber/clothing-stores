<aside
    :class="sidebarOpen ? 'w-64' : 'w-20'"
    class="bg-gradient-to-b from-gray-900 to-gray-800 text-white transition-all duration-300 flex flex-col shadow-xl">

    {{-- Logo --}}
    <div class="h-16 flex items-center justify-between px-4 border-b border-gray-700">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 space-x-reverse">
            <span class="text-2xl">🛡️</span>
            <span x-show="sidebarOpen" class="font-bold text-lg">لوحة المشرف</span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 py-4 space-y-1 overflow-y-auto">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center px-4 py-3 hover:bg-gray-700 transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 border-r-4 border-yellow-400' : '' }}">
            <span class="text-xl">📊</span>
            <span x-show="sidebarOpen" class="mr-3 font-medium">لوحة التحكم</span>
        </a>

        {{-- Users --}}
        <a href="{{ route('admin.users.index') }}"
           class="flex items-center px-4 py-3 hover:bg-gray-700 transition {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 border-r-4 border-yellow-400' : '' }}">
            <span class="text-xl">👥</span>
            <span x-show="sidebarOpen" class="mr-3 font-medium">المستخدمون</span>
        </a>

        {{-- Stores --}}
        <a href="{{ route('admin.stores.index') }}"
           class="flex items-center px-4 py-3 hover:bg-gray-700 transition {{ request()->routeIs('admin.stores.*') ? 'bg-gray-700 border-r-4 border-yellow-400' : '' }}">
            <span class="text-xl">🏪</span>
            <span x-show="sidebarOpen" class="mr-3 font-medium">المتاجر</span>
            @php
                $pendingStores = \App\Models\Store::where('status', 'pending')->count();
            @endphp
            @if($pendingStores > 0)
                <span x-show="sidebarOpen" class="mr-auto bg-yellow-500 text-gray-900 text-xs px-2 py-1 rounded-full font-bold">{{ $pendingStores }}</span>
            @endif
        </a>

        {{-- Orders --}}
        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center px-4 py-3 hover:bg-gray-700 transition {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700 border-r-4 border-yellow-400' : '' }}">
            <span class="text-xl">🛒</span>
            <span x-show="sidebarOpen" class="mr-3 font-medium">الطلبات</span>
        </a>

        {{-- Products --}}
        <a href="#"
           class="flex items-center px-4 py-3 hover:bg-gray-700 transition">
            <span class="text-xl">👕</span>
            <span x-show="sidebarOpen" class="mr-3 font-medium">المنتجات</span>
        </a>

        {{-- Reviews --}}
        <a href="#"
           class="flex items-center px-4 py-3 hover:bg-gray-700 transition">
            <span class="text-xl">⭐</span>
            <span x-show="sidebarOpen" class="mr-3 font-medium">التقييمات</span>
        </a>

        {{-- Reports --}}
        <a href="#"
           class="flex items-center px-4 py-3 hover:bg-gray-700 transition">
            <span class="text-xl">📈</span>
            <span x-show="sidebarOpen" class="mr-3 font-medium">التقارير</span>
        </a>

        {{-- Settings --}}
        <a href="#"
           class="flex items-center px-4 py-3 hover:bg-gray-700 transition">
            <span class="text-xl">⚙️</span>
            <span x-show="sidebarOpen" class="mr-3 font-medium">الإعدادات</span>
        </a>
    </nav>

    {{-- Footer --}}
    <div class="border-t border-gray-700 p-4">
        <div class="flex items-center space-x-2 space-x-reverse">
            <div class="w-10 h-10 rounded-full bg-yellow-400 text-gray-900 flex items-center justify-center font-bold">
                {{ mb_substr(auth()->user()->full_name, 0, 1) }}
            </div>
            <div x-show="sidebarOpen" class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate">{{ auth()->user()->full_name }}</p>
                <p class="text-xs text-gray-400">مشرف عام</p>
            </div>
        </div>
    </div>
</aside>