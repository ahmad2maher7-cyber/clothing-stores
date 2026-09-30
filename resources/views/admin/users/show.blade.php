@extends('admin.layouts.app')

@section('title', 'تفاصيل المستخدم')
@section('page-title', 'تفاصيل المستخدم')

@section('content')

    {{-- ═══ Breadcrumb ═══ --}}
    <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">
        <a href="{{ route('admin.users.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
            المستخدمون
        </a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">{{ $user->full_name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ═══ Sidebar ═══ --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- User Card --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6 text-center">

                {{-- Avatar --}}
                <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-forest-50 dark:bg-forest-950/40 flex items-center justify-center text-4xl font-bold text-forest-700 dark:text-gold-400">
                    {{ mb_substr($user->full_name, 0, 1) }}
                </div>

                <h2 class="font-display text-xl font-bold text-ink dark:text-cream">{{ $user->full_name }}</h2>
                <p class="text-ink-muted dark:text-cream/60 text-sm mb-4">{{ $user->email }}</p>

                {{-- Badges --}}
                <div class="flex justify-center gap-2 mb-5">
                    @switch($user->role)
                        @case('admin')
                            <span class="badge badge-dark">🛡️ مشرف</span>
                            @break
                        @case('merchant')
                            <span class="badge badge-forest">🏪 تاجر</span>
                            @break
                        @default
                            <span class="badge badge-stone">👤 زبون</span>
                    @endswitch

                    @if($user->status === 'active')
                        <span class="badge badge-forest">✅ نشط</span>
                    @else
                        <span class="badge badge-danger">⛔ معلق</span>
                    @endif
                </div>

                {{-- Actions --}}
                @if($user->id !== auth()->id() && $user->role !== 'admin')
                    <div class="grid grid-cols-2 gap-2">
                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                            @csrf @method('PUT')
                            <button class="w-full inline-flex items-center justify-center gap-1.5 h-10 px-3 text-xs font-semibold tracking-widest uppercase rounded transition-colors
                                    {{ $user->status === 'active' 
                                       ? 'text-amber-600 dark:text-amber-400 border border-stone-300 dark:border-stone-700 hover:border-amber-300 dark:hover:border-amber-800 hover:bg-amber-50 dark:hover:bg-amber-950/20' 
                                       : 'bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 hover:bg-forest-800 dark:hover:bg-gold-400' }}">
                                {{ $user->status === 'active' ? '⛔ تعليق' : '✓ تفعيل' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('حذف المستخدم نهائياً؟')">
                            @csrf @method('DELETE')
                            <button class="w-full inline-flex items-center justify-center gap-1.5 h-10 px-3 text-xs font-semibold tracking-widest uppercase text-red-600 dark:text-red-400 border border-stone-300 dark:border-stone-700 hover:border-red-300 dark:hover:border-red-800 hover:bg-red-50 dark:hover:bg-red-950/20 rounded transition-colors">
                                🗑️ حذف
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Details --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-ink dark:text-cream">معلومات</h3>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-ink-muted dark:text-cream/50">المعرّف:</span>
                        <span class="font-mono text-ink dark:text-cream">#{{ $user->id }}</span>
                    </div>
                    @if($user->phone)
                        <div class="flex justify-between items-center">
                            <span class="text-ink-muted dark:text-cream/50">الهاتف:</span>
                            <span class="text-ink dark:text-cream" dir="ltr">{{ $user->phone }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-ink-muted dark:text-cream/50">التسجيل:</span>
                        <span class="text-ink dark:text-cream">{{ $user->created_at->format('Y/m/d') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ Activity ═══ --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Stores (Merchant) --}}
            @if($user->role === 'merchant' && $user->stores->count() > 0)
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                    <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="font-display font-bold text-ink dark:text-cream">
                            المتاجر <span class="text-ink-muted dark:text-cream/50 text-sm">({{ $user->stores->count() }})</span>
                        </h3>
                    </div>
                    <div class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($user->stores as $store)
                            <div class="p-4 flex justify-between items-center gap-4 hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('admin.stores.show', $store) }}"
                                       class="font-medium text-forest-700 dark:text-gold-400 hover:underline">
                                        {{ $store->name }}
                                    </a>
                                    <p class="text-xs text-ink-muted dark:text-cream/50 mt-0.5">
                                        {{ $store->products()->count() }} منتج
                                    </p>
                                </div>
                                @if($store->status === 'active')
                                    <span class="badge badge-forest whitespace-nowrap">نشط</span>
                                @else
                                    <span class="badge badge-stone whitespace-nowrap">معطل</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Recent Orders --}}
            @if($user->orders->count() > 0)
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                    <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h3 class="font-display font-bold text-ink dark:text-cream">
                            آخر الطلبات <span class="text-ink-muted dark:text-cream/50 text-sm">({{ $user->orders->count() }})</span>
                        </h3>
                    </div>
                    <div class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($user->orders as $order)
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="p-4 flex justify-between items-center gap-4 hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors group">
                                <div class="flex-1 min-w-0">
                                    <p class="font-mono font-medium text-forest-700 dark:text-gold-400 group-hover:underline">
                                        {{ $order->order_number }}
                                    </p>
                                    <p class="text-xs text-ink-muted dark:text-cream/50 mt-0.5">
                                        {{ $order->created_at->format('Y/m/d') }}
                                    </p>
                                </div>
                                <p class="font-display font-bold text-ink dark:text-cream whitespace-nowrap">
                                    {{ number_format($order->total, 0) }} ₪
                                </p>
                                <svg class="w-4 h-4 text-ink-muted dark:text-cream/40 group-hover:text-forest-700 dark:group-hover:text-gold-400 group-hover:-translate-x-1 transition-all"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Empty State --}}
            @if(($user->role !== 'merchant' || $user->stores->count() === 0) && $user->orders->count() === 0)
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg text-center py-16">
                    <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                        <svg class="w-8 h-8 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <p class="text-sm text-ink-muted dark:text-cream/60">لا يوجد نشاط لهذا المستخدم</p>
                </div>
            @endif
        </div>
    </div>

@endsection