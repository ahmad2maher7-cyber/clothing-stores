@extends('admin.layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')

    <div class="mb-8">
        <span class="eyebrow block mb-3">— نظرة عامة</span>
        <h1 class="font-display text-3xl font-bold text-ink dark:text-cream mb-2">
            مرحباً {{ auth()->user()->full_name }} <i class="fa-solid fa-shield-halved text-forest-700 dark:text-gold-400"></i>
        </h1>
        <p class="text-sm text-ink-muted dark:text-cream/60">نظرة شاملة على المنصة</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
                <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/40">المستخدمون</span>
            </div>
            <p class="font-display text-3xl font-bold text-ink dark:text-cream">{{ $stats['users'] }}</p>
            <p class="text-xs text-ink-muted dark:text-cream/50 mt-2">
                {{ $stats['merchants'] }} تاجر • {{ $stats['customers'] }} زبون
            </p>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <i class="fa-solid fa-store text-lg"></i>
                </div>
                <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/40">المتاجر</span>
            </div>
            <p class="font-display text-3xl font-bold text-ink dark:text-cream">{{ $stats['stores'] }}</p>
            <p class="text-xs text-ink-muted dark:text-cream/50 mt-2">
                {{ $stats['active_stores'] }} نشط
                @if($stats['pending_stores'] > 0)
                    • <span class="text-amber-600 dark:text-amber-400 font-medium">{{ $stats['pending_stores'] }} معلق</span>
                @endif
            </p>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <i class="fa-solid fa-shirt text-lg"></i>
                </div>
                <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/40">المنتجات</span>
            </div>
            <p class="font-display text-3xl font-bold text-ink dark:text-cream">{{ $stats['products'] }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <i class="fa-solid fa-cart-shopping text-lg"></i>
                </div>
                <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/40">الطلبات</span>
            </div>
            <p class="font-display text-3xl font-bold text-ink dark:text-cream">{{ $stats['orders'] }}</p>
            @if($stats['pending_orders'] > 0)
                <p class="text-xs text-amber-600 dark:text-amber-400 mt-2">{{ $stats['pending_orders'] }} قيد المراجعة</p>
            @endif
        </div>
    </div>

    <div class="bg-forest-900 dark:bg-forest-950 text-white rounded-lg p-6 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-xs tracking-widest uppercase text-gold-400 mb-2">
                    <i class="fa-solid fa-sack-dollar"></i> إجمالي الإيرادات
                </p>
                <p class="font-display text-3xl md:text-4xl font-bold">{{ number_format($stats['revenue'], 0) }} ₪</p>
            </div>
            <div class="w-16 h-16 flex items-center justify-center bg-white/5 backdrop-blur rounded-full">
                <i class="fa-solid fa-coins text-3xl text-gold-400"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        @if($pendingStores->count() > 0)
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded">
                            <i class="fa-solid fa-clock text-sm"></i>
                        </div>
                        <h3 class="font-display font-bold text-ink dark:text-cream">
                            متاجر تنتظر الموافقة <span class="text-amber-600 dark:text-amber-400 text-sm">({{ $pendingStores->count() }})</span>
                        </h3>
                    </div>
                    <a href="{{ route('admin.stores.index', ['status' => 'pending']) }}"
                       class="text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity whitespace-nowrap">
                        عرض الكل <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    </a>
                </div>
                <div class="divide-y divide-stone-100 dark:divide-stone-800">
                    @foreach($pendingStores as $store)
                        <div class="p-4 flex items-center justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('admin.stores.show', $store) }}"
                                   class="font-medium text-sm text-ink dark:text-cream hover:text-forest-700 dark:hover:text-gold-400 truncate block">
                                    {{ $store->name }}
                                </a>
                                <p class="text-xs text-ink-muted dark:text-cream/50 mt-0.5">{{ $store->merchant->full_name }}</p>
                            </div>
                            <form action="{{ route('admin.stores.approve', $store) }}" method="POST">
                                @csrf @method('PUT')
                                <button class="inline-flex items-center gap-1.5 h-9 px-3 text-xs font-semibold tracking-widest uppercase bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 hover:bg-forest-800 dark:hover:bg-gold-400 rounded transition-colors whitespace-nowrap">
                                    <i class="fa-solid fa-check text-xs"></i>
                                    اعتماد
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
            <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <i class="fa-solid fa-receipt text-sm"></i>
                    </div>
                    <h3 class="font-display font-bold text-ink dark:text-cream">آخر الطلبات</h3>
                </div>
                <a href="{{ route('admin.orders.index') }}"
                   class="text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity whitespace-nowrap">
                    عرض الكل <i class="fa-solid fa-arrow-left text-[10px]"></i>
                </a>
            </div>
            @if($recentOrders->count() > 0)
                <div class="divide-y divide-stone-100 dark:divide-stone-800">
                    @foreach($recentOrders as $order)
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="p-4 flex justify-between items-center gap-4 hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors group">
                            <div class="min-w-0 flex-1">
                                <p class="font-mono font-medium text-sm text-forest-700 dark:text-gold-400 group-hover:underline">
                                    {{ $order->order_number }}
                                </p>
                                <p class="text-xs text-ink-muted dark:text-cream/50 mt-0.5 truncate">
                                    {{ $order->customer->full_name }}
                                </p>
                            </div>
                            <p class="font-display font-bold text-sm text-ink dark:text-cream whitespace-nowrap">
                                {{ number_format($order->total, 0) }} ₪
                            </p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                        <i class="fa-solid fa-inbox text-2xl text-ink-muted dark:text-cream/40"></i>
                    </div>
                    <p class="text-sm text-ink-muted dark:text-cream/60">لا توجد طلبات</p>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
        <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                </div>
                <h3 class="font-display font-bold text-ink dark:text-cream">آخر المستخدمين</h3>
            </div>
            <a href="{{ route('admin.users.index') }}"
               class="text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity whitespace-nowrap">
                عرض الكل <i class="fa-solid fa-arrow-left text-[10px]"></i>
            </a>
        </div>
        <div class="divide-y divide-stone-100 dark:divide-stone-800">
            @forelse($recentUsers as $user)
                <div class="p-4 flex items-center justify-between gap-3 hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 flex items-center justify-center font-bold text-sm shrink-0">
                            {{ mb_substr($user->full_name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-sm text-ink dark:text-cream truncate">{{ $user->full_name }}</p>
                            <p class="text-xs text-ink-muted dark:text-cream/50 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="shrink-0">
                        @switch($user->role)
                            @case('admin')
                                <span class="badge badge-dark"><i class="fa-solid fa-shield-halved text-[10px]"></i> مشرف</span>
                                @break
                            @case('merchant')
                                <span class="badge badge-forest"><i class="fa-solid fa-store text-[10px]"></i> تاجر</span>
                                @break
                            @default
                                <span class="badge badge-stone"><i class="fa-solid fa-user text-[10px]"></i> زبون</span>
                        @endswitch
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-ink-muted dark:text-cream/50 text-sm">
                    لا يوجد مستخدمون
                </div>
            @endforelse
        </div>
    </div>

@endsection