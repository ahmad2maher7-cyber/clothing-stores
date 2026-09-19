@extends('customer.layouts.app')

@section('title', 'تقييماتي')

@section('content')

    <div class="max-w-4xl mx-auto px-4 py-8">

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-1">⭐ تقييماتي</h1>
            <p class="text-gray-500">تقييماتك للمنتجات التي اشتريتها</p>
        </div>

        @if($reviews->count() > 0)
            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="flex gap-4">
                            {{-- Product Image --}}
                            <a href="{{ route('products.show', $review->product->slug) }}"
                               class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                @if($review->product->primaryImage)
                                    <img src="{{ asset('storage/' . $review->product->primaryImage->image_url) }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl">👕</div>
                                @endif
                            </a>

                            {{-- Content --}}
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <a href="{{ route('products.show', $review->product->slug) }}"
                                           class="font-medium text-gray-800 hover:text-indigo-600">
                                            {{ $review->product->name }}
                                        </a>
                                        <p class="text-xs text-gray-500">{{ $review->product->store->name }}</p>
                                    </div>

                                    {{-- Status --}}
                                    @if($review->status === 'approved')
                                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded">✅ منشور</span>
                                    @elseif($review->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded">⏳ قيد المراجعة</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded">❌ مرفوض</span>
                                    @endif
                                </div>

                                {{-- Rating --}}
                                <div class="text-yellow-400 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $review->rating ? '⭐' : '☆' }}
                                    @endfor
                                    <span class="text-xs text-gray-500 mr-2">({{ $review->rating }}/5)</span>
                                </div>

                                @if($review->comment)
                                    <p class="text-sm text-gray-700 mb-2">{{ $review->comment }}</p>
                                @endif

                                {{-- Review Images --}}
                                @if($review->images->count() > 0)
                                    <div class="flex gap-2 mb-2">
                                        @foreach($review->images as $image)
                                            <img src="{{ asset('storage/' . $image->image_url) }}"
                                                 class="w-16 h-16 object-cover rounded-lg">
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Footer --}}
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>{{ $review->created_at->diffForHumans() }}</span>
                                    <form action="{{ route('customer.reviews.destroy', $review) }}" method="POST"
                                          onsubmit="return confirm('حذف التقييم؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            🗑️ حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $reviews->links() }}</div>
        @else
            <div class="bg-white rounded-lg shadow text-center py-16">
                <div class="text-6xl mb-4">⭐</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد تقييمات</h3>
                <p class="text-gray-500 mb-6">يمكنك تقييم المنتجات بعد استلام الطلبات</p>
                <a href="{{ route('customer.orders.index') }}"
                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg">
                    📦 عرض طلباتي
                </a>
            </div>
        @endif
    </div>

@endsection