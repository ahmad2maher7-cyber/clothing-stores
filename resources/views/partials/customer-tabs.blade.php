<div class="bg-white dark:bg-zinc-900 border-b border-stone-200 dark:border-stone-800">
    <div class="container-x">
        <nav class="flex overflow-x-auto">

            {{-- ═══ Dashboard ═══ --}}
            <a href="{{ route('customer.dashboard') }}"
               class="flex items-center gap-2 px-5 py-4 border-b-2 whitespace-nowrap text-sm font-medium transition-colors
                      {{ request()->routeIs('customer.dashboard') 
                         ? 'border-forest-700 dark:border-gold-400 text-forest-700 dark:text-gold-400' 
                         : 'border-transparent text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400' }}">
                <i class="fa-solid fa-chart-line w-4 text-center"></i>
                لوحة التحكم
            </a>

            {{-- ═══ Orders ═══ --}}
            <a href="{{ route('customer.orders.index') }}"
               class="flex items-center gap-2 px-5 py-4 border-b-2 whitespace-nowrap text-sm font-medium transition-colors
                      {{ request()->routeIs('customer.orders.*') 
                         ? 'border-forest-700 dark:border-gold-400 text-forest-700 dark:text-gold-400' 
                         : 'border-transparent text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400' }}">
                <i class="fa-solid fa-box w-4 text-center"></i>
                طلباتي
                @php
                    $activeOrders = auth()->user()->orders()
                        ->whereIn('status', ['pending', 'processing', 'shipped', 'delivering'])
                        ->count();
                @endphp
                @if($activeOrders > 0)
                    <span class="min-w-[20px] h-5 px-1.5 flex items-center justify-center text-[10px] font-bold bg-forest-700 dark:bg-gold-500 text-white dark:text-ink rounded-full">
                        {{ $activeOrders > 9 ? '9+' : $activeOrders }}
                    </span>
                @endif
            </a>

            {{-- ═══ Reviews ═══ --}}
            <a href="{{ route('customer.reviews.index') }}"
               class="flex items-center gap-2 px-5 py-4 border-b-2 whitespace-nowrap text-sm font-medium transition-colors
                      {{ request()->routeIs('customer.reviews.*') 
                         ? 'border-forest-700 dark:border-gold-400 text-forest-700 dark:text-gold-400' 
                         : 'border-transparent text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400' }}">
                <i class="fa-solid fa-star w-4 text-center"></i>
                تقييماتي
            </a>

            {{-- ═══ Wishlist ═══ --}}
            <a href="{{ route('customer.wishlist') }}"
               class="flex items-center gap-2 px-5 py-4 border-b-2 whitespace-nowrap text-sm font-medium transition-colors
                      {{ request()->routeIs('customer.wishlist') 
                         ? 'border-forest-700 dark:border-gold-400 text-forest-700 dark:text-gold-400' 
                         : 'border-transparent text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400' }}">
                <i class="fa-solid fa-heart w-4 text-center"></i>
                المفضلة
                @php $wishlistCount = auth()->user()->wishlists()->count(); @endphp
                @if($wishlistCount > 0)
                    <span class="min-w-[20px] h-5 px-1.5 flex items-center justify-center text-[10px] font-bold bg-red-500 text-white rounded-full">
                        {{ $wishlistCount > 9 ? '9+' : $wishlistCount }}
                    </span>
                @endif
            </a>

            {{-- ═══ Profile ═══ --}}
            <a href="{{ route('customer.profile') }}"
               class="flex items-center gap-2 px-5 py-4 border-b-2 whitespace-nowrap text-sm font-medium transition-colors
                      {{ request()->routeIs('customer.profile') 
                         ? 'border-forest-700 dark:border-gold-400 text-forest-700 dark:text-gold-400' 
                         : 'border-transparent text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400' }}">
                <i class="fa-solid fa-user w-4 text-center"></i>
                الملف الشخصي
            </a>
        </nav>
    </div>
</div>