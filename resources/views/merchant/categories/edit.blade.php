@extends('merchant.layouts.app')

@section('title', 'تعديل تصنيف')
@section('page-title', 'تعديل التصنيف')

@section('content')

    {{-- Breadcrumb --}}
    <nav class="mb-6 text-sm text-gray-500">
        <a href="{{ route('merchant.dashboard') }}" class="hover:text-indigo-600">لوحة التحكم</a>
        <span class="mx-2">›</span>
        <a href="{{ route('merchant.categories.index') }}" class="hover:text-indigo-600">التصنيفات</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800">{{ $category->name }}</span>
    </nav>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-800">تعديل: {{ $category->name }}</h2>
        </div>

        <form action="{{ route('merchant.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- الاسم --}}
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        اسم التصنيف <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- التصنيف الأب --}}
                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                        التصنيف الأب (اختياري)
                    </label>
                    <select name="parent_id" id="parent_id"
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">— بدون تصنيف أب —</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ترتيب العرض --}}
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                        ترتيب العرض
                    </label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}"
                           min="0"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    @error('sort_order')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- الصورة --}}
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        صورة التصنيف
                    </label>
                    @if($category->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $category->image) }}" 
                                 class="w-20 h-20 rounded-lg object-cover" alt="{{ $category->name }}">
                        </div>
                    @endif
                    <input type="file" name="image" id="image" accept="image/*"
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">اتركها فارغة للإبقاء على الصورة الحالية</p>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- الحالة --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        الحالة <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>✅ نشط</option>
                        <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>⛔ معطل</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-between mt-8 pt-6 border-t">
                <form action="{{ route('merchant.categories.destroy', $category) }}"
                      method="POST"
                      onsubmit="return confirm('هل أنت متأكد من حذف التصنيف؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">
                        🗑️ حذف
                    </button>
                </form>

                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('merchant.categories.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg">
                        إلغاء
                    </a>
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                        💾 حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>
    </div>

@endsection