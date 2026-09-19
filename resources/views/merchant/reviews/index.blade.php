@extends('merchant.layouts.app')

@section('title', 'التقييمات')
@section('page-title', 'إدارة التقييمات')

@section('content')

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">⭐ التقييمات</h2>
        <p class="text-gray-500 text-sm">إدارة ومراجعة تقييمات الزبائن</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-r-4 border-indigo-500">
            <p class="text-xs text-gray-500 mb-1">إجمالي التقييمات</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        </div>

        <a href="{{ route('merchant.reviews.index', ['status' => 'pending']) }}"
           class="bg-white rounded-lg shadow p-4 border-r-4 border-yellow-500 hover:shadow-md transition">
            <p class="text-xs text-gray-500 mb-1">⏳ قيد المراجعة</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </a>

        <a href="{{ route('merchant.reviews.index', ['status' => 'approved']) }}"
           class="bg-white rounded-lg shadow p-4 border-r-4 border-green-500 hover:shadow-md transition">
            <p class="text-xs text-gray-500 mb-1">✅ منشورة</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
        </a>

        <a href="{{ route('merchant.reviews.index', ['status' => 'rejected']) }}"
           class="bg-white rounded-lg shadow p-4 border-r-4 border-red-500 hover:shadow-md transition">
            <p class="text-xs text-gray-500 mb-1">❌ مرفوضة</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
        </a>

        <div class="bg-gradient-to-l from-indigo-600 to-purple-700 text-white rounded-lg shadow p-4">
            <p class="text-xs mb-1 opacity-90">متوسط التقييم</p>
            <div class="flex items-baseline gap-1">
                <p class="text-3xl font-bold">{{ number_format($stats['avg_rating'], 1) }}</p>
                <span class="text-yellow-300">★</span>
            </div>
        </div>
    </div>

    {{-- Rating Distribution --}}
    @if($stats['approved'] > 0)
        <div class="bg-white rounded-lg shadow p-5 mb-6">
            <h3 class="font-bold text-gray-800 mb-4">📊 توزيع التقييمات</h3>
            <div class="space-y-2">
                @foreach($ratingDistribution as $star => $count)
                    @php
                        $percentage = $stats['approved'] > 0 ? ($count / $stats['approved']) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium w-16">{{ $star }} ★</span>
                        <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-400 rounded-full transition-all" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-16 text-left">{{ $count }} ({{ round($percentage) }}%)</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 اسم منتج أو زبون..."
                   class="md:col-span-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">

            <select name="status" class="border-gray-300 rounded-lg focus:border-indigo-500">
                <option value="">كل الحالات</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ قيد المراجعة</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ منشورة</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ مرفوضة</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex-1">
                    بحث
                </button>
                <a href="{{ route('merchant.reviews.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                    إعادة
                </a>
            </div>
        </form>
    </div>

    {{-- Reviews List --}}
    @if($reviews->count() > 0)
        <div class="space-y-4">
            @foreach($reviews as $review)
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="flex gap-4">

                        {{-- Product --}}
                        <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                            @if($review->product->primaryImage)
                                <img src="{{ asset('storage/' . $review->product->primaryImage->image_url) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl">👕</div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <a href="{{ route('merchant.products.show', $review->product) }}"
                                       class="font-medium text-gray-800 hover:text-indigo-600">
                                        {{ $review->product->name }}
                                    </a>
                                    <p class="text-xs text-gray-500">
                                        بواسطة {{ $review->customer->full_name }} • 
                                        {{ $review->created_at->diffForHumans() }}
                                    </p>
                                </div>

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
                            </div>

                            @if($review->comment)
                                <p class="text-sm text-gray-700 mb-2">{{ $review->comment }}</p>
                            @endif

                            {{-- Images --}}
                            @if($review->images->count() > 0)
                                <div class="flex gap-2 mb-3">
                                    @foreach($review->images as $image)
                                        <a href="{{ asset('storage/' . $image->image_url) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $image->image_url) }}"
                                                 class="w-16 h-16 object-cover rounded-lg hover:opacity-80 transition">
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Actions --}}
                            <div class="flex gap-2 pt-3 border-t">
                                @if($review->status !== 'approved')
                                    <form action="{{ route('merchant.reviews.approve', $review) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-sm">
                                            ✅ اعتماد
                                        </button>
                                    </form>
                                @endif

                                @if($review->status !== 'rejected')
                                    <form action="{{ route('merchant.reviews.reject', $review) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-sm">
                                            ❌ رفض
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('merchant.reviews.destroy', $review) }}" method="POST"
                                      onsubmit="return confirm('حذف التقييم نهائياً؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded text-sm">
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
            <p class="text-gray-500">ستظهر تقييمات الزبائن هنا</p>
        </div>
    @endif

@endsection