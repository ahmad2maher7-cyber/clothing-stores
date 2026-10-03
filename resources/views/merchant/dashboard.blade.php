@extends('merchant.layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')

    {{-- Welcome --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">
            مرحباً {{ auth()->user()->full_name }} 
            <i class="fas fa-hand-sparkles text-yellow-500"></i>
        </h1>
        <p class="text-sm text-gray-500">
            <i class="fas fa-chart-line text-gray-400 ml-1"></i>
            إليك نظرة سريعة على متجرك
        </p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Products --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <i class="fas fa-tshirt"></i>
                </div>
                <span class="text-xs text-gray-400 uppercase tracking-wider">المنتجات</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['products'] }}</p>
            <a href="{{ route('merchant.products.index') }}" 
               class="text-xs text-gray-500 hover:text-forest-700 dark:hover:text-gold-400 mt-2 inline-flex items-center gap-1 transition">
                إدارة المنتجات
                <i class="fas fa-arrow-left text-[10px]"></i>
            </a>
        </div>

        {{-- Orders --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-700 rounded">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <span class="text-xs text-gray-400 uppercase tracking-wider">الطلبات</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['orders'] }}</p>
            <a href="{{ route('merchant.orders.index') }}" 
               class="text-xs text-gray-500 hover:text-forest-700 dark:hover:text-gold-400 mt-2 inline-flex items-center gap-1 transition">
                إدارة الطلبات
                <i class="fas fa-arrow-left text-[10px]"></i>
            </a>
        </div>

        {{-- Pending --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 flex items-center justify-center bg-orange-50 text-orange-700 rounded">
                    <i class="fas fa-clock"></i>
                </div>
                <span class="text-xs text-gray-400 uppercase tracking-wider">قيد الانتظار</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_orders'] }}</p>
            @if($stats['pending_orders'] > 0)
                <a href="{{ route('merchant.orders.index', ['status' => 'pending']) }}" 
                   class="text-xs text-gray-500 hover:text-forest-700 dark:hover:text-gold-400 mt-2 inline-flex items-center gap-1 transition">
                    عرض الطلبات
                    <i class="fas fa-arrow-left text-[10px]"></i>
                </a>
            @else
                <p class="text-xs text-gray-400 mt-2">
                    <i class="fas fa-check-circle text-green-500 ml-1"></i>
                    لا توجد طلبات معلقة
                </p>
            @endif
        </div>

        {{-- Revenue --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 flex items-center justify-center bg-green-50 text-green-700 rounded">
                    <i class="fas fa-coins"></i>
                </div>
                <span class="text-xs text-gray-400 uppercase tracking-wider">الإيرادات</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['revenue'], 0) }} ₪</p>
            <p class="text-xs text-gray-400 mt-2">
                <i class="fas fa-chart-pie text-gray-400 ml-1"></i>
                إجمالي المدخول
            </p>
        </div>
    </div>

    {{-- Quick Actions + Low Stock --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Quick Actions --}}
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="p-5 border-b border-gray-200 flex items-center gap-2">
                <i class="fas fa-bolt text-yellow-500"></i>
                <h3 class="font-bold text-gray-900">إجراءات سريعة</h3>
            </div>
            <div class="p-5 grid grid-cols-2 gap-3">
                <a href="{{ route('merchant.products.create') }}"
                   class="flex flex-col items-center gap-2 p-4 border border-gray-200 rounded-md hover:border-forest-500 dark:hover:border-gold-400 hover:bg-forest-50 dark:hover:bg-forest-950/20 transition text-center group">
                    <div class="w-12 h-12 flex items-center justify-center bg-forest-100 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded group-hover:scale-110 transition">
                        <i class="fas fa-plus-circle text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-cream">إضافة منتج</span>
                </a>

                <a href="{{ route('merchant.categories.create') }}"
                   class="flex flex-col items-center gap-2 p-4 border border-gray-200 rounded-md hover:border-forest-500 dark:hover:border-gold-400 hover:bg-forest-50 dark:hover:bg-forest-950/20 transition text-center group">
                    <div class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded group-hover:scale-110 transition">
                        <i class="fas fa-folder-plus text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-cream">إضافة تصنيف</span>
                </a>

                <a href="{{ route('merchant.coupons.create') }}"
                   class="flex flex-col items-center gap-2 p-4 border border-gray-200 rounded-md hover:border-forest-500 dark:hover:border-gold-400 hover:bg-forest-50 dark:hover:bg-forest-950/20 transition text-center group">
                    <div class="w-12 h-12 flex items-center justify-center bg-purple-100 text-purple-700 rounded group-hover:scale-110 transition">
                        <i class="fas fa-ticket-alt text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-cream">إنشاء كوبون</span>
                </a>

                <a href="{{ route('merchant.offers.create') }}"
                   class="flex flex-col items-center gap-2 p-4 border border-gray-200 rounded-md hover:border-forest-500 dark:hover:border-gold-400 hover:bg-forest-50 dark:hover:bg-forest-950/20 transition text-center group">
                    <div class="w-12 h-12 flex items-center justify-center bg-red-100 text-red-700 rounded group-hover:scale-110 transition">
                        <i class="fas fa-fire text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-cream">إضافة عرض</span>
                </a>
            </div>
        </div>

        {{-- Low Stock Alert --}}
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="p-5 border-b border-gray-200 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-orange-500"></i>
                    <h3 class="font-bold text-gray-900">تنبيهات المخزون</h3>
                </div>
                <a href="{{ route('merchant.inventory.index', ['filter' => 'low_stock']) }}" 
                   class="text-xs text-gray-500 hover:text-forest-700 dark:hover:text-gold-400 inline-flex items-center gap-1 transition">
                    عرض الكل
                    <i class="fas fa-arrow-left text-[10px]"></i>
                </a>
            </div>
            <div class="p-5">
                @if($stats['low_stock'] > 0)
                    <div class="border border-orange-200 bg-orange-50 rounded-md p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 flex items-center justify-center bg-orange-100 text-orange-700 rounded">
                                <i class="fas fa-boxes text-xl"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ $stats['low_stock'] }} منتج بمخزون منخفض
                                </p>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle text-orange-500 ml-1"></i>
                                    تحتاج إعادة تعبئة
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('merchant.inventory.index', ['filter' => 'low_stock']) }}" 
                           class="btn-secondary btn-sm w-full">
                            <i class="fas fa-list ml-1"></i>
                            عرض القائمة
                        </a>
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="w-16 h-16 mx-auto mb-3 flex items-center justify-center bg-green-50 text-green-600 rounded-full">
                            <i class="fas fa-check-circle text-3xl"></i>
                        </div>
                        <p class="text-sm text-gray-600">جميع المنتجات بمخزون جيد</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection