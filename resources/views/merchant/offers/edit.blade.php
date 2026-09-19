@extends('merchant.layouts.app')

@section('title', isset($offer) ? 'تعديل عرض' : 'إضافة عرض')
@section('page-title', isset($offer) ? 'تعديل العرض' : 'إضافة عرض جديد')

@section('content')

    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('merchant.offers.index') }}" class="hover:text-indigo-600">العروض</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800">{{ isset($offer) ? $offer->title : 'إضافة جديد' }}</span>
    </nav>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">🔥 معلومات العرض</h3>
        </div>

        <form action="{{ isset($offer) ? route('merchant.offers.update', $offer) : route('merchant.offers.store') }}"
              method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @if(isset($offer)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">عنوان العرض <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $offer->title ?? '') }}" required
                           placeholder="مثال: تخفيضات نهاية الموسم"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                    <textarea name="description" rows="3"
                              class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="تفاصيل العرض...">{{ old('description', $offer->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نسبة الخصم (%) <span class="text-red-500">*</span></label>
                    <input type="number" name="discount_percent" 
                           value="{{ old('discount_percent', $offer->discount_percent ?? 20) }}" 
                           required min="1" max="100"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Banner (اختياري)</label>
                    @if(isset($offer) && $offer->banner)
                        <img src="{{ asset('storage/' . $offer->banner) }}" class="w-full h-24 object-cover rounded mb-2">
                    @endif
                    <input type="file" name="banner" accept="image/*"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ البداية <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" 
                           value="{{ old('start_date', isset($offer) ? $offer->start_date->format('Y-m-d') : date('Y-m-d')) }}" required
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الانتهاء <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" 
                           value="{{ old('end_date', isset($offer) ? $offer->end_date->format('Y-m-d') : date('Y-m-d', strtotime('+30 days'))) }}" required
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex justify-end space-x-2 space-x-reverse mt-8 pt-6 border-t">
                <a href="{{ route('merchant.offers.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg">إلغاء</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                    💾 {{ isset($offer) ? 'حفظ التعديلات' : 'إنشاء العرض' }}
                </button>
            </div>
        </form>
    </div>

@endsection
