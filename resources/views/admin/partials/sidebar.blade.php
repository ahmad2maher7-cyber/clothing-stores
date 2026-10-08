<aside
    :class="sidebarOpen ? 'w-64' : 'w-20'"
    class="bg-white dark:bg-zinc-900 border-l border-stone-200 dark:border-stone-800 transition-all duration-300 flex flex-col shrink-0">

    {{-- ═══ Logo ═══ --}}
    <div class="h-16 flex items-center px-4 border-b border-stone-200 dark:border-stone-800">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <div class="w-9 h-9 flex items-center justify-center bg-forest-900 dark:bg-gold-500 text-white dark:text-forest-950 rounded shrink-0">
                <i class="fa-solid fa-shield-halved text-base"></i>
            </div>
            <div x-show="sidebarOpen" class="overflow-hidden">
                <p class="font-display text-sm font-bold text-ink dark:text-cream leading-tight">
                    لوحة المشرف
                </p>
                <p class="text-[10px] tracking-widest uppercase text-ink-muted dark:text-cream/50">مدير النظام</p>
            </div>
        </a>
    </div>

    {{-- ═══ Navigation ═══ --}}
    <nav class="flex-1 py-3 overflow-y-auto">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded text-sm transition-colors
                  {{ request()->routeIs('admin.dashboard') 
                     ? 'bg-forest-700 text-white font-medium' 
                     : 'text-ink-soft dark:text-cream/70 hover:bg-stone-100 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400' }}">
            <i class="fa-solid fa-chart-line w-5 text-center shrink-0"></i>
            <span x-show="sidebarOpen">لوحة التحكم</span>
        </a>

        {{-- Users --}}
        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded text-sm transition-colors
                  {{ request()->routeIs('admin.users.*') 
                     ? 'bg-forest-700 text-white font-medium' 
                     : 'text-ink-soft dark:text-cream/70 hover:bg-stone-100 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400' }}">
            <i class="fa-solid fa-users w-5 text-center shrink-0"></i>
            <span x-show="sidebarOpen">المستخدمون</span>
        </a>

        {{-- Stores --}}
        <a href="{{ route('admin.stores.index') }}"
           class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded text-sm transition-colors
                  {{ request()->routeIs('admin.stores.*') 
                     ? 'bg-forest-700 text-white font-medium' 
                     : 'text-ink-soft dark:text-cream/70 hover:bg-stone-100 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400' }}">
            <i class="fa-solid fa-store w-5 text-center shrink-0"></i>
            <span x-show="sidebarOpen" class="flex-1">المتاجر</span>
            @php
                $pendingStores = \App\Models\Store::where('status', 'pending')->count();
            @endphp
            @if($pendingStores > 0)
                <span x-show="sidebarOpen" 
                      class="min-w-[20px] h-5 px-1.5 flex items-center justify-center text-[10px] font-bold bg-red-600 text-white rounded-full">
                    {{ $pendingStores > 9 ? '9+' : $pendingStores }}
                </span>
            @endif
        </a>

        {{-- Brands --}}
        <a href="{{ route('admin.brands.index') }}"
           class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded text-sm transition-colors
                  {{ request()->routeIs('admin.brands.*') 
                     ? 'bg-forest-700 text-white font-medium' 
                     : 'text-ink-soft dark:text-cream/70 hover:bg-stone-100 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400' }}">
            <i class="fa-solid fa-tags w-5 text-center shrink-0"></i>
            <span x-show="sidebarOpen">الماركات</span>
        </a>

        {{-- Orders --}}
        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded text-sm transition-colors
                  {{ request()->routeIs('admin.orders.*') 
                     ? 'bg-forest-700 text-white font-medium' 
                     : 'text-ink-soft dark:text-cream/70 hover:bg-stone-100 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400' }}">
            <i class="fa-solid fa-cart-shopping w-5 text-center shrink-0"></i>
            <span x-show="sidebarOpen">الطلبات</span>
        </a>
        {{-- Import --}}
        <a href="{{ route('admin.import.index') }}"
           class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded text-sm transition-colors
                  {{ request()->routeIs('admin.import.*') 
                     ? 'bg-forest-700 text-white font-medium' 
                     : 'text-ink-soft dark:text-cream/70 hover:bg-stone-100 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400' }}">
            <i class="fa-solid fa-file-import w-5 text-center shrink-0"></i>
            <span x-show="sidebarOpen">استيراد</span>
        </a>

        {{-- Security Logs --}}
        <a href="{{ route('admin.security.logs') }}"
           class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded text-sm transition-colors
                  {{ request()->routeIs('admin.security.*') 
                     ? 'bg-forest-700 text-white font-medium' 
                     : 'text-ink-soft dark:text-cream/70 hover:bg-stone-100 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400' }}">
            <i class="fa-solid fa-shield-halved w-5 text-center shrink-0"></i>
            <span x-show="sidebarOpen">سجلات الأمان</span>
        </a>

                {{-- Settings --}}
        <a href="{{ route('admin.settings.index') }}"
           class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded text-sm transition-colors
                  {{ request()->routeIs('admin.settings.*') 
                     ? 'bg-forest-700 text-white font-medium' 
                     : 'text-ink-soft dark:text-cream/70 hover:bg-stone-100 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400' }}">
            <i class="fa-solid fa-gear w-5 text-center shrink-0"></i>
            <span x-show="sidebarOpen">الإعدادات</span>
        </a>

        <div class="border-t border-stone-200 dark:border-stone-800 mx-4 my-3"></div>

        {{-- Back to Home --}}
        <a href="{{ route('home') }}"
           class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded text-sm text-ink-muted dark:text-cream/50 hover:bg-stone-100 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            <i class="fa-solid fa-house w-5 text-center shrink-0"></i>
            <span x-show="sidebarOpen">الرئيسية</span>
        </a>
    </nav>

    {{-- ═══ Footer ═══ --}}
    <div class="border-t border-stone-200 dark:border-stone-800 p-4">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-full bg-forest-900 dark:bg-gold-500 text-white dark:text-forest-950 flex items-center justify-center font-bold text-sm shrink-0">
                {{ mb_substr(auth()->user()->full_name, 0, 1) }}
            </div>
            <div x-show="sidebarOpen" class="flex-1 min-w-0">
                <p class="text-xs font-medium text-ink dark:text-cream truncate">{{ auth()->user()->full_name }}</p>
                <p class="text-[10px] text-ink-muted dark:text-cream/50">مشرف عام</p>
            </div>
        </div>
    </div>
</aside>