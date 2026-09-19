@extends('merchant.layouts.app')

@section('title', $product->name)
@section('page-title', 'تفاصيل المنتج')

@section('content')

    <div class="mb-6 flex justify-between items-center">
        <nav class="text-sm text-gray-500">
            <a href="{{ route('merchant.products.index') }}" class="hover:text-indigo-600">المنتجات</a>
            <span class="mx-2">›</span>
            <span class="text-gray-800">{{ $product->name }}</span>
        </nav>
        <div class="flex space-x-2 space-x-reverse">
            <a href="{{ route('merchant.products.edit', $product) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">✏️ تعديل</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Images --}}
        <div class="lg:col-span-1 bg-white rounded-lg shadow p-4">
            @if($product->images->count() > 0)
                <img src="{{ asset('storage/' . $product->images->first()->image_url) }}"
                     class="w-full h-80 object-cover rounded-lg mb-4">
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->images as $image)
                        <img src="{{ asset('storage/' . $image->image_url) }}" 
                             class="w-full h-16 object-cover rounded cursor-pointer hover:opacity-75">
                    @endforeach
                </div>
            @else
                <div class="h-80 flex items-center justify-center text-6xl">👕</div>
            @endif
        </div>

        {{-- Details --}}
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <span class="text-sm text-gray-500">التصنيف</span>
                    <p class="font-medium">{{ $product->category?->name ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">الماركة</span>
                    <p class="font-medium">{{ $product->brand?->name ?? '—' }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">الفئة</span>
                    <p class="font-medium">
                        @switch($product->gender)
                            @case('men') 👔 رجالي @break
                            @case('women') 👗 نسائي @break
                            @case('kids') 🧒 أطفال @break
                            @default 🧥 للجنسين
                        @endswitch
                    </p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">الحالة</span>
                    <p class="font-medium">
                        @if($product->status === 'active') ✅ نشط
                        @elseif($product->status === 'draft') 📝 مسودة
                        @else ⛔ نفد
                        @endif
                    </p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">السعر</span>
                    <p class="text-2xl font-bold text-indigo-600">
                        {{ $product->discount_price ?? $product->base_price }} ₪
                        @if($product->discount_price)
                            <span class="text-sm text-gray-400 line-through mr-2">{{ $product->base_price }} ₪</span>
                        @endif
                    </p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">التقييم</span>
                    <p class="font-medium">⭐ {{ $product->rating_avg }} / 5</p>
                </div>
            </div>

            @if($product->description)
                <div class="border-t pt-4">
                    <h3 class="font-bold text-gray-700 mb-2">الوصف</h3>
                    <p class="text-gray-600">{{ $product->description }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Variants --}}
    <div class="bg-white rounded-lg shadow mt-6">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold">🎨 المتغيرات ({{ $product->variants->count() }})</h3>
        </div>
        <div class="p-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-right">SKU</th>
                        <th class="px-4 py-2 text-right">المقاس</th>
                        <th class="px-4 py-2 text-right">اللون</th>
                        <th class="px-4 py-2 text-right">القماش</th>
                        <th class="px-4 py-2 text-right">السعر</th>
                        <th class="px-4 py-2 text-right">المخزون</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($product->variants as $variant)
                        <tr>
                            <td class="px-4 py-2 font-mono text-xs">{{ $variant->sku }}</td>
                            <td class="px-4 py-2">{{ $variant->size }}</td>
                            <td class="px-4 py-2">{{ $variant->color }}</td>
                            <td class="px-4 py-2">{{ $variant->fabric_type ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $variant->price }} ₪</td>
                            <td class="px-4 py-2">
                                @if($variant->stock_quantity <= $variant->low_stock_threshold)
                                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded">⚠️ {{ $variant->stock_quantity }}</span>
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

@endsection