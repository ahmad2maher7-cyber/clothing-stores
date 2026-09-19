@extends('merchant.layouts.app')

@section('title', 'سياسات المتجر')
@section('page-title', 'سياسات المتجر')

@section('content')

    {{-- Tabs --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="flex border-b overflow-x-auto">
            <a href="{{ route('merchant.settings.index') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">🏪 المعلومات الأساسية</a>
            <a href="{{ route('merchant.settings.branches') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">🏢 الفروع</a>
            <a href="{{ route('merchant.settings.shipping') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">🚚 مناطق الشحن</a>
            <a href="{{ route('merchant.settings.policies') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-indigo-600 text-indigo-600 font-medium">📜 السياسات</a>
        </div>
    </div>

    <form action="{{ route('merchant.settings.policies.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">🔒 سياسة الخصوصية</h3>
                    <p class="text-sm text-gray-500 mt-1">وضّح للزبائن كيف تحمي بياناتهم</p>
                </div>
                <div class="p-6">
                    <textarea name="privacy_policy" rows="8" maxlength="10000"
                              placeholder="اكتب سياسة الخصوصية لمتجرك هنا...&#10;&#10;مثال:&#10;- نحن نحترم خصوصيتك.&#10;- لا نشارك بياناتك مع أطراف ثالثة.&#10;- نستخدم بياناتك لتحسين خدمتنا فقط."
                              class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm">{{ old('privacy_policy', $store->privacy_policy) }}</textarea>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">🔄 سياسة الاستبدال والإرجاع</h3>
                    <p class="text-sm text-gray-500 mt-1">وضّح شروط الإرجاع والاستبدال</p>
                </div>
                <div class="p-6">
                    <textarea name="return_policy" rows="8" maxlength="10000"
                              placeholder="اكتب سياسة الاستبدال والإرجاع هنا...&#10;&#10;مثال:&#10;- يمكنك الإرجاع خلال 14 يوماً.&#10;- يجب أن يكون المنتج بحالته الأصلية.&#10;- تكاليف الشحن على المشتري."
                              class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm">{{ old('return_policy', $store->return_policy) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium">
                    💾 حفظ السياسات
                </button>
            </div>
        </div>
    </form>

@endsection