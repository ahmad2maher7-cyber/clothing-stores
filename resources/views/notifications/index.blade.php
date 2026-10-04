@extends('layouts.public')

@section('title', 'الإشعارات')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">🔔 الإشعارات</h1>
            <p class="text-gray-500 text-sm">آخر التحديثات والأحداث</p>
        </div>

        @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                    ✅ تعليم الكل كمقروء
                </button>
            </form>
        @endif
    </div>

    {{-- Notifications --}}
    @if($notifications->count() > 0)
        <div class="space-y-2">
            @foreach($notifications as $notif)
                <div class="bg-white rounded-lg border {{ !$notif->is_read ? 'border-blue-300 bg-blue-50' : 'border-gray-200' }} p-4 hover:shadow-sm transition">
                    <div class="flex gap-3">

                        {{-- Icon --}}
                        <div class="w-11 h-11 rounded-full flex items-center justify-center shrink-0
                            @switch($notif->type)
                                @case('order') bg-blue-100 text-blue-600 @break
                                @case('review') bg-yellow-100 text-yellow-600 @break
                                @case('store') bg-green-100 text-green-600 @break
                                @case('welcome') bg-purple-100 text-purple-600 @break
                                @case('stock') bg-red-100 text-red-600 @break
                                @default bg-gray-100 text-gray-600
                            @endswitch">
                            <i class="fa-solid
                                @switch($notif->type)
                                    @case('order') fa-cart-shopping @break
                                    @case('review') fa-star @break
                                    @case('store') fa-store @break
                                    @case('welcome') fa-gift @break
                                    @case('stock') fa-triangle-exclamation @break
                                    @default fa-bell
                                @endswitch"></i>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-2">
                                <h3 class="font-semibold text-gray-900">{{ $notif->title }}</h3>
                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $notif->body }}</p>

                            {{-- Actions --}}
                            <div class="flex gap-2 mt-2">
                                @if($notif->action_url)
                                    <a href="{{ $notif->action_url }}"
                                       class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                        عرض التفاصيل →
                                    </a>
                                @endif

                                @if(!$notif->is_read)
                                    <form action="{{ route('notifications.read', $notif) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-xs text-gray-500 hover:text-gray-700">
                                            تعليم كمقروء
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('notifications.destroy', $notif) }}" method="POST" class="inline ml-auto"
                                      onsubmit="return confirm('حذف الإشعار؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs text-red-500 hover:text-red-700">
                                        🗑️ حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $notifications->links() }}</div>
    @else
        <div class="bg-white rounded-lg border border-gray-200 text-center py-16">
            <i class="fa-solid fa-bell-slash text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد إشعارات</h3>
            <p class="text-gray-500">ستظهر هنا آخر التحديثات</p>
        </div>
    @endif
</div>

@endsection