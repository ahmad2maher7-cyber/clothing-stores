@php
    $sidebarStore = auth()->user()->stores()->first();
    $storeIsActive = $sidebarStore && $sidebarStore->status === 'active';
    $pendingReviews = $sidebarStore?->reviews()->where('status', 'pending')->count() ?? 0;
    $pendingOrders = $sidebarStore?->orders()->where('status', 'pending')->count() ?? 0;
    $lowStockCount = $sidebarStore?->products()
        ->whereHas('variants', fn($q) => $q->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->where('stock_quantity', '>', 0))
        ->count() ?? 0;
@endphp

{{-- ═══ Logo ═══ --}}
<div class="h-16 flex items-center px-5 border-b border-stone-200 dark:border-stone-800 shrink-0">
    <a href="{{ route('merchant.dashboard') }}" class="flex items-center gap-3 group">
        <div class="w-9 h-9 flex items-center justify-center bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 rounded transition-transform group-hover:scale-105 shrink-0">
            <i class="fa-solid fa-store text-sm"></i>
        </div>
        <div class="overflow-hidden">
            <p class="font-display text-sm font-bold text-ink dark:text-cream leading-tight truncate">
                {{ $sidebarStore->name ?? 'متجري' }}
            </p>
            <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50">لوحة التاجر</p>
        </div>
    </a>
</div>

{{-- ═══ Navigation ═══ --}}
<nav class="flex-1 py-3 overflow-y-auto">

    @php
        $menuItems = $storeIsActive ? [
            ['route' => 'merchant.dashboard',         'label' => 'لوحة التحكم',  'icon' => 'fa-chart-line'],
            ['route' => 'merchant.products.index',    'label' => 'المنتجات',     'icon' => 'fa-shirt'],
            ['route' => 'merchant.categories.index',  'label' => 'التصنيفات',    'icon' => 'fa-folder-tree'],
            ['route' => 'merchant.inventory.index',   'label' => 'المخزون',      'icon' => 'fa-boxes-stacked', 'badge' => $lowStockCount, 'badge_color' => 'amber'],
            ['route' => 'merchant.orders.index',      'label' => 'الطلبات',      'icon' => 'fa-cart-shopping', 'badge' => $pendingOrders, 'badge_color' => 'red'],
            ['route' => 'merchant.coupons.index',     'label' => 'الكوبونات',    'icon' => 'fa-ticket'],
            ['route' => 'merchant.offers.index',      'label' => 'العروض',       'icon' => 'fa-fire'],
            ['route' => 'merchant.reviews.index',     'label' => 'التقييمات',    'icon' => 'fa-star', 'badge' => $pendingReviews, 'badge_color' => 'amber'],
        ] : [
            ['route' => 'merchant.dashboard', 'label' => 'لوحة التحكم', 'icon' => 'fa-chart-line'],
        ];
    @endphp

    @foreach($menuItems as $item)
        @php
            $routeParts = explode('.', $item['route']);
            $pattern = count($routeParts) > 1 ? $routeParts[0] . '.' . $routeParts[1] . '.*' : $item['route'];
            $isActive = request()->routeIs($item['route']) || request()->routeIs($pattern);
        @endphp
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded text-sm transition-colors
                  {{ $isActive 
                     ? 'bg-forest-700 text-white font-medium' 
                     : 'text-ink-soft dark:text-cream/70 hover:bg-stone-100 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400' }}">
            <i class="fa-solid {{ $item['icon'] }} w-5 text-center shrink-0"></i>
            <span class="flex-1 truncate">{{ $item['label'] }}</span>
            @if(!empty($item['badge']) && $item['badge'] > 0)
                <span class="min-w-[20px] h-5 px-1.5 flex items-center justify-center text-[10px] font-bold rounded-full
                             {{ ($item['badge_color'] ?? 'red') === 'amber' ? 'bg-amber-500 text-white' : 'bg-red-600 text-white' }}">
                    {{ $item['badge'] > 9 ? '9+' : $item['badge'] }}
                </span>
            @endif
        </a>
    @endforeach

    @if($storeIsActive)
        <div class="border-t border-stone-200 dark:border-stone-800 mx-4 my-3"></div>

        <a href="{{ route('merchant.settings.index') }}"
           class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded text-sm transition-colors
                  {{ request()->routeIs('merchant.settings.*') 
                     ? 'bg-forest-700 text-white font-medium' 
                     : 'text-ink-soft dark:text-cream/70 hover:bg-stone-100 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400' }}">
            <i class="fa-solid fa-gear w-5 text-center shrink-0"></i>
            <span>الإعدادات</span>
        </a>
    @else
        <div class="mx-3 mt-4 p-4 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 rounded">
            <div class="flex items-start gap-2">
                <i class="fa-solid fa-clock text-amber-600 dark:text-amber-400 shrink-0 mt-0.5 text-sm"></i>
                <div class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                    <p class="font-semibold mb-1">متجرك قيد المراجعة</p>
                    <a href="{{ route('merchant.store.pending') }}" class="underline">عرض الحالة</a>
                </div>
            </div>
        </div>
    @endif

    <div class="border-t border-stone-200 dark:border-stone-800 mx-4 my-3"></div>

    <a href="{{ route('home') }}" target="_blank"
       class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded text-sm text-ink-muted dark:text-cream/50 hover:bg-stone-100 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
        <i class="fa-solid fa-house w-5 text-center shrink-0"></i>
        <span>زيارة المتجر</span>
    </a>
</nav>

{{-- ═══ Footer ═══ --}}
<div class="border-t border-stone-200 dark:border-stone-800 p-4 shrink-0">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-forest-50 dark:bg-forest-950/40 flex items-center justify-center shrink-0 overflow-hidden">
            @if($sidebarStore?->logo)
                <img src="{{ asset('storage/' . $sidebarStore->logo) }}" class="w-full h-full object-cover">
            @else
                <i class="fa-solid fa-store text-forest-700 dark:text-gold-400 text-sm"></i>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-ink dark:text-cream truncate">
                {{ $sidebarStore->name ?? 'متجري' }}
            </p>
            <p class="text-[10px] text-ink-muted dark:text-cream/50 truncate">
                @if(!$sidebarStore)
                    لم يُنشأ بعد
                @elseif($sidebarStore->status === 'active')
                    <i class="fa-solid fa-circle-check text-forest-600 dark:text-gold-400"></i> نشط
                @elseif($sidebarStore->status === 'pending')
                    <i class="fa-solid fa-clock text-amber-500"></i> قيد المراجعة
                @else
                    <i class="fa-solid fa-ban text-red-500"></i> موقوف
                @endif
            </p>
        </div>
    </div>
</div>