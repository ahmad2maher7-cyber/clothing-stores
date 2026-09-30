@extends('merchant.layouts.app')

@section('title', 'إضافة منتج')
@section('page-title', 'إضافة منتج جديد')

@section('content')

    <div x-data="productForm()">

        {{-- ═══ Breadcrumb ═══ --}}
        <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
            <a href="{{ route('merchant.dashboard') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                لوحة التحكم
            </a>
            <span class="opacity-40">/</span>
            <a href="{{ route('merchant.products.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                المنتجات
            </a>
            <span class="opacity-40">/</span>
            <span class="text-ink dark:text-cream">إضافة جديد</span>
        </nav>

        <form action="{{ route('merchant.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- ═══ 1. Product Info ═══ --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-display text-lg font-bold text-ink dark:text-cream">معلومات المنتج</h3>
                        <p class="text-xs text-ink-muted dark:text-cream/60">البيانات الأساسية للمنتج</p>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="md:col-span-2">
                        <label class="form-label">
                            اسم المنتج <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="مثال: قميص قطني كلاسيك"
                               class="form-input">
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" rows="4"
                                  placeholder="وصف تفصيلي للمنتج..."
                                  class="form-input h-auto py-3 resize-none">{{ old('description') }}</textarea>
                        @error('description') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">
                            التصنيف <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" required class="form-input">
                            <option value="">— اختر تصنيفاً —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->parent ? $cat->parent->name . ' › ' : '' }}{{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">الماركة</label>
                        <select name="brand_id" class="form-input">
                            <option value="">— بدون ماركة —</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">
                            الفئة <span class="text-red-500">*</span>
                        </label>
                        <select name="gender" required class="form-input">
                            <option value="men" {{ old('gender') == 'men' ? 'selected' : '' }}>👔 رجالي</option>
                            <option value="women" {{ old('gender') == 'women' ? 'selected' : '' }}>👗 نسائي</option>
                            <option value="kids" {{ old('gender') == 'kids' ? 'selected' : '' }}>🧒 أطفال</option>
                            <option value="unisex" {{ old('gender', 'unisex') == 'unisex' ? 'selected' : '' }}>🧥 للجنسين</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">
                            الحالة <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required class="form-input">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>✅ نشط</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📝 مسودة</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">
                            السعر الأساسي (₪) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="base_price" value="{{ old('base_price') }}" required min="0"
                               class="form-input">
                        @error('base_price') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">سعر الخصم (₪)</label>
                        <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price') }}" min="0"
                               class="form-input">
                        @error('discount_price') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ═══ 2. Images ═══ --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-display text-lg font-bold text-ink dark:text-cream">صور المنتج</h3>
                        <p class="text-xs text-ink-muted dark:text-cream/60">الحد الأقصى 5 صور — الأولى رئيسية</p>
                    </div>
                </div>

                <div class="p-6">
                    <input type="file" name="images[]" multiple accept="image/*" required
                           @change="handleImages($event)"
                           class="form-input file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-forest-50 dark:file:bg-forest-950/40 file:text-forest-700 dark:file:text-gold-400 hover:file:bg-forest-100 dark:hover:file:bg-forest-900/50">

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-4" x-show="previews.length > 0" x-cloak>
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative aspect-square">
                                <img :src="preview" class="w-full h-full object-cover rounded border border-stone-200 dark:border-stone-800">
                                <span x-show="index === 0" 
                                      class="absolute top-2 right-2 bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 text-[10px] font-bold tracking-widest uppercase px-2 py-1 rounded">
                                    رئيسية
                                </span>
                            </div>
                        </template>
                    </div>

                    @error('images') <p class="form-error">{{ $message }}</p> @enderror
                    @error('images.*') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ═══ 3. Variants ═══ --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-display text-lg font-bold text-ink dark:text-cream">المتغيرات</h3>
                            <p class="text-xs text-ink-muted dark:text-cream/60">المقاسات والألوان</p>
                        </div>
                    </div>
                    <button type="button" @click="addVariant()"
                            class="btn-solid">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        إضافة متغير
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <template x-for="(variant, index) in variants" :key="index">
                        <div class="border border-stone-200 dark:border-stone-800 rounded-lg p-4 bg-stone-50 dark:bg-zinc-950">
                            <div class="flex justify-between items-center mb-4">
                                <span class="font-medium text-sm text-ink dark:text-cream">
                                    متغير #<span x-text="index + 1"></span>
                                </span>
                                <button type="button" @click="removeVariant(index)"
                                        x-show="variants.length > 1"
                                        class="inline-flex items-center gap-1 text-xs font-medium text-red-600 dark:text-red-400 hover:opacity-70 transition-opacity">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    حذف
                                </button>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                <div>
                                    <label class="block text-[10px] tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50 mb-2">
                                        المقاس <span class="text-red-500">*</span>
                                    </label>
                                    <select :name="`variants[${index}][size]`" x-model="variant.size" required
                                            class="form-input">
                                        <option value="">—</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50 mb-2">
                                        اللون <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" :name="`variants[${index}][color]`" x-model="variant.color" required
                                           placeholder="أبيض"
                                           class="form-input">
                                </div>

                                <div>
                                    <label class="block text-[10px] tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50 mb-2">
                                        القماش
                                    </label>
                                    <input type="text" :name="`variants[${index}][fabric_type]`" x-model="variant.fabric_type"
                                           placeholder="قطن"
                                           class="form-input">
                                </div>

                                <div>
                                    <label class="block text-[10px] tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50 mb-2">
                                        السعر (₪) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" step="0.01" :name="`variants[${index}][price]`" x-model="variant.price" required min="0"
                                           class="form-input">
                                </div>

                                <div>
                                    <label class="block text-[10px] tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50 mb-2">
                                        الكمية <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" :name="`variants[${index}][stock_quantity]`" x-model="variant.stock_quantity" required min="0"
                                           class="form-input">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- ═══ Actions ═══ --}}
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                <a href="{{ route('merchant.products.index') }}" class="btn-outline justify-center">
                    إلغاء
                </a>
                <button type="submit" class="btn-solid justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    حفظ المنتج
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