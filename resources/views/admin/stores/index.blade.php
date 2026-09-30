@extends('admin.layouts.app')

@section('title', 'المتاجر')
@section('page-title', 'المتاجر')

@section('content')

    {{-- ═══ Header ═══ --}}
    <div class="mb-6">
        <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">المتاجر</h2>
        <p class="text-sm text-ink-muted dark:text-cream/60">إدارة والموافقة على المتاجر</p>
    </div>

    {{-- ═══ Stats ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('admin.stores.index') }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-5 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ !request('status') ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">الكل</p>
            <p class="font-display text-2xl font-bold text-ink dark:text-cream">{{ $stats['all'] }}</p>
        </a>
        <a href="{{ route('admin.stores.index', ['status' => 'active']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-5 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('status') == 'active' ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">✅ نشط</p>
            <p class="font-display text-2xl font-bold text-forest-700 dark:text-gold-400">{{ $stats['active'] }}</p>
        </a>
        <a href="{{ route('admin.stores.index', ['status' => 'pending']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-5 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('status') == 'pending' ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">⏳ معلق</p>
            <p class="font-display text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending'] }}</p>
        </a>
        <a href="{{ route('admin.stores.index', ['status' => 'inactive']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-5 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('status') == 'inactive' ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">⛔ موقوف</p>
            <p class="font-display text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['inactive'] }}</p>
        </a>
    </div>

    {{-- ═══ Search ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 mb-6">
        <form method="GET" class="flex gap-2">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث باسم المتجر..."
                       class="form-input pl-10">
                <svg class="w-4 h-4 text-ink-muted dark:text-cream/40 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="btn-solid">بحث</button>
        </form>
    </div>

    {{-- ═══ Grid ═══ --}}
    @if($stores->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
            @foreach($stores as $store)
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden hover:border-forest-500 dark:hover:border-gold-500 transition group">

                    {{-- Banner --}}
                    <div class="h-32 overflow-hidden">
                        @if($store->banner)
                            <img src="{{ asset('storage/' . $store->banner) }}"
                                 alt="{{ $store->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-forest-700 to-gold-500 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5">
                        <div class="flex justify-between items-start gap-3 mb-3">
                            <h3 class="font-display font-bold text-ink dark:text-cream line-clamp-1 group-hover:text-forest-700 dark:group-hover:text-gold-400 transition-colors">
                                {{ $store->name }}
                            </h3>
                            @if($store->status === 'active')
                                <span class="badge badge-forest">نشط</span>
                            @elseif($store->status === 'pending')
                                <span class="badge badge-stone">معلق</span>
                            @else
                                <span class="badge badge-danger">موقوف</span>
                            @endif
                        </div>

                        <p class="text-xs text-ink-muted dark:text-cream/60 mb-3 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $store->merchant->full_name }}
                        </p>

                        <div class="flex gap-2 mb-4">
                            <span class="badge badge-stone">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                {{ $store->products_count }}
                            </span>
                            <span class="badge badge-stone">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                {{ $store->orders_count }}
                            </span>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.stores.show', $store) }}"
                               class="flex-1 inline-flex items-center justify-center gap-2 h-9 px-3 text-xs font-semibold tracking-widest uppercase text-ink-soft dark:text-cream/70 border border-stone-300 dark:border-stone-700 hover:border-forest-500 dark:hover:border-gold-400 hover:text-forest-700 dark:hover:text-gold-400 rounded transition-colors">
                                عرض
                            </a>

                            @if($store->status === 'pending')
                                <form action="{{ route('admin.stores.approve', $store) }}" method="POST" class="flex-1">
                                    @csrf @method('PUT')
                                    <button class="w-full inline-flex items-center justify-center gap-1.5 h-9 px-3 text-xs font-semibold tracking-widest uppercase bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 hover:bg-forest-800 dark:hover:bg-gold-400 rounded transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        اعتماد
                                    </button>
                                </form>
                            @elseif($store->status === 'active')
                                <form action="{{ route('admin.stores.suspend', $store) }}" method="POST" class="flex-1">
                                    @csrf @method('PUT')
                                    <button class="w-full inline-flex items-center justify-center gap-1.5 h-9 px-3 text-xs font-semibold tracking-widest uppercase text-red-600 dark:text-red-400 border border-stone-300 dark:border-stone-700 hover:border-red-300 dark:hover:border-red-800 hover:bg-red-50 dark:hover:bg-red-950/20 rounded transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        إيقاف
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.stores.approve', $store) }}" method="POST" class="flex-1">
                                    @csrf @method('PUT')
                                    <button class="w-full inline-flex items-center justify-center gap-1.5 h-9 px-3 text-xs font-semibold tracking-widest uppercase bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 hover:bg-forest-800 dark:hover:bg-gold-400 rounded transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        تفعيل
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-center">{{ $stores->links() }}</div>
    @else
        {{-- ═══ Empty State ═══ --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg text-center py-20">
            <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                <svg class="w-10 h-10 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد متاجر</h3>
            <p class="text-sm text-ink-muted dark:text-cream/60">لم يتم العثور على متاجر مطابقة</p>
        </div>
    @endif

@endsection