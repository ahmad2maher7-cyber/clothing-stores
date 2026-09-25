<!-- @php
    $store = auth()->user()->stores()->first();

    $menuItems = [
        [
            'label' => 'لوحة التحكم',
            'icon' => '📊',
            'route' => 'merchant.dashboard',
            'pattern' => 'merchant.dashboard',
        ],
        [
            'label' => 'المنتجات',
            'icon' => '👕',
            'route' => 'merchant.products.index',
            'pattern' => 'merchant.products.*',
        ],
        [
            'label' => 'التصنيفات',
            'icon' => '📂',
            'route' => 'merchant.categories.index',
            'pattern' => 'merchant.categories.*',
        ],
        [
            'label' => 'المخزون',
            'icon' => '📦',
            'route' => 'merchant.inventory.index',
            'pattern' => 'merchant.inventory.*',
        ],
        [
            'label' => 'الطلبات',
            'icon' => '🛒',
            'route' => 'merchant.orders.index',
            'pattern' => 'merchant.orders.*',
            'badge' => $store ? $store->orders()->where('status', 'pending')->count() : 0,
            'badge_color' => '#dc2626',
        ],
        [
            'label' => 'الكوبونات',
            'icon' => '🎟️',
            'route' => 'merchant.coupons.index',
            'pattern' => 'merchant.coupons.*',
        ],
        [
            'label' => 'العروض',
            'icon' => '🔥',
            'route' => 'merchant.offers.index',
            'pattern' => 'merchant.offers.*',
        ],
        [
            'label' => 'التقييمات',
            'icon' => '⭐',
            'route' => 'merchant.reviews.index',
            'pattern' => 'merchant.reviews.*',
            'badge' => $store ? $store->reviews()->where('status', 'pending')->count() : 0,
            'badge_color' => '#d97706',
        ],
        [
            'label' => 'الإعدادات',
            'icon' => '⚙️',
            'route' => 'merchant.settings.index',
            'pattern' => 'merchant.settings.*',
        ],
    ];
@endphp

<aside class="h-screen lg:h-screen flex flex-col shadow-xl"
       :class="sidebarCollapsed ? 'lg:w-20' : 'lg:w-64'"
       style="background-color: var(--bg-primary); border-left: 1px solid var(--border-light); width: inherit; transition: width 0.3s;">

    {{-- Logo / Store Name --}}
    <div class="h-16 flex items-center justify-between px-4 border-b shrink-0"
         style="border-color: var(--border-light);">

        <a href="{{ route('merchant.dashboard') }}" class="flex items-center gap-2 min-w-0">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white text-lg shrink-0"
                 style="background-color: var(--gold);">
                🏪
            </div>
            <div x-show="!sidebarCollapsed" class="min-w-0">
                <p class="text-[13px] font-bold truncate" style="color: var(--text-primary);">
                    {{ $store->name ?? 'متجري' }}
                </p>
                <p class="text-[10px]" style="color: var(--text-tertiary);">
                    لوحة التاجر
                </p>
            </div>
        </a>

        {{-- Collapse Button (Desktop) --}}
        <button @click="sidebarCollapsed = !sidebarCollapsed"
                class="hidden lg:flex w-7 h-7 items-center justify-center rounded-lg transition"
                style="color: var(--text-secondary);"
                onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                onmouseout="this.style.backgroundColor='transparent';">
            <svg class="w-4 h-4" :class="sidebarCollapsed ? 'rotate-180' : ''" style="transition: transform 0.3s;"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Close Button (Mobile) --}}
        <button @click="sidebarOpen = false" class="lg:hidden text-2xl" style="color: var(--text-secondary);">
            ×
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 py-4 overflow-y-auto">

        <ul class="space-y-1 px-2">
            @foreach($menuItems as $item)
                @php
                    $isActive = request()->routeIs($item['pattern']);
                @endphp

                <li>
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150 group relative"
                       @if($isActive)
                           style="background-color: var(--gold); color: white;"
                       @else
                           style="color: var(--text-secondary);"
                           onmouseover="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                           onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';"
                       @endif>

                        {{-- Icon --}}
                        <span class="text-xl shrink-0">{{ $item['icon'] }}</span>

                        {{-- Label --}}
                        <span x-show="!sidebarCollapsed" 
                              class="text-[13px] font-medium flex-1">
                            {{ $item['label'] }}
                        </span>

                        {{-- Badge --}}
                        @if(!empty($item['badge']) && $item['badge'] > 0)
                            <span x-show="!sidebarCollapsed"
                                  class="text-white text-[10px] font-bold rounded-full min-w-[20px] h-5 px-1.5 flex items-center justify-center"
                                  style="background-color: {{ $item['badge_color'] }};">
                                {{ $item['badge'] > 9 ? '9+' : $item['badge'] }}
                            </span>

                            {{-- Dot when collapsed --}}
                            <span x-show="sidebarCollapsed"
                                  class="absolute top-2 right-2 w-2 h-2 rounded-full"
                                  style="background-color: {{ $item['badge_color'] }};"></span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Divider --}}
        <div x-show="!sidebarCollapsed" class="my-4 mx-4 border-t" style="border-color: var(--border-light);"></div>

        {{-- Visit Store --}}
        <ul class="space-y-1 px-2 mt-4">
            <li>
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150"
                   style="color: var(--text-secondary);"
                   onmouseover="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                    <span class="text-xl shrink-0">👁️</span>
                    <span x-show="!sidebarCollapsed" class="text-[13px] font-medium flex-1">
                        زيارة المتجر
                    </span>
                </a>
            </li>
        </ul>
    </nav>

    {{-- Footer: Store Info --}}
    <div class="border-t p-4 shrink-0" style="border-color: var(--border-light);">
        @if($store)
            <div class="flex items-center gap-2" x-show="!sidebarCollapsed">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-sm font-bold shrink-0"
                     style="background-color: var(--gold);">
                    {{ mb_substr($store->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[12px] font-medium truncate" style="color: var(--text-primary);">
                        {{ $store->name }}
                    </p>
                    <p class="text-[10px]" style="color: {{ $store->status === 'active' ? '#166534' : '#d97706' }};">
                        {{ $store->status === 'active' ? '✅ نشط' : ($store->status === 'pending' ? '⏳ قيد المراجعة' : '⛔ موقوف') }}
                    </p>
                </div>
            </div>

            {{-- Collapsed: Only avatar --}}
            <div x-show="sidebarCollapsed" class="flex justify-center">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-sm font-bold"
                     style="background-color: var(--gold);">
                    {{ mb_substr($store->name, 0, 1) }}
                </div>
            </div>
        @endif
    </div>
</aside> -->