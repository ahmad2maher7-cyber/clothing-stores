@extends('merchant.layouts.app')

@section('title', 'إضافة كوبون جديد')
@section('page-title', 'إضافة كوبون جديد')

@section('content')

    {{-- Breadcrumb --}}
    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('merchant.dashboard') }}" class="hover:text-indigo-600">لوحة التحكم</a>
        <span class="mx-2">›</span>
        <a href="{{ route('merchant.coupons.index') }}" class="hover:text-indigo-600">الكوبونات</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800">إضافة جديد</span>
    </nav>

    <div x-data="couponForm()">

        <form action="{{ route('merchant.coupons.store') }}" method="POST">
            @csrf

            {{-- ============ 1. المعلومات الأساسية ============ --}}
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">🎟️ معلومات الكوبون</h3>
                    <p class="text-sm text-gray-500 mt-1">أنشئ كود خصم لجذب المزيد من الزبائن</p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- كود الكوبون --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            كود الكوبون <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="text" name="code" x-model="code" required
                                   @input="code = code.toUpperCase().replace(/[^A-Z0-9-]/g, '')"
                                   placeholder="مثال: WELCOME10"
                                   maxlength="50"
                                   style="text-transform: uppercase"
                                   class="flex-1 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 font-mono text-lg tracking-wider">
                            <button type="button" @click="generateCode()"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 rounded-lg text-sm whitespace-nowrap">
                                🎲 توليد
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">أحرف إنجليزية كبيرة وأرقام فقط (بدون مسافات)</p>
                        @error('code') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- نوع الخصم --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            نوع الخصم <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="percentage" x-model="type" class="peer sr-only" checked>
                                <div class="border-2 border-gray-300 rounded-lg p-4 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition">
                                    <div class="text-3xl mb-1">%</div>
                                    <div class="font-medium">نسبة مئوية</div>
                                    <div class="text-xs text-gray-500 mt-1">مثال: خصم 20%</div>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="fixed" x-model="type" class="peer sr-only">
                                <div class="border-2 border-gray-300 rounded-lg p-4 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition">
                                    <div class="text-3xl mb-1">₪</div>
                                    <div class="font-medium">مبلغ ثابت</div>
                                    <div class="text-xs text-gray-500 mt-1">مثال: خصم 50 ₪</div>
                                </div>
                            </label>
                        </div>
                        @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- قيمة الخصم --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            قيمة الخصم <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" name="value" x-model="value" required min="0.01"
                                   :max="type === 'percentage' ? 100 : null"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 pr-12">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-bold"
                                  x-text="type === 'percentage' ? '%' : '₪'"></span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1"
                           x-show="type === 'percentage'">النسبة من 1 إلى 100</p>
                        @error('value') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- الحد الأدنى للطلب --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            الحد الأدنى للطلب (₪)
                        </label>
                        <input type="number" step="0.01" name="min_order_amount" min="0"
                               placeholder="اتركها فارغة لعدم التحديد"
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">الحد الأدنى لقيمة السلة لتفعيل الكوبون</p>
                    </div>

                    {{-- أقصى خصم --}}
                    <div x-show="type === 'percentage'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            أقصى مبلغ خصم (₪)
                        </label>
                        <input type="number" step="0.01" name="max_discount" min="0"
                               placeholder="اتركها فارغة بدون حد"
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">حتى لو كانت النسبة أعلى، لن يتجاوز هذا المبلغ</p>
                    </div>

                    {{-- حد الاستخدام --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            حد الاستخدام
                        </label>
                        <input type="number" name="usage_limit" min="1"
                               placeholder="اتركها فارغة لاستخدام غير محدود"
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">عدد مرات استخدام الكوبون الإجمالي</p>
                    </div>
                </div>
            </div>

            {{-- ============ 2. الفترة الزمنية ============ --}}
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">📅 فترة صلاحية الكوبون</h3>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            تاريخ البداية <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        @error('start_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            تاريخ الانتهاء <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date"
                               value="{{ old('end_date', date('Y-m-d', strtotime('+30 days'))) }}" required
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        @error('end_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Quick Date Buttons --}}
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500 mb-2">اختصارات سريعة:</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="setDuration(7)"
                                    class="bg-gray-100 hover:bg-indigo-100 text-gray-700 text-xs px-3 py-1.5 rounded-lg">
                                📅 أسبوع
                            </button>
                            <button type="button" @click="setDuration(14)"
                                    class="bg-gray-100 hover:bg-indigo-100 text-gray-700 text-xs px-3 py-1.5 rounded-lg">
                                📅 أسبوعان
                            </button>
                            <button type="button" @click="setDuration(30)"
                                    class="bg-gray-100 hover:bg-indigo-100 text-gray-700 text-xs px-3 py-1.5 rounded-lg">
                                📅 شهر
                            </button>
                            <button type="button" @click="setDuration(90)"
                                    class="bg-gray-100 hover:bg-indigo-100 text-gray-700 text-xs px-3 py-1.5 rounded-lg">
                                📅 3 أشهر
                            </button>
                        </div>
                    </div>

                    {{-- الحالة --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            حالة الكوبون <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-3">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status" value="active" class="peer sr-only" checked>
                                <div class="border-2 border-gray-300 rounded-lg p-3 text-center peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                                    ✅ <span class="font-medium">نشط</span>
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status" value="expired" class="peer sr-only">
                                <div class="border-2 border-gray-300 rounded-lg p-3 text-center peer-checked:border-red-500 peer-checked:bg-red-50 transition">
                                    ⛔ <span class="font-medium">معطل</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ 3. معاينة مباشرة ============ --}}
            <div class="bg-gradient-to-l from-indigo-600 to-purple-700 rounded-lg shadow mb-6 p-6 text-white">
                <h3 class="text-lg font-bold mb-3">👁️ معاينة الكوبون</h3>
                <div class="bg-white/10 backdrop-blur rounded-lg p-4 border-2 border-dashed border-white/30">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-80 mb-1">كود الخصم</p>
                            <p class="font-mono text-2xl font-bold tracking-widest" x-text="code || 'YOURCODE'"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-80 mb-1">الخصم</p>
                            <p class="text-3xl font-bold">
                                <span x-text="value || 0"></span><span x-text="type === 'percentage' ? '%' : ' ₪'"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end space-x-2 space-x-reverse">
                <a href="{{ route('merchant.coupons.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg">
                    إلغاء
                </a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium">
                    💾 إنشاء الكوبون
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
function couponForm() {
    return {
        code: '',
        type: 'percentage',
        value: '',

        generateCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < 8; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.code = result;
        },

        setDuration(days) {
            const start = new Date();
            const end = new Date();
            end.setDate(end.getDate() + days);
            
            const formatDate = (date) => date.toISOString().split('T')[0];
            
            document.querySelector('input[name="start_date"]').value = formatDate(start);
            document.querySelector('input[name="end_date"]').value = formatDate(end);
        }
    }
}
</script>
@endpush