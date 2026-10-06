@extends('merchant.layouts.app')

@section('title', 'المحادثات')
@section('page-title', 'المحادثات')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">💬 المحادثات</h2>
    <p class="text-gray-500 text-sm">تواصل مع زبائنك</p>
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
            <a href="{{ route('merchant.chat.show', $conv) }}"
               class="flex items-center gap-4 p-4 hover:bg-gray-50 transition">
                <div class="w-14 h-14 rounded-full bg-forest-900 text-white flex items-center justify-center text-xl font-bold shrink-0">
                    {{ mb_substr($conv->customer->full_name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="font-medium text-gray-900">{{ $conv->customer->full_name }}</h3>
                        @if($lastMessage)
                            <span class="text-xs text-gray-400">
                                {{ $lastMessage->created_at->diffForHumans() }}
                            </span>
                        @endif
                    </div>
                    @if($lastMessage)
                        <p class="text-sm text-gray-500 truncate">{{ $lastMessage->message }}</p>
                    @else
                        <p class="text-sm text-gray-400">لا توجد رسائل بعد</p>
                    @endif
                </div>
                @if($unreadCount > 0)
                    <span class="min-w-[24px] h-6 px-2 flex items-center justify-center text-xs font-bold bg-red-600 text-white rounded-full">
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
        <p class="text-gray-500">ستظهر محادثات الزبائن هنا</p>
    </div>
@endif

@endsection