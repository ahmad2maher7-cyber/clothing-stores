@extends('layouts.public')

@section('title', 'محادثاتي')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-1">💬 محادثاتي</h1>
        <p class="text-gray-500 text-sm">تواصل مع المتاجر مباشرة</p>
    </div>

    @if($conversations->count() > 0)
        <div class="bg-white rounded-lg border border-gray-200 divide-y">
            @foreach($conversations as $conv)
                @php
                    $lastMessage = $conv->messages->first();
                    $unreadCount = $conv->messages()
                        ->where('sender_id', '!=', auth()->id())
                        ->where('is_read', false)
                        ->count();
                @endphp
                <a href="{{ route('customer.chat.show', $conv) }}"
                   class="flex items-center gap-4 p-4 hover:bg-gray-50 transition">
                    <div class="w-14 h-14 rounded-full bg-forest-50 text-forest-700 flex items-center justify-center text-2xl shrink-0">
                        🏪
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-medium text-gray-900">{{ $conv->store->name }}</h3>
                            @if($lastMessage)
                                <span class="text-xs text-gray-400">
                                    {{ $lastMessage->created_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                        @if($lastMessage)
                            <p class="text-sm text-gray-500 truncate">
                                @if($lastMessage->sender_id === auth()->id())
                                    أنت: 
                                @endif
                                {{ $lastMessage->message }}
                            </p>
                        @else
                            <p class="text-sm text-gray-400">لا توجد رسائل بعد</p>
                        @endif
                    </div>
                    @if($unreadCount > 0)
                        <span class="min-w-[24px] h-6 px-2 flex items-center justify-center text-xs font-bold bg-forest-700 text-white rounded-full">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg border border-gray-200 text-center py-16">
            <div class="text-6xl mb-4">💬</div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد محادثات</h3>
            <p class="text-gray-500 mb-4">ابدأ محادثة من صفحة أي متجر</p>
            <a href="{{ route('stores.index') }}" class="btn-primary">تصفح المتاجر</a>
        </div>
    @endif
</div>
@endsection
