@extends('merchant.layouts.app')

@section('title', 'إعدادات المتجر')
@section('page-title', 'إعدادات المتجر')

@section('content')

    {{-- Tabs --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="flex border-b overflow-x-auto">
            <a href="{{ route('merchant.settings.index') }}"
               class="px-6 py-4 whitespace-nowrap border-b-2 font-medium transition
                      {{ request()->routeIs('merchant.settings.index') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-indigo-600' }}">
                🏪 المعلومات الأساسية
            </a>
            <a href="{{ route('merchant.settings.branches') }}"
               class="px-6 py-4 whitespace-nowrap border-b-2 font-medium transition
                      {{ request()->routeIs('merchant.settings.branches') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-indigo-600' }}">
                🏢 الفروع
            </a>
            <a href="{{ route('merchant.settings.shipping') }}"
               class="px-6 py-4 whitespace-nowrap border-b-2 font-medium transition
                      {{ request()->routeIs('merchant.settings.shipping') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-indigo-600' }}">
                🚚 مناطق الشحن
            </a>
            <a href="{{ route('merchant.settings.policies') }}"
               class="px-6 py-4 whitespace-nowrap border-b-2 font-medium transition
                      {{ request()->routeIs('merchant.settings.policies') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-indigo-600' }}">
                📜 السياسات
            </a>
        </div>
    </div>

    <div x-data="settingsForm()">

        <form action="{{ route('merchant.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main Column --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Basic Info --}}
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-bold text-gray-800">📝 المعلومات الأساسية</h3>
                        </div>
                        <div class="p-6 space-y-5">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    اسم المتجر <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $store->name) }}" required
                                       class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">وصف المتجر</label>
                                <textarea name="description" rows="4" maxlength="1000"
                                          placeholder="اكتب وصفاً جاذباً لمتجرك..."
                                          class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $store->description) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">السجل التجاري</label>
                                    <input type="text" name="commercial_register" value="{{ old('commercial_register', $store->commercial_register) }}"
                                           placeholder="CR-12345"
                                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                                    <input type="text" value="{{ auth()->user()->phone }}" disabled
                                           class="w-full border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                                    <p class="text-xs text-gray-500 mt-1">يُعدل من الملف الشخصي</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">العنوان الرئيسي</label>
                                <textarea name="address" rows="2"
                                          placeholder="المدينة - الحي - الشارع"
                                          class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $store->address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Working Hours --}}
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-bold text-gray-800">⏰ أوقات العمل</h3>
                            <p class="text-sm text-gray-500 mt-1">اتركها فارغة للأيام المغلقة</p>
                        </div>
                        <div class="p-6">
                            @php
                                $days = [
                                    'saturday' => 'السبت',
                                    'sunday' => 'الأحد',
                                    'monday' => 'الاثنين',
                                    'tuesday' => 'الثلاثاء',
                                    'wednesday' => 'الأربعاء',
                                    'thursday' => 'الخميس',
                                    'friday' => 'الجمعة',
                                ];
                                $workingHours = $store->working_hours ?? [];
                            @endphp

                            <div class="space-y-3">
                                @foreach($days as $key => $label)
                                    <div class="flex items-center gap-3">
                                        <div class="w-24 text-sm font-medium text-gray-700">{{ $label }}</div>
                                        <input type="text"
                                               name="working_hours[{{ $key }}]"
                                               value="{{ old('working_hours.' . $key, $workingHours[$key] ?? '') }}"
                                               placeholder="09:00 - 22:00 أو CLOSED"
                                               class="flex-1 border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">

                    {{-- Logo --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-gray-800 mb-4">🏪 شعار المتجر</h3>

                        <div class="text-center">
                            @if($store->logo)
                                <img src="{{ asset('storage/' . $store->logo) }}"
                                     class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-gray-100 mb-3">
                            @else
                                <div class="w-32 h-32 rounded-full bg-indigo-100 flex items-center justify-center text-4xl mx-auto mb-3">
                                    🏪
                                </div>
                            @endif

                            <label for="logo" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg inline-block text-sm">
                                📸 تغيير الشعار
                            </label>
                            <input type="file" name="logo" id="logo" accept="image/*" class="hidden"
                                   onchange="document.getElementById('logo-name').textContent = this.files[0].name">
                            <p id="logo-name" class="text-xs text-gray-500 mt-1"></p>
                        </div>
                        @error('logo') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Banner --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-gray-800 mb-4">🖼️ غلاف المتجر</h3>

                        @if($store->banner)
                            <img src="{{ asset('storage/' . $store->banner) }}"
                                 class="w-full h-32 object-cover rounded-lg mb-3">
                        @else
                            <div class="w-full h-32 bg-gradient-to-l from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center mb-3">
                                <span class="text-white text-sm">لا يوجد غلاف</span>
                            </div>
                        @endif

                        <label for="banner" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg inline-block text-sm w-full text-center">
                            📸 تغيير الغلاف
                        </label>
                        <input type="file" name="banner" id="banner" accept="image/*" class="hidden"
                               onchange="document.getElementById('banner-name').textContent = this.files[0].name">
                        <p id="banner-name" class="text-xs text-gray-500 mt-1"></p>

                        <p class="text-xs text-gray-400 mt-2">الحجم المقترح: 1200x400 بكسل</p>
                        @error('banner') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Store Info --}}
                    <div class="bg-gray-50 rounded-lg p-4 text-sm">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-500">حالة المتجر:</span>
                            @if($store->status === 'active')
                                <span class="text-green-600 font-medium">✅ نشط</span>
                            @else
                                <span class="text-red-600 font-medium">⛔ معطل</span>
                            @endif
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-500">تاريخ الإنشاء:</span>
                            <span class="font-medium">{{ $store->created_at->format('Y/m/d') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">معرّف المتجر:</span>
                            <span class="font-mono text-xs">{{ $store->id }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium">
                    💾 حفظ الإعدادات
                </button>
            </div>
        </form>
    </div>

@endsection