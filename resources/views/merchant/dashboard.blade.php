@extends('merchant.layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
    {{-- Welcome Section --}}
    <div class="bg-gradient-to-l from-indigo-600 to-indigo-800 text-white rounded-xl p-6 mb-6 shadow-lg">
        <h2 class="text-2xl font-bold mb-2">مرحباً {{ auth()->user()->full_name }} 👋</h2>
        <p class="text-indigo-100">إليك نظرة سريعة على أداء متجرك اليوم</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        {{-- Products --}}
        <div class="bg-white rounded-xl shadow p-6 border-r-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">إجمالي المنتجات</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['products'] }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-2xl">
                    👕
                </div>
            </div>
        </div>

        {{-- Orders --}}
        <div class="bg-white rounded-xl shadow p-6 border-r-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">إجمالي الطلبات</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['orders'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-2xl">
                    🛒
                </div>
            </div>
        </div>

        {{-- Pending Orders --}}
        <div class="bg-white rounded-xl shadow p-6 border-r-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">طلبات قيد الانتظار</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_orders'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-2xl">
                    ⏳
                </div>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="bg-white rounded-xl shadow p-6 border-r-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">الإيرادات</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['revenue'], 0) }} ₪</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-2xl">
                    💰
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions + Low Stock --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">⚡ إجراءات سريعة</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="#" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 p-4 rounded-lg text-center transition">
                    <span class="text-3xl block mb-2">➕</span>
                    <span class="text-sm font-medium">إضافة منتج</span>
                </a>
                <a href="#" class="bg-green-50 hover:bg-green-100 text-green-700 p-4 rounded-lg text-center transition">
                    <span class="text-3xl block mb-2">📂</span>
                    <span class="text-sm font-medium">إضافة تصنيف</span>
                </a>
                <a href="#" class="bg-orange-50 hover:bg-orange-100 text-orange-700 p-4 rounded-lg text-center transition">
                    <span class="text-3xl block mb-2">🎟️</span>
                    <span class="text-sm font-medium">إنشاء كوبون</span>
                </a>
                <a href="#" class="bg-purple-50 hover:bg-purple-100 text-purple-700 p-4 rounded-lg text-center transition">
                    <span class="text-3xl block mb-2">🔥</span>
                    <span class="text-sm font-medium">إضافة عرض</span>
                </a>
            </div>
        </div>

        {{-- Low Stock Alert --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">⚠️ تنبيهات المخزون</h3>
            @if($stats['low_stock'] > 0)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-700 font-medium">
                        يوجد <strong>{{ $stats['low_stock'] }}</strong> منتج بمخزون منخفض
                    </p>
                    <a href="#" class="text-red-600 text-sm underline mt-2 inline-block">
                        عرض التفاصيل →
                    </a>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-green-700 font-medium">✅ جميع المنتجات بمخزون جيد</p>
                </div>
            @endif
        </div>
    </div>
@endsection