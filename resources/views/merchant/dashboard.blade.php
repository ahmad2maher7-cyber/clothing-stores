@extends('merchant.layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')
@section('page-subtitle', 'نظرة سريعة على أداء متجرك')

@section('content')

    {{-- Welcome Banner --}}
    <div class="rounded-xl p-6 mb-6 text-white relative overflow-hidden"
         style="background-color: var(--gold);">
        <div class="relative z-10">
            <h2 class="text-xl md:text-2xl font-bold mb-1">
                مرحباً {{ auth()->user()->full_name }} 👋
            </h2>
            <p class="text-[13px] opacity-90">
                إليك نظرة سريعة على أداء متجرك اليوم
            </p>
        </div>

        {{-- Decorative --}}
        <div class="absolute -left-8 -bottom-8 text-[180px] opacity-20 pointer-events-none select-none">
            🏪
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- Products --}}
        <a href="{{ route('merchant.products.index') }}"
           class="rounded-xl border p-5 transition hover:-translate-y-0.5 hover:shadow-md"
           style="background-color: var(--bg-primary); border-color: var(--border-light);">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xl"
                     style="background-color: var(--gold-soft);">
                    👕
                </div>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     style="color: var(--text-tertiary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </div>
            <p class="text-[11px] mb-1" style="color: var(--text-secondary);">إجمالي المنتجات</p>
            <p class="text-2xl md:text-3xl font-bold" style="color: var(--text-primary);">
                {{ $stats['products'] }}
            </p>
        </a>

        {{-- Orders --}}
        <a href="{{ route('merchant.orders.index') }}"
           class="rounded-xl border p-5 transition hover:-translate-y-0.5 hover:shadow-md"
           style="background-color: var(--bg-primary); border-color: var(--border-light);">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xl"
                     style="background-color: #dbeafe;">
                    🛒
                </div>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     style="color: var(--text-tertiary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </div>
            <p class="text-[11px] mb-1" style="color: var(--text-secondary);">إجمالي الطلبات</p>
            <p class="text-2xl md:text-3xl font-bold" style="color: #2563eb;">
                {{ $stats['orders'] }}
            </p>
        </a>

        {{-- Pending Orders --}}
        <a href="{{ route('merchant.orders.index', ['status' => 'pending']) }}"
           class="rounded-xl border p-5 transition hover:-translate-y-0.5 hover:shadow-md"
           style="background-color: var(--bg-primary); border-color: var(--border-light);">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xl"
                     style="background-color: #fef3c7;">
                    ⏳
                </div>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     style="color: var(--text-tertiary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </div>
            <p class="text-[11px] mb-1" style="color: var(--text-secondary);">طلبات قيد الانتظار</p>
            <p class="text-2xl md:text-3xl font-bold" style="color: #d97706;">
                {{ $stats['pending_orders'] }}
            </p>
        </a>

        {{-- Revenue --}}
        <div class="rounded-xl border p-5"
             style="background-color: var(--bg-primary); border-color: var(--border-light);">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xl"
                     style="background-color: #d1fae5;">
                    💰
                </div>
            </div>
            <p class="text-[11px] mb-1" style="color: var(--text-secondary);">الإيرادات</p>
            <p class="text-2xl md:text-3xl font-bold" style="color: #166534;">
                {{ number_format($stats['revenue'], 0) }}
            </p>
            <p class="text-[10px] mt-1" style="color: var(--text-tertiary);">جنيه إسترليني</p>
        </div>
    </div>

    {{-- Quick Actions + Low Stock --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Quick Actions --}}
        <div class="rounded-xl border overflow-hidden"
             style="background-color: var(--bg-primary); border-color: var(--border-light);">
            <div class="px-5 py-4 border-b" style="border-color: var(--border-light);">
                <h3 class="text-[14px] font-bold" style="color: var(--text-primary);">
                    ⚡ إجراءات سريعة
                </h3>
            </div>
            <div class="p-4 grid grid-cols-2 gap-3">

                <a href="{{ route('merchant.products.create') }}"
                   class="flex flex-col items-center justify-center p-4 rounded-lg transition border"
                   style="background-color: var(--bg-tertiary); border-color: var(--border-light);"
                   onmouseover="this.style.borderColor='var(--gold)'; this.style.backgroundColor='var(--gold-soft)';"
                   onmouseout="this.style.borderColor='var(--border-light)'; this.style.backgroundColor='var(--bg-tertiary)';">
                    <span class="text-3xl mb-2">➕</span>
                    <span class="text-[12px] font-medium" style="color: var(--text-primary);">إضافة منتج</span>
                </a>

                <a href="{{ route('merchant.categories.create') }}"
                   class="flex flex-col items-center justify-center p-4 rounded-lg transition border"
                   style="background-color: var(--bg-tertiary); border-color: var(--border-light);"
                   onmouseover="this.style.borderColor='var(--gold)'; this.style.backgroundColor='var(--gold-soft)';"
                   onmouseout="this.style.borderColor='var(--border-light)'; this.style.backgroundColor='var(--bg-tertiary)';">
                    <span class="text-3xl mb-2">📂</span>
                    <span class="text-[12px] font-medium" style="color: var(--text-primary);">إضافة تصنيف</span>
                </a>

                <a href="{{ route('merchant.coupons.create') }}"
                   class="flex flex-col items-center justify-center p-4 rounded-lg transition border"
                   style="background-color: var(--bg-tertiary); border-color: var(--border-light);"
                   onmouseover="this.style.borderColor='var(--gold)'; this.style.backgroundColor='var(--gold-soft)';"
                   onmouseout="this.style.borderColor='var(--border-light)'; this.style.backgroundColor='var(--bg-tertiary)';">
                    <span class="text-3xl mb-2">🎟️</span>
                    <span class="text-[12px] font-medium" style="color: var(--text-primary);">إنشاء كوبون</span>
                </a>

                <a href="{{ route('merchant.offers.create') }}"
                   class="flex flex-col items-center justify-center p-4 rounded-lg transition border"
                   style="background-color: var(--bg-tertiary); border-color: var(--border-light);"
                   onmouseover="this.style.borderColor='var(--gold)'; this.style.backgroundColor='var(--gold-soft)';"
                   onmouseout="this.style.borderColor='var(--border-light)'; this.style.backgroundColor='var(--bg-tertiary)';">
                    <span class="text-3xl mb-2">🔥</span>
                    <span class="text-[12px] font-medium" style="color: var(--text-primary);">إضافة عرض</span>
                </a>
            </div>
        </div>

        {{-- Low Stock Alert --}}
        <div class="rounded-xl border overflow-hidden"
             style="background-color: var(--bg-primary); border-color: var(--border-light);">
            <div class="px-5 py-4 border-b flex items-center justify-between"
                 style="border-color: var(--border-light);">
                <h3 class="text-[14px] font-bold" style="color: var(--text-primary);">
                    ⚠️ تنبيهات المخزون
                </h3>
                <a href="{{ route('merchant.inventory.lowStock') }}"
                   class="text-[12px] font-semibold"
                   style="color: var(--gold);">
                    عرض الكل
                </a>
            </div>

            <div class="p-4">
                @if($stats['low_stock'] > 0)
                    <div class="rounded-lg p-4 border"
                         style="background-color: #fef2f2; border-color: #fecaca;">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-2xl"
                                 style="background-color: #dc2626;">
                                ⚠️
                            </div>
                            <div class="flex-1">
                                <p class="text-[14px] font-bold" style="color: #991b1b;">
                                    يوجد {{ $stats['low_stock'] }} منتج بمخزون منخفض
                                </p>
                                <p class="text-[12px]" style="color: #b91c1c;">
                                    يُنصح بإعادة التعبئة قريباً
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-lg p-4 border"
                         style="background-color: #f0fdf4; border-color: #bbf7d0;">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-2xl"
                                 style="background-color: #166534;">
                                ✅
                            </div>
                            <div class="flex-1">
                                <p class="text-[14px] font-bold" style="color: #166534;">
                                    جميع المنتجات بمخزون جيد
                                </p>
                                <p class="text-[12px]" style="color: #15803d;">
                                    لا توجد تنبيهات حالياً
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection