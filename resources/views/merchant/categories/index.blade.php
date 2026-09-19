@extends('merchant.layouts.app')

@section('title', 'التصنيفات')
@section('page-title', 'إدارة التصنيفات')

@section('content')

    {{-- Header Actions --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">التصنيفات</h2>
            <p class="text-gray-500 text-sm">إجمالي: {{ $categories->total() }} تصنيف</p>
        </div>
        <a href="{{ route('merchant.categories.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse transition">
            <span>➕</span>
            <span>إضافة تصنيف</span>
        </a>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="🔍 ابحث باسم التصنيف..."
                       class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <select name="status" class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">كل الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>معطل</option>
                </select>
            </div>
            <div class="flex space-x-2 space-x-reverse">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                    بحث
                </button>
                <a href="{{ route('merchant.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg">
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>

    {{-- Categories Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($categories->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الصورة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التصنيف الأب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المنتجات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         class="w-12 h-12 rounded-lg object-cover" alt="{{ $category->name }}">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center text-xl">
                                        📂
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $category->name }}</div>
                                <div class="text-xs text-gray-500">{{ $category->slug }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $category->parent?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-xs font-medium">
                                    {{ $category->products_count }} منتج
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($category->status === 'active')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">✅ نشط</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">⛔ معطل</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('merchant.categories.edit', $category) }}"
                                       class="text-indigo-600 hover:text-indigo-900" title="تعديل">
                                        ✏️
                                    </a>
                                    <form action="{{ route('merchant.categories.destroy', $category) }}"
                                          method="POST"
                                          onsubmit="return confirm('هل أنت متأكد من حذف التصنيف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="حذف">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t bg-gray-50">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد تصنيفات بعد</h3>
                <p class="text-gray-500 mb-4">ابدأ بإضافة أول تصنيف لمتجرك</p>
                <a href="{{ route('merchant.categories.create') }}"
                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                    ➕ إضافة تصنيف
                </a>
            </div>
        @endif
    </div>

@endsection
