@php
    $store = auth()->user()->stores()->first();

    $menuItems = [
        ['label' => 'لوحة التحكم', 'icon' => '📊', 'route' => 'merchant.dashboard', 'pattern' => 'merchant.dashboard'],
        ['label' => 'المنتجات', 'icon' => '👕', 'route' => 'merchant.products.index', 'pattern' => 'merchant.products.*'],
        ['label' => 'التصنيفات', 'icon' => '📂', 'route' => 'merchant.categories.index', 'pattern' => 'merchant.categories.*'],
        ['label' => 'المخزون', 'icon' => '📦', 'route' => 'merchant.inventory.index', 'pattern' => 'merchant.inventory.*'],
        ['label' => 'الطلبات', 'icon' => '🛒', 'route' => 'merchant.orders.index', 'pattern' => 'merchant.orders.*',
            'badge' => $store ? $store->orders()->where('status', 'pending')->count() : 0, 'badge_color' => '#dc2626'],
        ['label' => 'الكوبونات', 'icon' => '🎟️', 'route' => 'merchant.coupons.index', 'pattern' => 'merchant.coupons.*'],
        ['label' => 'العروض', 'icon' => '🔥', 'route' => 'merchant.offers.index', 'pattern' => 'merchant.offers.*'],
        ['label' => 'التقييمات', 'icon' => '⭐', 'route' => 'merchant.reviews.index', 'pattern' => 'merchant.reviews.*',
            'badge' => $store ? $store->reviews()->where('status', 'pending')->count() : 0, 'badge_color' => '#d97706'],
        ['label' => 'الإعدادات', 'icon' => '⚙️', 'route' => 'merchant.settings.index', 'pattern' => 'merchant.settings.*'],
    ];
@endphp

<div class="flex flex-col h-full min-h-screen">

    {{-- ============ Header (Logo) ============ --}}
    <div class="h-16 flex items-center gap-2 px-4 border-b shrink-0"
         style="border-color: var(--border-light);">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white text-lg shrink-0"
             style="background-color: var(--gold);">
            🏪
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-[13px] font-bold truncate" style="color: var(--text-primary);">
                {{ $store->name ?? 'متجري' }}
            </p>
            <p class="text-[10px]" style="color: var(--text-tertiary);">
                لوحة التاجر
            </p>
        </div>
    </div>

    {{-- ============ Navigation ============ --}}
    <nav class="flex-1 py-4 overflow-y-auto">
        <ul class="space-y-1 px-2">
            @foreach($menuItems as $item)
                @php $isActive = request()->routeIs($item['pattern']); @endphp

                <li>
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all relative"
                       @if($isActive)
                           style="background-color: var(--gold); color: white;"
                       @else
                           style="color: var(--text-secondary);"
                           onmouseover="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                           onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';"
                       @endif>

                        <span class="text-xl shrink-0">{{ $item['icon'] }}</span>
                        <span class="text-[13px] font-medium flex-1">{{ $item['label'] }}</span>

                        @if(!empty($item['badge']) && $item['badge'] > 0)
                            <span class="text-white text-[10px] font-bold rounded-full min-w-[20px] h-5 px-1.5 flex items-center justify-center"
                                  style="background-color: {{ $item['badge_color'] }};">
                                {{ $item['badge'] > 9 ? '9+' : $item['badge'] }}
                            </span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="my-4 mx-4 border-t" style="border-color: var(--border-light);"></div>

        <ul class="space-y-1 px-2">
            <li>
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition"
                   style="color: var(--text-secondary);"
                   onmouseover="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                    <span class="text-xl shrink-0">👁️</span>
                    <span class="text-[13px] font-medium flex-1">زيارة المتجر</span>
                </a>
            </li>
        </ul>
    </nav>

    {{-- ============ Footer (Store Info) ============ --}}
    @if($store)
        <div class="border-t p-4 shrink-0" style="border-color: var(--border-light);">
            <div class="flex items-center gap-2">
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
        </div>
    @endif
</div>