<div x-data="{ open: false }" 
     @toggle-mobile-menu.window="open = !open"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 md:hidden">

    <div @click="open = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div class="absolute right-0 top-0 h-full w-80 max-w-[85%] bg-white dark:bg-zinc-950 flex flex-col shadow-2xl"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full">
        
        {{-- Header --}}
        <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 flex items-center justify-center bg-forest-900 dark:bg-gold-500 text-white dark:text-ink rounded">
                    <i class="fa-solid fa-shirt"></i>
                </div>
                <span class="font-display font-bold text-ink dark:text-cream">متجر الملابس</span>
            </div>
            <button @click="open = false" 
                    class="w-8 h-8 flex items-center justify-center text-ink-muted hover:text-ink dark:hover:text-cream rounded hover:bg-stone-100 dark:hover:bg-zinc-900 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- User Info --}}
        @auth
            <div class="p-5 border-b border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-zinc-900">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 flex items-center justify-center bg-forest-900 dark:bg-gold-500 text-white dark:text-ink rounded-full font-bold">
                        {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm text-ink dark:text-cream truncate">
                            {{ auth()->user()->full_name }}
                        </p>
                        <p class="text-xs text-ink-muted dark:text-cream/50 truncate">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>
        @endauth

        {{-- Links --}}
        <nav class="flex-1 overflow-y-auto py-3">
            @php
                $mobileLinks = [
                    ['route' => 'home',          'label' => 'الرئيسية',    'icon' => 'fa-house'],
                    ['route' => 'products.index','label' => 'كل المنتجات','icon' => 'fa-shirt'],
                    ['route' => 'stores.index',  'label' => 'المتاجر',     'icon' => 'fa-store'],
                    ['route' => 'offers.index',  'label' => 'العروض',      'icon' => 'fa-fire', 'accent' => true],
                ];
            @endphp

            @foreach($mobileLinks as $link)
                <a href="{{ route($link['route']) }}" 
                   class="flex items-center gap-3 px-5 py-3.5 text-sm font-medium transition-colors
                          {{ isset($link['accent']) 
                             ? 'text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20' 
                             : 'text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-900 hover:text-forest-700 dark:hover:text-gold-400' }}">
                    <i class="fa-solid {{ $link['icon'] }} w-5 text-center"></i>
                    {{ $link['label'] }}
                </a>
            @endforeach

            <div class="my-3 border-t border-stone-200 dark:border-stone-800"></div>
            <p class="px-5 py-2 text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/40">
                تسوّق حسب
            </p>

            @foreach(['men' => 'رجالي', 'women' => 'نسائي', 'kids' => 'أطفال'] as $gender => $label)
                <a href="{{ route('products.index', ['gender' => $gender]) }}" 
                   class="flex items-center gap-3 px-5 py-3.5 text-sm font-medium text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-900 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                    <i class="fa-solid fa-user w-5 text-center"></i>
                    {{ $label }}
                </a>
            @endforeach

            @auth
                @if(auth()->user()->role === 'customer')
                    <div class="my-3 border-t border-stone-200 dark:border-stone-800"></div>

                    <a href="{{ route('customer.wishlist') }}" 
                       class="flex items-center gap-3 px-5 py-3.5 text-sm font-medium text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-900 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                        <i class="fa-solid fa-heart w-5 text-center"></i>
                        المفضلة
                    </a>
                    <a href="{{ route('cart.index') }}" 
                       class="flex items-center gap-3 px-5 py-3.5 text-sm font-medium text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-900 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                        <i class="fa-solid fa-cart-shopping w-5 text-center"></i>
                        السلة
                    </a>
                    <a href="{{ route('customer.orders.index') }}" 
                       class="flex items-center gap-3 px-5 py-3.5 text-sm font-medium text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-900 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                        <i class="fa-solid fa-box w-5 text-center"></i>
                        طلباتي
                    </a>
                @endif
            @endauth
        </nav>

        {{-- Footer --}}
        <div class="border-t border-stone-200 dark:border-stone-800 p-5">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 h-11 text-sm font-medium text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900/50 rounded hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        تسجيل الخروج
                    </button>
                </form>
            @else
                <div class="space-y-2">
                    <a href="{{ route('login') }}" 
                       class="flex items-center justify-center gap-2 h-11 text-sm font-medium text-ink-soft dark:text-cream/80 border border-stone-300 dark:border-stone-700 rounded hover:bg-stone-50 dark:hover:bg-zinc-900 transition-colors">
                        تسجيل الدخول
                    </a>
                    <a href="{{ route('register') }}" 
                       class="flex items-center justify-center gap-2 h-11 text-sm font-semibold bg-forest-900 dark:bg-gold-500 text-white dark:text-ink rounded hover:bg-forest-800 dark:hover:bg-gold-400 transition-colors">
                        إنشاء حساب
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>