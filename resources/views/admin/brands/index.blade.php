@extends('admin.layouts.app')

@section('title', 'الماركات')
@section('page-title', 'إدارة الماركات')

@section('content')

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">الماركات</h2>
        <p class="text-sm text-gray-500">إدارة ماركات المنتجات في المنصة</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <p class="text-xs text-gray-500 mb-1">إجمالي الماركات</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <p class="text-xs text-gray-500 mb-1">مرتبطة بمنتجات</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['with_products'] }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <p class="text-xs text-gray-500 mb-1">فارغة</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['empty'] }}</p>
        </div>
    </div>

    {{-- Header Actions --}}
    <div class="flex justify-between items-center gap-4 mb-6">
        <form method="GET" class="flex-1 max-w-md">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ابحث باسم الماركة..."
                   class="form-input">
        </form>
        <a href="{{ route('admin.brands.create') }}" class="btn-primary">
            ➕ إضافة ماركة
        </a>
    </div>

    {{-- Brands Grid --}}
    @if($brands->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
            @foreach($brands as $brand)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:border-gray-400 transition">
                    {{-- Logo --}}
                    <div class="h-32 bg-gray-50 flex items-center justify-center border-b border-gray-200">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" 
                                 class="max-h-24 max-w-[80%] object-contain">
                        @else
                            <span class="text-5xl text-gray-300">🏷️</span>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 mb-1">{{ $brand->name }}</h3>
                        @if($brand->description)
                            <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $brand->description }}</p>
                        @endif

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="badge badge-gray text-xs">{{ $brand->products_count }} منتج</span>

                            <div class="flex gap-2">
                                <a href="{{ route('admin.brands.edit', $brand) }}"
                                   class="text-gray-600 hover:text-gray-900" title="تعديل">
                                    ✏️
                                </a>
                                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST"
                                      onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-600 hover:text-red-600" title="حذف">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-center">
            {{ $brands->links() }}
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-lg text-center py-16">
            <div class="text-5xl mb-4">🏷️</div>
            <h3 class="text-base font-medium text-gray-900 mb-2">لا توجد ماركات</h3>
            <p class="text-sm text-gray-500 mb-6">ابدأ بإضافة ماركات للمنصة</p>
            <a href="{{ route('admin.brands.create') }}" class="btn-primary">
                ➕ إضافة ماركة
            </a>
        </div>
    @endif

@endsection