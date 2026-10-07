<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                <i class="fa-solid fa-user text-lg"></i>
            </div>
            <h2 class="font-display text-xl font-bold text-ink dark:text-cream">
                الملف الشخصي
            </h2>
        </div>
    </x-slot>

    <div class="py-10 lg:py-14">
        <div class="container-x">

            @php
                $profileTabs = [
                    ['route' => 'profile.edit', 'label' => 'الملف الشخصي', 'icon' => 'fa-user'],
                ];

                $user = auth()->user();
                if ($user->role === 'merchant' && $user->stores()->exists()) {
                    $profileTabs[] = ['route' => 'merchant.settings.index', 'label' => 'إعدادات المتجر', 'icon' => 'fa-gear'];
                } elseif ($user->role === 'customer') {
                    $profileTabs[] = ['route' => 'customer.dashboard', 'label' => 'لوحة حسابي', 'icon' => 'fa-chart-line'];
                }
            @endphp

            @if(count($profileTabs) > 1)
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg mb-6 overflow-hidden">
                    <div class="flex overflow-x-auto">
                        @foreach($profileTabs as $tab)
                            <a href="{{ route($tab['route']) }}"
                               class="flex items-center gap-2 px-5 py-4 whitespace-nowrap border-b-2 transition-colors
                                      {{ request()->routeIs($tab['route']) 
                                         ? 'border-forest-700 dark:border-gold-400 text-forest-700 dark:text-gold-400 font-medium bg-forest-50 dark:bg-forest-950/30' 
                                         : 'border-transparent text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400 hover:bg-stone-50 dark:hover:bg-zinc-800' }}">
                                <i class="fa-solid {{ $tab['icon'] }}"></i>
                                <span class="text-sm">{{ $tab['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ═══ Sidebar: User Card ═══ --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6 text-center sticky top-24">

                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar_url }}"
                                 alt="{{ auth()->user()->full_name }}"
                                 class="w-24 h-24 mx-auto rounded-full object-cover border-4 border-stone-100 dark:border-zinc-800 mb-4">
                        @else
                            <div class="w-24 h-24 mx-auto rounded-full bg-forest-50 dark:bg-forest-950/40 flex items-center justify-center text-4xl font-bold text-forest-700 dark:text-gold-400 border-4 border-stone-100 dark:border-zinc-800 mb-4">
                                {{ mb_substr(auth()->user()->full_name, 0, 1) }}
                            </div>
                        @endif

                        <h2 class="font-display text-xl font-bold text-ink dark:text-cream mb-1">
                            {{ auth()->user()->full_name }}
                        </h2>
                        <p class="text-sm text-ink-muted dark:text-cream/60 mb-4">
                            {{ auth()->user()->email }}
                        </p>

                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-5
                                    @if(auth()->user()->role === 'admin') bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 border border-forest-200 dark:border-forest-900/50
                                    @elseif(auth()->user()->role === 'merchant') bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50
                                    @else bg-stone-100 dark:bg-zinc-800 text-ink-soft dark:text-cream/70 border border-stone-200 dark:border-stone-700
                                    @endif">
                            @if(auth()->user()->role === 'admin')
                                <i class="fa-solid fa-shield-halved"></i>
                            @elseif(auth()->user()->role === 'merchant')
                                <i class="fa-solid fa-store"></i>
                            @else
                                <i class="fa-solid fa-user"></i>
                            @endif
                            <span class="font-medium text-sm">
                                @switch(auth()->user()->role)
                                    @case('admin') مشرف @break
                                    @case('merchant') تاجر @break
                                    @default زبون
                                @endswitch
                            </span>
                        </div>

                        <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">
                            عضو منذ {{ auth()->user()->created_at->format('Y/m/Y') }}
                        </p>
                    </div>
                </div>

                {{-- ═══ Main: Profile Info ═══ --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- ═══ Information Card ═══ --}}
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">

                        <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                            <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                                <i class="fa-solid fa-circle-info text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-display font-bold text-ink dark:text-cream">المعلومات الشخصية</h3>
                                <p class="text-xs text-ink-muted dark:text-cream/60">تفاصيل حسابك الأساسية</p>
                            </div>
                        </div>

                        <div class="p-6 space-y-5">

                            <div class="flex items-start gap-4 pb-5 border-b border-stone-100 dark:border-stone-800">
                                <div class="w-10 h-10 flex items-center justify-center bg-stone-50 dark:bg-zinc-950 text-ink-muted dark:text-cream/60 rounded shrink-0">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">
                                        الاسم الكامل
                                    </p>
                                    <p class="font-medium text-ink dark:text-cream">
                                        {{ auth()->user()->full_name }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 pb-5 border-b border-stone-100 dark:border-stone-800">
                                <div class="w-10 h-10 flex items-center justify-center bg-stone-50 dark:bg-zinc-950 text-ink-muted dark:text-cream/60 rounded shrink-0">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">
                                        البريد الإلكتروني
                                    </p>
                                    <p class="font-medium text-ink dark:text-cream" dir="ltr">
                                        {{ auth()->user()->email }}
                                    </p>
                                    @if(auth()->user()->email_verified_at)
                                        <span class="inline-flex items-center gap-1 text-xs text-forest-700 dark:text-gold-400 mt-1">
                                            <i class="fa-solid fa-circle-check text-xs"></i>
                                            مُفعّل
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs text-amber-600 dark:text-amber-400 mt-1">
                                            <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                            غير مُفعّل
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-start gap-4 pb-5 border-b border-stone-100 dark:border-stone-800">
                                <div class="w-10 h-10 flex items-center justify-center bg-stone-50 dark:bg-zinc-950 text-ink-muted dark:text-cream/60 rounded shrink-0">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">
                                        رقم الهاتف
                                    </p>
                                    <p class="font-medium text-ink dark:text-cream" dir="ltr">
                                        {{ auth()->user()->phone ?? '—' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 flex items-center justify-center bg-stone-50 dark:bg-zinc-950 text-ink-muted dark:text-cream/60 rounded shrink-0">
                                    <i class="fa-solid fa-id-badge"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">
                                        نوع الحساب
                                    </p>
                                    <p class="font-medium text-ink dark:text-cream">
                                        @switch(auth()->user()->role)
                                            @case('admin')
                                                <i class="fa-solid fa-shield-halved text-forest-700 dark:text-gold-400"></i>
                                                مشرف
                                                @break
                                            @case('merchant')
                                                <i class="fa-solid fa-store text-amber-600 dark:text-amber-400"></i>
                                                تاجر
                                                @break
                                            @default
                                                <i class="fa-solid fa-user text-ink-muted dark:text-cream/60"></i>
                                                زبون
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ═══ Role-Specific Actions ═══ --}}
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                        <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                            <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                                <i class="fa-solid fa-bolt text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-display font-bold text-ink dark:text-cream">إجراءات سريعة</h3>
                                <p class="text-xs text-ink-muted dark:text-cream/60">روابط مفيدة لحسابك</p>
                            </div>
                        </div>

                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                                    <i class="fa-solid fa-chart-line text-forest-700 dark:text-gold-400 text-lg"></i>
                                    <span class="text-sm font-medium text-ink dark:text-cream">لوحة المشرف</span>
                                </a>
                                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                                    <i class="fa-solid fa-gear text-forest-700 dark:text-gold-400 text-lg"></i>
                                    <span class="text-sm font-medium text-ink dark:text-cream">الإعدادات</span>
                                </a>
                            @elseif(auth()->user()->role === 'merchant')
                                <a href="{{ route('merchant.dashboard') }}" class="flex items-center gap-3 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                                    <i class="fa-solid fa-house text-forest-700 dark:text-gold-400 text-lg"></i>
                                    <span class="text-sm font-medium text-ink dark:text-cream">لوحة التاجر</span>
                                </a>
                                <a href="{{ route('merchant.settings.index') }}" class="flex items-center gap-3 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                                    <i class="fa-solid fa-gear text-forest-700 dark:text-gold-400 text-lg"></i>
                                    <span class="text-sm font-medium text-ink dark:text-cream">إعدادات المتجر</span>
                                </a>
                            @else
                                <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                                    <i class="fa-solid fa-chart-line text-forest-700 dark:text-gold-400 text-lg"></i>
                                    <span class="text-sm font-medium text-ink dark:text-cream">لوحة حسابي</span>
                                </a>
                                <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-3 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                                    <i class="fa-solid fa-box text-forest-700 dark:text-gold-400 text-lg"></i>
                                    <span class="text-sm font-medium text-ink dark:text-cream">طلباتي</span>
                                </a>
                                <a href="{{ route('customer.wishlist') }}" class="flex items-center gap-3 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                                    <i class="fa-solid fa-heart text-forest-700 dark:text-gold-400 text-lg"></i>
                                    <span class="text-sm font-medium text-ink dark:text-cream">المفضلة</span>
                                </a>
                                <a href="{{ route('customer.reviews.index') }}" class="flex items-center gap-3 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                                    <i class="fa-solid fa-star text-forest-700 dark:text-gold-400 text-lg"></i>
                                    <span class="text-sm font-medium text-ink dark:text-cream">تقييماتي</span>
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="sm:col-span-2">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center justify-center gap-2 p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 rounded hover:bg-red-100 dark:hover:bg-red-950/40 transition-colors">
                                    <i class="fa-solid fa-right-from-bracket text-red-600 dark:text-red-400 text-lg"></i>
                                    <span class="text-sm font-medium text-red-600 dark:text-red-400">تسجيل الخروج</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>