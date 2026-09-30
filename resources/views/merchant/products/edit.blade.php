@extends('merchant.layouts.app')

@section('title', 'تعديل منتج')
@section('page-title', 'تعديل المنتج')

@section('content')

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
        <span class="text-ink dark:text-cream">{{ $product->name }}</span>
    </nav>

    {{-- ═══ Product Info ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden mb-6">
        <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream">معلومات المنتج</h3>
                <p class="text-xs text-ink-muted dark:text-cream/60">حدّث بيانات المنتج</p>
            </div>
        </div>

        <form action="{{ route('merchant.products.update', $product) }}" method="POST">
            @csrf @method('PUT')

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <label class="form-label">
                        اسم المنتج <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="form-input">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" rows="4"
                              class="form-input h-auto py-3 resize-none">{{ old('description', $product->description) }}</textarea>
                </div>

                <div>
                    <label class="form-label">
                        التصنيف <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" required class="form-input">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">الماركة</label>
                    <select name="brand_id" class="form-input">
                        <option value="">— بدون —</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
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
                        <option value="men" {{ old('gender', $product->gender) == 'men' ? 'selected' : '' }}>رجالي</option>
                        <option value="women" {{ old('gender', $product->gender) == 'women' ? 'selected' : '' }}>نسائي</option>
                        <option value="kids" {{ old('gender', $product->gender) == 'kids' ? 'selected' : '' }}>أطفال</option>
                        <option value="unisex" {{ old('gender', $product->gender) == 'unisex' ? 'selected' : '' }}>للجنسين</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">
                        الحالة <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required class="form-input">
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>✅ نشط</option>
                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>📝 مسودة</option>
                        <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>⛔ نفد</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">
                        السعر الأساسي (₪) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" name="base_price" value="{{ old('base_price', $product->base_price) }}" required
                           class="form-input">
                </div>

                <div>
                    <label class="form-label">سعر الخصم (₪)</label>
                    <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}"
                           class="form-input">
                </div>
            </div>

            <div class="p-6 border-t border-stone-200 dark:border-stone-800 flex justify-end gap-3">
                <a href="{{ route('merchant.products.index') }}" class="btn-outline">إلغاء</a>
                <button type="submit" class="btn-solid">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>

    {{-- ═══ Images ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden mb-6">
        <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream">صور المنتج</h3>
                <p class="text-xs text-ink-muted dark:text-cream/60">{{ $product->images->count() }} صورة</p>
            </div>
        </div>

        <div class="p-6">
            @if($product->images->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                        <div class="relative aspect-square rounded overflow-hidden border border-stone-200 dark:border-stone-800 group">
                            <img src="{{ asset('storage/' . $image->image_url) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @if($image->is_primary)
                                <span class="absolute top-2 right-2 bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 text-[10px] font-bold tracking-widest uppercase px-2 py-1 rounded">
                                    رئيسية
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-ink-muted dark:text-cream/50 text-sm">
                    لا توجد صور
                </div>
            @endif
        </div>
    </div>

    {{-- ═══ Variants ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
        <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
            </div>
            <div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream">
                    المتغيرات <span class="text-ink-muted dark:text-cream/50 text-sm">({{ $product->variants->count() }})</span>
                </h3>
                <p class="text-xs text-ink-muted dark:text-cream/60">المقاسات والألوان والكميات</p>
            </div>
        </div>

        <div class="p-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">SKU</th>
                        <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المقاس</th>
                        <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">اللون</th>
                        <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">القماش</th>
                        <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">السعر</th>
                        <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الكمية</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                    @foreach($product->variants as $variant)
                        <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-ink-muted dark:text-cream/60">{{ $variant->sku }}</td>
                            <td class="px-4 py-3">
                                <span class="badge badge-stone">{{ $variant->size }}</span>
                            </td>
                            <td class="px-4 py-3 text-ink-soft dark:text-cream/70">{{ $variant->color }}</td>
                            <td class="px-4 py-3 text-ink-soft dark:text-cream/70">{{ $variant->fabric_type ?? '—' }}</td>
                            <td class="px-4 py-3 font-display font-bold text-ink dark:text-cream">{{ $variant->price }} ₪</td>
                            <td class="px-4 py-3">
                                @if($variant->stock_quantity <= $variant->low_stock_threshold)
                                    <span class="badge badge-danger">⚠️ {{ $variant->stock_quantity }}</span>
                                @else
                                    <span class="badge badge-forest">{{ $variant->stock_quantity }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection