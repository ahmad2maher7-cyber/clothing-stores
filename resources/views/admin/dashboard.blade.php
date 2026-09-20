@extends('admin.layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')

    {{-- Welcome --}}
    <div class="bg-gradient-to-l from-gray-900 to-gray-700 text-white rounded-xl p-6 mb-6">
        <h2 class="text-2xl font-bold mb-1">مرحباً {{ auth()->user()->full_name }} 🛡️</h2>
        <p class="text-gray-300">إليك نظرة شاملة على المنصة</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5 border-r-4 border-indigo-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-3xl">👥</span>
            </div>
            <p class="text-xs text-gray-500 mb-1">المستخدمون</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['users'] }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $stats['merchants'] }} تاجر • {{ $stats['customers'] }} زبون
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-r-4 border-green-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-3xl">🏪</span>
            </div>
            <p class="text-xs text-gray-500 mb-1">المتاجر</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['stores'] }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $stats['active_stores'] }} نشط
                @if($stats['pending_stores'] > 0)
                    • <span class="text-yellow-600">{{ $stats['pending_stores'] }} معلق</span>
                @endif
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-r-4 border-blue-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-3xl">👕</span>
            </div>
            <p class="text-xs text-gray-500 mb-1">المنتجات</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['products'] }}</p>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-r-4 border-orange-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-3xl">🛒</span>
            </div>
            <p class="text-xs text-gray-500 mb-1">الطلبات</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['orders'] }}</p>
            @if($stats['pending_orders'] > 0)
                <p class="text-xs text-orange-600 mt-1">{{ $stats['pending_orders'] }} قيد المراجعة</p>
            @endif
        </div>
    </div>

    {{-- Revenue --}}
    <div class="bg-gradient-to-l from-green-600 to-emerald-700 text-white rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm mb-1">💰 إجمالي الإيرادات</p>
                <p class="text-3xl md:text-4xl font-bold">{{ number_format($stats['revenue'], 0) }} ₪</p>
            </div>
            <span class="text-6xl">💳</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- Pending Stores --}}
        @if($pendingStores->count() > 0)
            <div class="bg-white rounded-xl shadow">
                <div class="p-5 border-b flex justify-between items-center">
                    <h3 class="font-bold">⏳ متاجر تنتظر الموافقة</h3>
                    <a href="{{ route('admin.stores.index', ['status' => 'pending']) }}" class="text-indigo-600 text-sm">
                        عرض الكل →
                    </a>
                </div>
                <div class="divide-y">
                    @foreach($pendingStores as $store)
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <a href="{{ route('admin.stores.show', $store) }}" 
                                   class="font-medium text-gray-800 hover:text-indigo-600">
                                    {{ $store->name }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $store->merchant->full_name }}</p>
                            </div>
                            <form action="{{ route('admin.stores.approve', $store) }}" method="POST">
                                @csrf @method('PUT')
                                <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-sm">
                                    ✅ اعتماد
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Recent Orders --}}
        <div class="bg-white rounded-xl shadow">
            <div class="p-5 border-b flex justify-between items-center">
                <h3 class="font-bold">📦 آخر الطلبات</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 text-sm">
                    عرض الكل →
                </a>
            </div>
            @if($recentOrders->count() > 0)
                <div class="divide-y">
                    @foreach($recentOrders as $order)
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="p-4 flex justify-between items-center hover:bg-gray-50">
                            <div>
                                <p class="font-mono font-medium text-indigo-600">{{ $order->order_number }}</p>
                                <p class="text-xs text-gray-500">{{ $order->customer->full_name }}</p>
                            </div>
                            <p class="font-bold text-gray-800">{{ number_format($order->total, 0) }} ₪</p>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="bg-white rounded-xl shadow">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-bold">👥 آخر المستخدمين</h3>
            <a href="{{ route('admin.users.index') }}" class="text-indigo-600 text-sm">
                عرض الكل →
            </a>
        </div>
        <div class="divide-y">
            @foreach($recentUsers as $user)
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                            {{ mb_substr($user->full_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium">{{ $user->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @switch($user->role)
                            @case('admin') <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded">مشرف</span> @break
                            @case('merchant') <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">تاجر</span> @break
                            @default <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">زبون</span>
                        @endswitch
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection