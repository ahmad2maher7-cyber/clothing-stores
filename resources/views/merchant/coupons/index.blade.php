@extends('merchant.layouts.app')

@section('title', 'الكوبونات')
@section('page-title', 'إدارة الكوبونات')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">الكوبونات</h2>
            <p class="text-gray-500 text-sm">إدارة أكواد الخصم لمتجرك</p>
        </div>
        <a href="{{ route('merchant.coupons.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition">
            <span>➕</span><span>إضافة كوبون</span>
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-r-4 border-indigo-500">
            <p class="text-xs text-gray-500 mb-1">إجمالي الكوبونات</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        </div>
        <a href="{{ route('merchant.coupons.index', ['status' => 'active']) }}"
           class="bg-white rounded-lg shadow p-4 border-r-4 border-green-500 hover:shadow-md transition">
            <p class="text-xs text-gray-500 mb-1">✅ نشطة</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </a>
        <a href="{{ route('merchant.coupons.index', ['status' => 'expired']) }}"
           class="bg-white rounded-lg shadow p-4 border-r-4 border-red-500 hover:shadow-md transition">
            <p class="text-xs text-gray-500 mb-1">⛔ منتهية</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['expired'] }}</p>
        </a>
        <div class="bg-white rounded-lg shadow p-4 border-r-4 border-purple-500">
            <p class="text-xs text-gray-500 mb-1">📊 مرات الاستخدام</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['total_used'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 ابحث بكود الكوبون..."
                   class="md:col-span-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
            <div class="flex space-x-2 space-x-reverse">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex-1">بحث</button>
                <a href="{{ route('merchant.coupons.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">إعادة</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($coupons->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الكود</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الخصم</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الشروط</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الفترة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاستخدام</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($coupons as $coupon)
                            @php
                                $isExpired = $coupon->end_date < now();
                                $isFull = $coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit;
                                $isActive = $coupon->status === 'active' && !$isExpired && !$isFull;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <span class="font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                        {{ $coupon->code }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($coupon->type === 'percentage')
                                        <span class="text-lg font-bold text-green-600">{{ $coupon->value }}%</span>
                                    @else
                                        <span class="text-lg font-bold text-green-600">{{ number_format($coupon->value, 0) }} ₪</span>
                                    @endif
                                    @if($coupon->max_discount)
                                        <div class="text-xs text-gray-500">أقصى: {{ $coupon->max_discount }} ₪</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    @if($coupon->min_order_amount)
                                        <div>حد أدنى: {{ $coupon->min_order_amount }} ₪</div>
                                    @endif
                                    @if($coupon->usage_limit)
                                        <div>حد الاستخدام: {{ $coupon->usage_limit }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    <div>من: {{ $coupon->start_date->format('Y/m/d') }}</div>
                                    <div>إلى: {{ $coupon->end_date->format('Y/m/d') }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium">{{ $coupon->used_count }}
                                        @if($coupon->usage_limit) / {{ $coupon->usage_limit }} @endif
                                    </div>
                                    @if($coupon->usage_limit)
                                        <div class="w-24 h-2 bg-gray-200 rounded mt-1">
                                            <div class="h-full bg-indigo-500 rounded" 
                                                 style="width: {{ min(100, ($coupon->used_count / $coupon->usage_limit) * 100) }}%"></div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($isActive)
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">✅ نشط</span>
                                    @elseif($isExpired)
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">⛔ منتهي</span>
                                    @elseif($isFull)
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">🔒 استُنفد</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">⏸️ معطل</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex space-x-2 space-x-reverse">
                                        <a href="{{ route('merchant.coupons.edit', $coupon) }}"
                                           class="text-indigo-600 hover:text-indigo-900">✏️</a>
                                        <form action="{{ route('merchant.coupons.destroy', $coupon) }}"
                                              method="POST" onsubmit="return confirm('حذف الكوبون؟')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t bg-gray-50">{{ $coupons->links() }}</div>
        @else
            <div class="text-center py-16">
                <div class="text-6xl mb-4">🎟️</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد كوبونات</h3>
                <a href="{{ route('merchant.coupons.create') }}"
                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg mt-2">
                    ➕ إضافة كوبون
                </a>
            </div>
        @endif
    </div>

@endsection