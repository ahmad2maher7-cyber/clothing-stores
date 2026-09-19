@extends('merchant.layouts.app')

@section('title', 'تعديل منتج')
@section('page-title', 'تعديل المنتج')

@section('content')

    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('merchant.dashboard') }}" class="hover:text-indigo-600">لوحة التحكم</a>
        <span class="mx-2">›</span>
        <a href="{{ route('merchant.products.index') }}" class="hover:text-indigo-600">المنتجات</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800">{{ $product->name }}</span>
    </nav>

    {{-- Product Info --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">📝 معلومات المنتج</h3>
        </div>

        <form action="{{ route('merchant.products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم المنتج *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                    <textarea name="description" rows="4"
                              class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">التصنيف *</label>
                    <select name="category_id" required
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الماركة</label>
                    <select name="brand_id"
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">— بدون —</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الفئة *</label>
                    <select name="gender" required
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="men" {{ old('gender', $product->gender) == 'men' ? 'selected' : '' }}>رجالي</option>
                        <option value="women" {{ old('gender', $product->gender) == 'women' ? 'selected' : '' }}>نسائي</option>
                        <option value="kids" {{ old('gender', $product->gender) == 'kids' ? 'selected' : '' }}>أطفال</option>
                        <option value="unisex" {{ old('gender', $product->gender) == 'unisex' ? 'selected' : '' }}>للجنسين</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحالة *</label>
                    <select name="status" required
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>✅ نشط</option>
                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>📝 مسودة</option>
                        <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>⛔ نفد</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">السعر الأساسي (₪) *</label>
                    <input type="number" step="0.01" name="base_price" value="{{ old('base_price', $product->base_price) }}" required
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">سعر الخصم (₪)</label>
                    <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div class="p-6 border-t flex justify-end space-x-2 space-x-reverse">
                <a href="{{ route('merchant.products.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg">إلغاء</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                    💾 حفظ التعديلات
                </button>
            </div>
        </form>
    </div>

    {{-- Images --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">📸 صور المنتج</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($product->images as $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image->image_url) }}" class="w-full h-32 object-cover rounded-lg">
                        @if($image->is_primary)
                            <span class="absolute top-2 right-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded">
                                رئيسية
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
            <p class="text-sm text-gray-500 mt-3">لإضافة صور جديدة، احفظ المنتج وأنشئ منتجاً جديداً بصور محدثة</p>
        </div>
    </div>

    {{-- Variants --}}
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">🎨 المتغيرات ({{ $product->variants->count() }})</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-right">SKU</th>
                            <th class="px-4 py-2 text-right">المقاس</th>
                            <th class="px-4 py-2 text-right">اللون</th>
                            <th class="px-4 py-2 text-right">القماش</th>
                            <th class="px-4 py-2 text-right">السعر</th>
                            <th class="px-4 py-2 text-right">الكمية</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($product->variants as $variant)
                            <tr>
                                <td class="px-4 py-2 font-mono text-xs">{{ $variant->sku }}</td>
                                <td class="px-4 py-2"><span class="bg-indigo-100 px-2 py-1 rounded">{{ $variant->size }}</span></td>
                                <td class="px-4 py-2">{{ $variant->color }}</td>
                                <td class="px-4 py-2">{{ $variant->fabric_type ?? '—' }}</td>
                                <td class="px-4 py-2 font-medium">{{ $variant->price }} ₪</td>
                                <td class="px-4 py-2">
                                    @if($variant->stock_quantity <= $variant->low_stock_threshold)
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded">{{ $variant->stock_quantity }}</span>
                                    @else
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded">{{ $variant->stock_quantity }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection