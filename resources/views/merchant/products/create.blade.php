@extends('merchant.layouts.app')

@section('title', 'إضافة منتج')
@section('page-title', 'إضافة منتج جديد')

@section('content')

    <div x-data="productForm()">

        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('merchant.dashboard') }}" class="hover:text-indigo-600">لوحة التحكم</a>
            <span class="mx-2">›</span>
            <a href="{{ route('merchant.products.index') }}" class="hover:text-indigo-600">المنتجات</a>
            <span class="mx-2">›</span>
            <span class="text-gray-800">إضافة جديد</span>
        </nav>

        <form action="{{ route('merchant.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ============ 1. معلومات المنتج ============ --}}
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">📝 معلومات المنتج</h3>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            اسم المنتج <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="مثال: قميص قطني كلاسيك">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                        <textarea name="description" rows="4"
                                  class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="وصف تفصيلي للمنتج...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            التصنيف <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" required
                                class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">— اختر تصنيفاً —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->parent ? $cat->parent->name . ' › ' : '' }}{{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الماركة</label>
                        <select name="brand_id"
                                class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">— بدون ماركة —</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            الفئة <span class="text-red-500">*</span>
                        </label>
                        <select name="gender" required
                                class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="men" {{ old('gender') == 'men' ? 'selected' : '' }}>👔 رجالي</option>
                            <option value="women" {{ old('gender') == 'women' ? 'selected' : '' }}>👗 نسائي</option>
                            <option value="kids" {{ old('gender') == 'kids' ? 'selected' : '' }}>🧒 أطفال</option>
                            <option value="unisex" {{ old('gender', 'unisex') == 'unisex' ? 'selected' : '' }}>🧥 للجنسين</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            الحالة <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                                class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>✅ نشط</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📝 مسودة</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            السعر الأساسي (₪) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="base_price" value="{{ old('base_price') }}" required min="0"
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        @error('base_price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">سعر الخصم (₪)</label>
                        <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price') }}" min="0"
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        @error('discount_price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ============ 2. صور المنتج ============ --}}
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">📸 صور المنتج</h3>
                    <p class="text-sm text-gray-500">الحد الأقصى 5 صور — الصورة الأولى رئيسية</p>
                </div>

                <div class="p-6">
                    <input type="file" name="images[]" multiple accept="image/*" required
                           @change="handleImages($event)"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-4" x-show="previews.length > 0">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative">
                                <img :src="preview" class="w-full h-32 object-cover rounded-lg">
                                <span x-show="index === 0" class="absolute top-2 right-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded">
                                    رئيسية
                                </span>
                            </div>
                        </template>
                    </div>

                    @error('images') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    @error('images.*') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ============ 3. المتغيرات ============ --}}
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">🎨 المتغيرات (المقاسات والألوان)</h3>
                        <p class="text-sm text-gray-500">أضف كل مقاس/لون كمتغير منفصل</p>
                    </div>
                    <button type="button" @click="addVariant()"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                        ➕ إضافة متغير
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <template x-for="(variant, index) in variants" :key="index">
                        <div class="border rounded-lg p-4 bg-gray-50">
                            <div class="flex justify-between items-center mb-3">
                                <span class="font-medium text-gray-700">متغير #<span x-text="index + 1"></span></span>
                                <button type="button" @click="removeVariant(index)"
                                        x-show="variants.length > 1"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                    🗑️ حذف
                                </button>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">المقاس *</label>
                                    <select :name="`variants[${index}][size]`" x-model="variant.size" required
                                            class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">—</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">اللون *</label>
                                    <input type="text" :name="`variants[${index}][color]`" x-model="variant.color" required
                                           placeholder="أبيض"
                                           class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">القماش</label>
                                    <input type="text" :name="`variants[${index}][fabric_type]`" x-model="variant.fabric_type"
                                           placeholder="قطن"
                                           class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">السعر (₪) *</label>
                                    <input type="number" step="0.01" :name="`variants[${index}][price]`" x-model="variant.price" required min="0"
                                           class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">الكمية *</label>
                                    <input type="number" :name="`variants[${index}][stock_quantity]`" x-model="variant.stock_quantity" required min="0"
                                           class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end space-x-2 space-x-reverse">
                <a href="{{ route('merchant.products.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg">
                    إلغاء
                </a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium">
                    💾 حفظ المنتج
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
function productForm() {
    return {
        previews: [],
        variants: [
            { size: '', color: '', fabric_type: '', price: '', stock_quantity: 10 }
        ],

        handleImages(event) {
            this.previews = [];
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previews.push(e.target.result);
                };
                reader.readAsDataURL(files[i]);
            }
        },

        addVariant() {
            this.variants.push({
                size: '',
                color: '',
                fabric_type: '',
                price: '',
                stock_quantity: 10
            });
        },

        removeVariant(index) {
            this.variants.splice(index, 1);
        }
    }
}
</script>
@endpush