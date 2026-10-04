@extends(auth()->user()->role === 'merchant' ? 'merchant.layouts.app' : (auth()->user()->role === 'admin' ? 'admin.layouts.app' : 'customer.layouts.app'))

@section('title', 'كل الإشعارات')
@section('page-title', 'كل الإشعارات')

@section('content')

    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-ink dark:text-cream">كل الإشعارات</h2>
                <p class="text-sm text-ink-muted dark:text-cream/60">
                    {{ $notifications->total() }} إشعار
                </p>
            </div>

            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" id="markAllForm">
                @csrf
                @method('PUT')
                <button type="button" onclick="document.getElementById('markAllForm').submit()"
                        class="btn-secondary btn-sm">
                    <i class="fa-solid fa-check-double"></i>
                    تعليم الكل كمقروء
                </button>
            </form>
        </div>

        {{-- Notifications --}}
        @if($notifications->count() > 0)
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="divide-y divide-stone-100 dark:divide-stone-800">
                    @foreach($notifications as $notification)
                        <div class="p-5 hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition
                                    {{ !$notification->is_read ? 'bg-forest-50/50 dark:bg-forest-950/20' : '' }}">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 flex items-center justify-center rounded shrink-0
                                            @switch($notification->type)
                                                @case('login') bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 @break
                                                @case('order_created') bg-forest-50 text-forest-700 dark:bg-forest-950/40 dark:text-gold-400 @break
                                                @case('order_placed') bg-green-50 text-green-600 dark:bg-green-950/40 dark:text-green-400 @break
                                                @case('order_status_changed') bg-orange-50 text-orange-600 dark:bg-orange-950/40 dark:text-orange-400 @break
                                                @case('product_created') bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400 @break
                                                @case('review_created') bg-yellow-50 text-yellow-600 dark:bg-yellow-950/40 dark:text-yellow-400 @break
                                                @default bg-stone-100 text-stone-600 dark:bg-zinc-800 dark:text-cream/60
                                            @endswitch">
                                    <i class="fa-solid text-lg
                                              @switch($notification->type)
                                                @case('login') fa-right-to-bracket @break
                                                @case('order_created') fa-shopping-cart @break
                                                @case('order_placed') fa-check-circle @break
                                                @case('order_status_changed') fa-rotate @break
                                                @case('product_created') fa-box @break
                                                @case('review_created') fa-star @break
                                                @default fa-bell
                                              @endswitch"></i>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3">
                                        <h3 class="font-bold text-ink dark:text-cream">{{ $notification->title }}</h3>
                                        @if(!$notification->is_read)
                                            <span class="text-xs px-2 py-0.5 bg-forest-700 text-white rounded shrink-0">
                                                جديد
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-ink-muted dark:text-cream/70 mt-1">{{ $notification->body }}</p>
                                    <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">
                                        <i class="fa-regular fa-clock text-[10px] ml-1"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                        <span class="mx-2">•</span>
                                        {{ $notification->created_at->format('Y/m/d H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <i class="fa-regular fa-bell text-4xl text-ink-muted dark:text-cream/40"></i>
                </div>
                <h3 class="font-bold text-lg text-ink dark:text-cream mb-2">لا توجد إشعارات</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60">ستظهر الإشعارات هنا عند وصولها</p>
            </div>
        @endif
    </div>

@endsection