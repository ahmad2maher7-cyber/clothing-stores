<aside
    :class="sidebarOpen ? 'w-64' : 'w-20'"
    class="bg-gradient-to-b from-indigo-700 to-indigo-900 text-white transition-all duration-300 flex flex-col shadow-xl">

    <!-- Logo -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-indigo-600">
        <a href="{{ route('merchant.dashboard') }}" class="flex items-center space-x-2 space-x-reverse">
            <span class="text-2xl">🏪</span>
            <span x-show="sidebarOpen" class="font-bold text-lg">{{ $store->name ?? 'متجري' }}</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-4 space-y-1 overflow-y-auto">

        {{-- لوحة التحكم --}}
        <a href="{{ route('merchant.dashboard') }}"
           class="flex items-center px-4 py-3 hover:bg-indigo-600 transition {{ request()->routeIs('merchant.dashboard') ? 'bg-indigo-600 border-r-4 border-yellow-400' : '' }}">
            <span class="text-xl">📊</span>
            <span x-show="sidebarOpen" class="mr-3 font-medium">لوحة التحكم</span>
        </a>

        {{-- المنتجات --}}
<a href="{{ route('merchant.products.index') }}"
   class="flex items-center px-4 py-3 hover:bg-indigo-600 transition {{ request()->routeIs('merchant.products.*') ? 'bg-indigo-600 border-r-4 border-yellow-400' : '' }}">
    <span class="text-xl">👕</span>
    <span x-show="sidebarOpen" class="mr-3 font-medium">المنتجات</span>
</a>

        {{-- التصنيفات --}}
<a href="{{ route('merchant.categories.index') }}"
   class="flex items-center px-4 py-3 hover:bg-indigo-600 transition {{ request()->routeIs('merchant.categories.*') ? 'bg-indigo-600 border-r-4 border-yellow-400' : '' }}">
    <span class="text-xl">📂</span>
    <span x-show="sidebarOpen" class="mr-3 font-medium">التصنيفات</span>
</a>

        {{-- المخزون --}}
<a href="{{ route('merchant.inventory.index') }}"
   class="flex items-center px-4 py-3 hover:bg-indigo-600 transition {{ request()->routeIs('merchant.inventory.*') ? 'bg-indigo-600 border-r-4 border-yellow-400' : '' }}">
    <span class="text-xl">📦</span>
    <span x-show="sidebarOpen" class="mr-3 font-medium">المخزون</span>
    @php
        $lowStockCount = \App\Models\ProductVariant::whereHas('product', function($q) {
            $q->where('store_id', auth()->user()->stores()->first()?->id);
        })->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->where('stock_quantity', '>', 0)->count();
    @endphp
    @if($lowStockCount > 0)
        <span x-show="sidebarOpen" class="mr-auto bg-orange-500 text-xs px-2 py-1 rounded-full">{{ $lowStockCount }}</span>
    @endif
</a>

        {{-- الطلبات --}}
<a href="{{ route('merchant.orders.index') }}"
   class="flex items-center px-4 py-3 hover:bg-indigo-600 transition {{ request()->routeIs('merchant.orders.*') ? 'bg-indigo-600 border-r-4 border-yellow-400' : '' }}">
    <span class="text-xl">🛒</span>
    <span x-show="sidebarOpen" class="mr-3 font-medium">الطلبات</span>
    @php
        $pendingCount = auth()->user()->stores()->first()?->orders()->where('status', 'pending')->count() ?? 0;
    @endphp
    @if($pendingCount > 0)
        <span x-show="sidebarOpen" class="mr-auto bg-red-500 text-xs px-2 py-1 rounded-full">{{ $pendingCount }}</span>
    @endif
</a>
        {{-- الكوبونات --}}
<a href="{{ route('merchant.coupons.index') }}"
   class="flex items-center px-4 py-3 hover:bg-indigo-600 transition {{ request()->routeIs('merchant.coupons.*') ? 'bg-indigo-600 border-r-4 border-yellow-400' : '' }}">
    <span class="text-xl">🎟️</span>
    <span x-show="sidebarOpen" class="mr-3 font-medium">الكوبونات</span>
</a>

{{-- العروض --}}
<a href="{{ route('merchant.offers.index') }}"
   class="flex items-center px-4 py-3 hover:bg-indigo-600 transition {{ request()->routeIs('merchant.offers.*') ? 'bg-indigo-600 border-r-4 border-yellow-400' : '' }}">
    <span class="text-xl">🔥</span>
    <span x-show="sidebarOpen" class="mr-3 font-medium">العروض</span>
</a>

        
        {{-- التقييمات --}}
        <a href="#"
           class="flex items-center px-4 py-3 hover:bg-indigo-600 transition {{ request()->routeIs('merchant.reviews.*') ? 'bg-indigo-600 border-r-4 border-yellow-400' : '' }}">
            <span class="text-xl">⭐</span>
            <span x-show="sidebarOpen" class="mr-3 font-medium">التقييمات</span>
        </a>

        {{-- التقارير --}}
        <a href="#"
           class="flex items-center px-4 py-3 hover:bg-indigo-600 transition {{ request()->routeIs('merchant.reports.*') ? 'bg-indigo-600 border-r-4 border-yellow-400' : '' }}">
            <span class="text-xl">📈</span>
            <span x-show="sidebarOpen" class="mr-3 font-medium">التقارير</span>
        </a>

        {{-- الإعدادات --}}
<a href="{{ route('merchant.settings.index') }}"
   class="flex items-center px-4 py-3 hover:bg-indigo-600 transition {{ request()->routeIs('merchant.settings.*') ? 'bg-indigo-600 border-r-4 border-yellow-400' : '' }}">
    <span class="text-xl">⚙️</span>
    <span x-show="sidebarOpen" class="mr-3 font-medium">الإعدادات</span>
</a>
    </nav>

    <!-- Footer - Store Info -->
    <div class="border-t border-indigo-600 p-4">
        <div class="flex items-center space-x-2 space-x-reverse">
            <div class="w-10 h-10 rounded-full bg-yellow-400 text-indigo-900 flex items-center justify-center font-bold">
                {{ mb_substr($store->name ?? 'م', 0, 1) }}
            </div>
            <div x-show="sidebarOpen" class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate">{{ $store->name ?? 'متجري' }}</p>
                <p class="text-xs text-indigo-300 truncate">الحالة: {{ $store->status === 'active' ? 'نشط ✅' : 'موقوف ⛔' }}</p>
            </div>
        </div>
    </div>
</aside>