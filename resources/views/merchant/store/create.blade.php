@extends('merchant.layouts.app')

@section('title', 'إنشاء متجر')
@section('page-title', 'إنشاء متجرك')

@section('content')
    <div class="max-w-3xl mx-auto">

        {{-- Welcome --}}
        <div class="bg-gradient-to-l from-indigo-600 to-purple-700 text-white rounded-xl p-8 mb-6 text-center">
            <div class="text-6xl mb-3">🏪</div>
            <h1 class="text-3xl font-bold mb-2">ابدأ رحلتك التجارية</h1>
            <p class="text-indigo-100">أنشئ متجرك الأول وابدأ البيع خلال دقائق</p>
        </div>

        {{-- Steps --}}
        <div class="bg-white rounded-xl shadow p-5 mb-6">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                    <span class="font-medium text-indigo-600">معلومات المتجر</span>
                </div>
                <div class="flex-1 border-t-2 border-dashed border-gray-300 mx-3"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-bold">2</div>
                    <span class="text-gray-500">المراجعة</span>
                </div>
                <div class="flex-1 border-t-2 border-dashed border-gray-300 mx-3"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-bold">3</div>
                    <span class="text-gray-500">التفعيل</span>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('merchant.store.store') }}" method="POST" enctype="multipart/form-data"
              class="bg-white rounded-xl shadow">
            @csrf

            <div class="p-6 border-b">
                <h3 class="text-lg font-bold">📝 معلومات المتجر</h3>
                <p class="text-sm text-gray-500">املأ البيانات التالية بدقة</p>
            </div>

            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        اسم المتجر <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="مثال: متجر الأناقة"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                    <textarea name="description" rows="3" maxlength="1000"
                              placeholder="وصف مختصر وجاذب لمتجرك..."
                              class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            رقم الهاتف <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                               placeholder="0599000000"
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">السجل التجاري</label>
                        <input type="text" name="commercial_register" value="{{ old('commercial_register') }}"
                               placeholder="CR-12345"
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        العنوان <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" rows="2" required maxlength="500"
                              placeholder="المدينة - الحي - الشارع"
                              class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                    @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Logo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">شعار المتجر</label>
                    <input type="file" name="logo" accept="image/*"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG — أقل من 2MB</p>
                    @error('logo') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Banner --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">غلاف المتجر</label>
                    <input type="file" name="banner" accept="image/*"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG — أقل من 3MB — مقاس مقترح 1200x400</p>
                    @error('banner') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Notice --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex gap-3">
                        <span class="text-2xl">⚠️</span>
                        <div class="text-sm">
                            <p class="font-medium text-yellow-800 mb-1">مراجعة إلزامية</p>
                            <p class="text-yellow-700 leading-relaxed">
                                بعد إرسال الطلب، سيتم مراجعته من قبل الإدارة.
                                لا يمكنك إضافة منتجات أو استقبال طلبات حتى تتم الموافقة.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t bg-gray-50 flex justify-end">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-bold transition">
                    🚀 إرسال طلب الإنشاء
                </button>
            </div>
        </form>
    </div>
@endsection