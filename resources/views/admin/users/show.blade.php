@extends('admin.layouts.app')

@section('title', 'تفاصيل المستخدم')
@section('page-title', 'تفاصيل المستخدم')

@section('content')

    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('admin.users.index') }}" class="hover:text-indigo-600">المستخدمون</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800">{{ $user->full_name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Info --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="w-24 h-24 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-4xl font-bold mx-auto mb-4">
                    {{ mb_substr($user->full_name, 0, 1) }}
                </div>
                <h2 class="text-xl font-bold">{{ $user->full_name }}</h2>
                <p class="text-gray-500 text-sm mb-4">{{ $user->email }}</p>

                <div class="flex justify-center gap-2 mb-4">
                    @switch($user->role)
                        @case('admin') <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">🛡️ مشرف</span> @break
                        @case('merchant') <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">🏪 تاجر</span> @break
                        @default <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">👤 زبون</span>
                    @endswitch

                    @if($user->status === 'active')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">✅ نشط</span>
                    @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">⛔ معلق</span>
                    @endif
                </div>

                @if($user->id !== auth()->id() && $user->role !== 'admin')
                    <div class="flex gap-2">
                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="flex-1">
                            @csrf @method('PUT')
                            <button class="w-full {{ $user->status === 'active' ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white py-2 rounded-lg text-sm">
                                {{ $user->status === 'active' ? '⛔ تعليق' : '✅ تفعيل' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('حذف المستخدم نهائياً؟')" class="flex-1">
                            @csrf @method('DELETE')
                            <button class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm">
                                🗑️ حذف
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Details --}}
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h3 class="font-bold mb-4">📋 معلومات</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">المعرّف:</span>
                        <span class="font-mono">#{{ $user->id }}</span>
                    </div>
                    @if($user->phone)
                        <div class="flex justify-between">
                            <span class="text-gray-500">الهاتف:</span>
                            <span>{{ $user->phone }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">التسجيل:</span>
                        <span>{{ $user->created_at->format('Y/m/d') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Stores --}}
            @if($user->role === 'merchant' && $user->stores->count() > 0)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-5 border-b">
                        <h3 class="font-bold">🏪 المتاجر ({{ $user->stores->count() }})</h3>
                    </div>
                    <div class="divide-y">
                        @foreach($user->stores as $store)
                            <div class="p-4 flex justify-between items-center">
                                <div>
                                    <a href="{{ route('admin.stores.show', $store) }}" 
                                       class="font-medium text-indigo-600 hover:underline">
                                        {{ $store->name }}
                                    </a>
                                    <p class="text-xs text-gray-500">{{ $store->products()->count() }} منتج</p>
                                </div>
                                <span class="text-xs {{ $store->status === 'active' ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $store->status === 'active' ? '✅ نشط' : '⛔ معطل' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Recent Orders --}}
            @if($user->orders->count() > 0)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-5 border-b">
                        <h3 class="font-bold">📦 آخر الطلبات ({{ $user->orders->count() }})</h3>
                    </div>
                    <div class="divide-y">
                        @foreach($user->orders as $order)
                            <div class="p-4 flex justify-between items-center">
                                <div>
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="font-mono font-medium text-indigo-600 hover:underline">
                                        {{ $order->order_number }}
                                    </a>
                                    <p class="text-xs text-gray-500">{{ $order->created_at->format('Y/m/d') }}</p>
                                </div>
                                <p class="font-bold">{{ number_format($order->total, 0) }} ₪</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection