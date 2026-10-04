@extends('layouts.public')

@section('title', 'الإشعارات')

@section('content')

    <div class="max-w-4xl mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-gray-900">الرئيسية</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-gray-900">الإشعارات</span>
        </nav>

        {{-- Header --}}
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="font-display text-2xl font-bold text-ink dark:text-cream">الإشعارات</h1>
                <p class="text-sm text-ink-muted dark:text-cream/60">
                    @php
                        $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();
                    @endphp
                    {{ $unreadCount }} غير مقروءة
                </p>
            </div>

            @if($unreadCount > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn-secondary btn-sm">
                        <i class="fa-solid fa-check-double text-xs"></i>
                        تعليم الكل كمقروء
                    </button>
                </form>
            @endif
        </div>

        {{-- Notifications --}}
        @if($notifications->count() > 0)
            <div class="space-y-2">
                @foreach($notifications as $notification)
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 flex gap-3
                                {{ !$notification->is_read ? 'border-r-4 border-r-forest-700 dark:border-r-gold-400' : '' }}">

                        {{-- Icon --}}
                        <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded-full">
                            @php
                                $icons = [
                                    'login' => 'fa-right-to-bracket',
                                    'order_created' => 'fa-cart-shopping',
                                    'order_placed' => 'fa-check',
                                    'order_status_changed' => 'fa-truck',
                                    'product_created' => 'fa-shirt',
                                    'review_created' => 'fa-star',
                                    'review_approved' => 'fa-circle-check',
                                    'offer_created' => 'fa-fire',
                                ];
                            @endphp
                            <i class="fa-solid {{ $icons[$notification->type] ?? 'fa-bell' }}"></i>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="font-medium text-ink dark:text-cream">{{ $notification->title }}</h3>
                                @if(!$notification->is_read)
                                    <span class="badge badge-forest">جديد</span>
                                @endif
                            </div>
                            <p class="text-sm text-ink-muted dark:text-cream/60 mt-1">{{ $notification->body }}</p>
                            <p class="text-xs text-ink-faint dark:text-cream/40 mt-2">
                                <i class="fa-regular fa-clock text-[10px] ml-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        @if(!$notification->is_read)
                            <form action="{{ route('notifications.markAsRead', $notification) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" title="تعليم كمقروء"
                                        class="text-ink-muted hover:text-forest-700 dark:hover:text-gold-400 transition">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-center">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <i class="fa-solid fa-bell-slash text-4xl text-ink-muted dark:text-cream/40"></i>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد إشعارات</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60">ستظهر إشعاراتك هنا</p>
            </div>
        @endif
    </div>

@endsection