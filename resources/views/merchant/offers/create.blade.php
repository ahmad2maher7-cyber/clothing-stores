@extends('merchant.layouts.app')

@section('title', 'إضافة عرض جديد')
@section('page-title', 'إضافة عرض جديد')

@section('content')

    {{-- Breadcrumb --}}
    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('merchant.dashboard') }}" class="hover:text-indigo-600">لوحة التحكم</a>
        <span class="mx-2">›</span>
        <a href="{{ route('merchant.offers.index') }}" class="hover:text-indigo-600">العروض</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800">إضافة جديد</span>
    </nav>

    <div x-data="offerForm()">

        <form action="{{ route('merchant.offers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ============ العمود الرئيسي ============ --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- 1. معلومات العرض --}}
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-bold text-gray-800">🔥 معلومات العرض</h3>
                            <p class="text-sm text-gray-500 mt-1">أدخل تفاصيل العرض الترويجي</p>
                        </div>

                        <div class="p-6 space-y-5">

                            {{-- العنوان --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    عنوان العرض <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" x-model="title" required maxlength="255"
                                       placeholder="مثال: تخفيضات نهاية الموسم"
                                       class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 text-lg">
                                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- الوصف --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                                <textarea name="description" x-model="description" rows="3" maxlength="1000"
                                          placeholder="اكتب وصفاً جاذباً للعرض..."
                                          class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                <p class="text-xs text-gray-500 mt-1 text-left">
                                    <span x-text="description.length"></span> / 1000
                                </p>
                            </div>

                            {{-- نسبة الخصم --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    نسبة الخصم <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-4">
                                    <input type="range" name="discount_percent" x-model="discount_percent"
                                           min="1" max="100" step="1"
                                           class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                    <div class="flex items-center gap-2 bg-indigo-50 rounded-lg px-4 py-2 min-w-[100px] justify-center">
                                        <span class="text-2xl font-bold text-indigo-600" x-text="discount_percent"></span>
                                        <span class="text-indigo-600 font-bold">%</span>
                                    </div>
                                </div>

                                {{-- Quick Buttons --}}
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <template x-for="percent in [10, 15, 20, 25, 30, 40, 50]" :key="percent">
                                        <button type="button" @click="discount_percent = percent"
                                                :class="discount_percent == percent ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-indigo-100'"
                                                class="text-xs px-3 py-1.5 rounded-lg transition">
                                            <span x-text="percent"></span>%
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. الفترة الزمنية --}}
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-bold text-gray-800">📅 فترة العرض</h3>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    تاريخ البداية <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="start_date" x-ref="startDate"
                                       value="{{ date('Y-m-d') }}" required
                                       class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    تاريخ الانتهاء <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="end_date" x-ref="endDate"
                                       value="{{ date('Y-m-d', strtotime('+30 days')) }}" required
                                       class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Quick Duration --}}
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 mb-2">⏱️ مدة سريعة:</p>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" @click="setDuration(3)"
                                            class="bg-gray-100 hover:bg-indigo-100 text-gray-700 text-xs px-3 py-1.5 rounded-lg">
                                        3 أيام
                                    </button>
                                    <button type="button" @click="setDuration(7)"
                                            class="bg-gray-100 hover:bg-indigo-100 text-gray-700 text-xs px-3 py-1.5 rounded-lg">
                                        أسبوع
                                    </button>
                                    <button type="button" @click="setDuration(14)"
                                            class="bg-gray-100 hover:bg-indigo-100 text-gray-700 text-xs px-3 py-1.5 rounded-lg">
                                        أسبوعان
                                    </button>
                                    <button type="button" @click="setDuration(30)"
                                            class="bg-gray-100 hover:bg-indigo-100 text-gray-700 text-xs px-3 py-1.5 rounded-lg">
                                        شهر
                                    </button>
                                    <button type="button" @click="setDuration(90)"
                                            class="bg-gray-100 hover:bg-indigo-100 text-gray-700 text-xs px-3 py-1.5 rounded-lg">
                                        3 أشهر
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ العمود الجانبي ============ --}}
                <div class="space-y-6">

                    {{-- Banner Upload --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-gray-800 mb-4">📸 صورة العرض (Banner)</h3>

                        <div @click="$refs.bannerInput.click()"
                             class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-indigo-500 transition"
                             :class="bannerPreview ? 'border-solid' : ''">
                            <template x-if="!bannerPreview">
                                <div>
                                    <div class="text-5xl mb-2">🖼️</div>
                                    <p class="text-sm text-gray-600 mb-1">اضغط لاختيار صورة</p>
                                    <p class="text-xs text-gray-400">JPG, PNG, WEBP — أقل من 3MB</p>
                                </div>
                            </template>
                            <template x-if="bannerPreview">
                                <img :src="bannerPreview" class="w-full h-40 object-cover rounded">
                            </template>
                        </div>

                        <input type="file" name="banner" x-ref="bannerInput"
                               @change="handleBanner($event)"
                               accept="image/*" class="hidden">

                        <button type="button" x-show="bannerPreview"
                                @click="removeBanner()"
                                class="mt-2 w-full text-red-600 hover:text-red-800 text-sm">
                            🗑️ حذف الصورة
                        </button>

                        @error('banner') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Live Preview --}}
                    <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                        <h3 class="font-bold text-gray-800 mb-4">👁️ معاينة مباشرة</h3>

                        <div class="border rounded-lg overflow-hidden">
                            {{-- Banner --}}
                            <template x-if="bannerPreview">
                                <img :src="bannerPreview" class="w-full h-32 object-cover">
                            </template>
                            <template x-if="!bannerPreview">
                                <div class="w-full h-32 bg-gradient-to-l from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-5xl">🔥</span>
                                </div>
                            </template>

                            {{-- Content --}}
                            <div class="p-3">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-sm text-gray-800" x-text="title || 'عنوان العرض'"></h4>
                                    <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded font-bold whitespace-nowrap"
                                          x-text="'-' + discount_percent + '%'"></span>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-2" 
                                   x-text="description || 'وصف العرض سيظهر هنا...'"></p>
                                <div class="mt-2 pt-2 border-t">
                                    <span class="bg-green-500 text-white px-2 py-0.5 rounded text-xs">🔥 نشط</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end space-x-2 space-x-reverse mt-6">
                <a href="{{ route('merchant.offers.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg">
                    إلغاء
                </a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium">
                    💾 إنشاء العرض
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
function offerForm() {
    return {
        title: '',
        description: '',
        discount_percent: 20,
        bannerPreview: null,

        handleBanner(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                this.bannerPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        removeBanner() {
            this.bannerPreview = null;
            this.$refs.bannerInput.value = '';
        },

        setDuration(days) {
            const start = new Date();
            const end = new Date();
            end.setDate(end.getDate() + days);

            const formatDate = (date) => date.toISOString().split('T')[0];

            this.$refs.startDate.value = formatDate(start);
            this.$refs.endDate.value = formatDate(end);
        }
    }
}
</script>
@endpush