@extends('merchant.layouts.app')

@section('title', 'الهوية البصرية')
@section('page-title', 'الهوية البصرية')

@section('content')

    {{-- Tabs --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="flex border-b overflow-x-auto">
            <a href="{{ route('merchant.settings.index') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">🏪 المعلومات الأساسية</a>
            <a href="{{ route('merchant.settings.appearance') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-indigo-600 text-indigo-600 font-medium">🎨 الهوية البصرية</a>
            <a href="{{ route('merchant.settings.branches') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">🏢 الفروع</a>
            <a href="{{ route('merchant.settings.shipping') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">🚚 مناطق الشحن</a>
            <a href="{{ route('merchant.settings.policies') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">📜 السياسات</a>
        </div>
    </div>

    <form action="{{ route('merchant.settings.appearance.update') }}" method="POST" x-data="appearanceForm()">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Colors --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Primary --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-bold text-lg mb-1">🎨 اللون الأساسي (Primary)</h3>
                    <p class="text-sm text-gray-500 mb-4">اللون الرئيسي للأزرار والروابط والعناصر النشطة</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">اختر اللون</label>
                            <input type="color" name="primary_color" x-model="primaryColor"
                                   value="{{ old('primary_color', $store->primary_color) }}"
                                   class="w-full h-16 border-gray-300 rounded-lg cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">أو أدخل HEX</label>
                            <input type="text" x-model="primaryColor" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$"
                                   class="w-full border-gray-300 rounded-lg font-mono text-center"
                                   placeholder="#4A4A9D">
                        </div>
                    </div>

                    {{-- Preview --}}
                    <div class="mt-4 p-4 rounded-lg bg-gray-50">
                        <p class="text-xs text-gray-500 mb-2">معاينة:</p>
                        <button type="button" :style="'background-color: ' + primaryColor"
                                class="text-white px-6 py-2 rounded-lg font-medium shadow">
                            زر أساسي
                        </button>
                        <a href="#" :style="'color: ' + primaryColor" class="mr-4 underline">رابط أساسي</a>
                    </div>
                </div>

                {{-- Secondary --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-bold text-lg mb-1">🎨 اللون الثانوي (Secondary)</h3>
                    <p class="text-sm text-gray-500 mb-4">للأزرار الثانوية والعناصر الأقل أهمية</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">اختر اللون</label>
                            <input type="color" name="secondary_color" x-model="secondaryColor"
                                   value="{{ old('secondary_color', $store->secondary_color) }}"
                                   class="w-full h-16 border-gray-300 rounded-lg cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">أو أدخل HEX</label>
                            <input type="text" x-model="secondaryColor" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$"
                                   class="w-full border-gray-300 rounded-lg font-mono text-center"
                                   placeholder="#1E293B">
                        </div>
                    </div>

                    <div class="mt-4 p-4 rounded-lg bg-gray-50">
                        <p class="text-xs text-gray-500 mb-2">معاينة:</p>
                        <button type="button" :style="'background-color: ' + secondaryColor"
                                class="text-white px-6 py-2 rounded-lg font-medium shadow">
                            زر ثانوي
                        </button>
                    </div>
                </div>

                {{-- Accent --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-bold text-lg mb-1">🎨 لون التمييز (Accent)</h3>
                    <p class="text-sm text-gray-500 mb-4">للشارات المهمة (تخفيضات، جديد، مميز)</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">اختر اللون</label>
                            <input type="color" name="accent_color" x-model="accentColor"
                                   value="{{ old('accent_color', $store->accent_color) }}"
                                   class="w-full h-16 border-gray-300 rounded-lg cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">أو أدخل HEX</label>
                            <input type="text" x-model="accentColor" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$"
                                   class="w-full border-gray-300 rounded-lg font-mono text-center"
                                   placeholder="#EF4444">
                        </div>
                    </div>

                    <div class="mt-4 p-4 rounded-lg bg-gray-50">
                        <p class="text-xs text-gray-500 mb-2">معاينة:</p>
                        <span :style="'background-color: ' + accentColor"
                              class="text-white text-xs px-3 py-1 rounded-full font-bold">
                            -30%
                        </span>
                    </div>
                </div>

                {{-- Theme Mode --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-bold text-lg mb-1">🌓 وضع الثيم</h3>
                    <p class="text-sm text-gray-500 mb-4">اختر الوضع الافتراضي لصفحة متجرك</p>

                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="theme_mode" value="light" class="peer sr-only"
                                   {{ old('theme_mode', $store->theme_mode) === 'light' ? 'checked' : '' }}>
                            <div class="border-2 border-gray-300 rounded-lg p-4 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition">
                                <div class="text-3xl mb-2">☀️</div>
                                <div class="font-medium">فاتح</div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="theme_mode" value="dark" class="peer sr-only"
                                   {{ old('theme_mode', $store->theme_mode) === 'dark' ? 'checked' : '' }}>
                            <div class="border-2 border-gray-300 rounded-lg p-4 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition">
                                <div class="text-3xl mb-2">🌙</div>
                                <div class="font-medium">داكن</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Live Preview --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow p-6 sticky top-4">
                    <h3 class="font-bold mb-4">👁️ معاينة مباشرة</h3>

                    <div class="border rounded-lg overflow-hidden">
                        {{-- Header --}}
                        <div :style="'background-color: ' + primaryColor" class="p-4 text-white">
                            <div class="flex items-center justify-between">
                                <span class="font-bold">متجري</span>
                                <span>🛒</span>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="p-4 bg-white">
                            <div class="border rounded-lg p-3 mb-3">
                                <div class="h-24 rounded" :style="'background-color: ' + primaryColor + '20'"></div>
                                <p class="font-medium mt-2">اسم المنتج</p>
                                <p :style="'color: ' + primaryColor" class="font-bold">150 ₪</p>
                            </div>

                            <button :style="'background-color: ' + primaryColor"
                                    class="w-full text-white py-2 rounded-lg font-medium mb-2">
                                أضف للسلة
                            </button>

                            <button :style="'background-color: ' + secondaryColor"
                                    class="w-full text-white py-2 rounded-lg font-medium">
                                زر ثانوي
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end mt-6">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium">
                💾 حفظ الهوية البصرية
            </button>
        </div>
    </form>

@endsection

@push('scripts')
<script>
function appearanceForm() {
    return {
        primaryColor: '{{ old('primary_color', $store->primary_color) }}',
        secondaryColor: '{{ old('secondary_color', $store->secondary_color) }}',
        accentColor: '{{ old('accent_color', $store->accent_color) }}',
    }
}
</script>
@endpush
