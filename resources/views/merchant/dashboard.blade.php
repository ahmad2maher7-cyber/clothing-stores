@extends('merchant.layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')

    {{-- Welcome --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">
            مرحباً {{ auth()->user()->full_name }} 👋
        </h1>
        <p class="text-sm text-gray-500">إليك نظرة سريعة على متجرك</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Products --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-2xl">👕</span>
                <span class="text-xs text-gray-400">المنتجات</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['products'] }}</p>
            <a href="{{ route('merchant.products.index') }}" 
               class="text-xs text-gray-500 hover:text-gray-900 mt-2 inline-block">
                إدارة المنتجات ←
            </a>
        </div>

        {{-- Orders --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-2xl">🛒</span>
                <span class="text-xs text-gray-400">الطلبات</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['orders'] }}</p>
            <a href="{{ route('merchant.orders.index') }}" 
               class="text-xs text-gray-500 hover:text-gray-900 mt-2 inline-block">
                إدارة الطلبات ←
            </a>
        </div>

        {{-- Pending --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-2xl">⏳</span>
                <span class="text-xs text-gray-400">قيد الانتظار</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_orders'] }}</p>
            @if($stats['pending_orders'] > 0)
                <a href="{{ route('merchant.orders.index', ['status' => 'pending']) }}" 
                   class="text-xs text-gray-500 hover:text-gray-900 mt-2 inline-block">
                    عرض الطلبات ←
                </a>
            @else
                <p class="text-xs text-gray-400 mt-2">لا توجد طلبات معلقة</p>
            @endif
        </div>

        {{-- Revenue --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-2xl">💰</span>
                <span class="text-xs text-gray-400">الإيرادات</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['revenue'], 0) }} ₪</p>
            <p class="text-xs text-gray-400 mt-2">إجمالي المدخول</p>
        </div>
    </div>

    {{-- Quick Actions + Low Stock --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Quick Actions --}}
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="p-5 border-b border-gray-200">
                <h3 class="font-bold text-gray-900">⚡ إجراءات سريعة</h3>
            </div>
            <div class="p-5 grid grid-cols-2 gap-3">
                <a href="{{ route('merchant.products.create') }}"
                   class="flex flex-col items-center gap-2 p-4 border border-gray-200 rounded-md hover:border-gray-400 hover:bg-gray-50 transition text-center">
                    <span class="text-2xl">➕</span>
                    <span class="text-sm text-gray-700">إضافة منتج</span>
                </a>

                <a href="{{ route('merchant.categories.create') }}"
                   class="flex flex-col items-center gap-2 p-4 border border-gray-200 rounded-md hover:border-gray-400 hover:bg-gray-50 transition text-center">
                    <span class="text-2xl">📂</span>
                    <span class="text-sm text-gray-700">إضافة تصنيف</span>
                </a>

                <a href="{{ route('merchant.coupons.create') }}"
                   class="flex flex-col items-center gap-2 p-4 border border-gray-200 rounded-md hover:border-gray-400 hover:bg-gray-50 transition text-center">
                    <span class="text-2xl">🎟️</span>
                    <span class="text-sm text-gray-700">إنشاء كوبون</span>
                </a>

                <a href="{{ route('merchant.offers.create') }}"
                   class="flex flex-col items-center gap-2 p-4 border border-gray-200 rounded-md hover:border-gray-400 hover:bg-gray-50 transition text-center">
                    <span class="text-2xl">🔥</span>
                    <span class="text-sm text-gray-700">إضافة عرض</span>
                </a>
            </div>
        </div>

        {{-- Low Stock Alert --}}
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="p-5 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">⚠️ تنبيهات المخزون</h3>
                <a href="{{ route('merchant.inventory.index', ['filter' => 'low_stock']) }}" 
                   class="text-xs text-gray-500 hover:text-gray-900">
                    عرض الكل ←
                </a>
            </div>
            <div class="p-5">
                @if($stats['low_stock'] > 0)
                    <div class="border border-gray-200 rounded-md p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-2xl">📦</span>
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ $stats['low_stock'] }} منتج بمخزون منخفض
                                </p>
                                <p class="text-xs text-gray-500">تحتاج إعادة تعبئة</p>
                            </div>
                        </div>
                        <a href="{{ route('merchant.inventory.index', ['filter' => 'low_stock']) }}" 
                           class="btn-secondary btn-sm w-full">
                            عرض القائمة
                        </a>
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="text-4xl mb-2">✅</div>
                        <p class="text-sm text-gray-600">جميع المنتجات بمخزون جيد</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection