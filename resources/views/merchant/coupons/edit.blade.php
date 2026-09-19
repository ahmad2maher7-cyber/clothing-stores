@extends('merchant.layouts.app')

@section('title', isset($coupon) ? 'تعديل كوبون' : 'إضافة كوبون')
@section('page-title', isset($coupon) ? 'تعديل الكوبون' : 'إضافة كوبون جديد')

@section('content')

    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('merchant.coupons.index') }}" class="hover:text-indigo-600">الكوبونات</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800">{{ isset($coupon) ? $coupon->code : 'إضافة جديد' }}</span>
    </nav>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">🎟️ معلومات الكوبون</h3>
        </div>

        <form action="{{ isset($coupon) ? route('merchant.coupons.update', $coupon) : route('merchant.coupons.store') }}"
              method="POST" class="p-6">
            @csrf
            @if(isset($coupon)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        كود الكوبون <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" required
                           style="text-transform: uppercase"
                           placeholder="مثال: WELCOME10, SAVE50"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 font-mono text-lg">
                    <p class="text-xs text-gray-500 mt-1">أحرف إنجليزية كبيرة وأرقام فقط</p>
                    @error('code') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع الخصم <span class="text-red-500">*</span></label>
                    <select name="type" required
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="percentage" {{ old('type', $coupon->type ?? '') == 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                        <option value="fixed" {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>مبلغ ثابت (₪)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">قيمة الخصم <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value ?? '') }}" required min="0.01"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    @error('value') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحد الأدنى للطلب (₪)</label>
                    <input type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount ?? '') }}" min="0"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">أقصى خصم (₪)</label>
                    <input type="number" step="0.01" name="max_discount" value="{{ old('max_discount', $coupon->max_discount ?? '') }}" min="0"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">للنسبة المئوية: أقصى مبلغ خصم</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">حد الاستخدام</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" min="1"
                           placeholder="اتركها فارغة لاستخدام غير محدود"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحالة <span class="text-red-500">*</span></label>
                    <select name="status" required
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="active" {{ old('status', $coupon->status ?? 'active') == 'active' ? 'selected' : '' }}>✅ نشط</option>
                        <option value="expired" {{ old('status', $coupon->status ?? '') == 'expired' ? 'selected' : '' }}>⛔ معطل</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ البداية <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" 
                           value="{{ old('start_date', isset($coupon) ? $coupon->start_date->format('Y-m-d') : date('Y-m-d')) }}" required
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الانتهاء <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" 
                           value="{{ old('end_date', isset($coupon) ? $coupon->end_date->format('Y-m-d') : date('Y-m-d', strtotime('+30 days'))) }}" required
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    @error('end_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-2 space-x-reverse mt-8 pt-6 border-t">
                <a href="{{ route('merchant.coupons.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg">إلغاء</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                    💾 {{ isset($coupon) ? 'حفظ التعديلات' : 'إنشاء الكوبون' }}
                </button>
            </div>
        </form>
    </div>

@endsection