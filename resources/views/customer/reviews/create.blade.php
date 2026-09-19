@extends('customer.layouts.app')

@section('title', 'إضافة تقييم')

@section('content')

    <div class="max-w-3xl mx-auto px-4 py-8" x-data="reviewForm()">

        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('customer.orders.index') }}" class="hover:text-indigo-600">طلباتي</a>
            <span class="mx-2">›</span>
            <a href="{{ route('customer.orders.show', $order) }}" class="hover:text-indigo-600">
                {{ $order->order_number }}
            </a>
            <span class="mx-2">›</span>
            <span class="text-gray-800">إضافة تقييم</span>
        </nav>

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-1">⭐ إضافة تقييم</h1>
            <p class="text-gray-500">شاركنا تجربتك مع المنتجات</p>
        </div>

        <form action="{{ route('customer.reviews.store', $order) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Product Selection --}}
            @if($productsToReview->count() > 1)
                <div class="bg-white rounded-lg shadow p-5 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">اختر المنتج *</label>
                    <select name="product_id" required x-model="selectedProduct"
                            class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">— اختر منتج —</option>
                        @foreach($productsToReview as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    @error('product_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            @else
                <input type="hidden" name="product_id" value="{{ $productsToReview->first()->id }}">
                <div class="bg-white rounded-lg shadow p-5 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                            @if($productsToReview->first()->primaryImage)
                                <img src="{{ asset('storage/' . $productsToReview->first()->primaryImage->image_url) }}"
                                     class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">المنتج</p>
                            <p class="font-medium">{{ $productsToReview->first()->name }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Rating --}}
            <div class="bg-white rounded-lg shadow p-5 mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-3">تقييمك *</label>
                <div class="flex items-center gap-2 justify-center py-4">
                    <template x-for="star in 5" :key="star">
                        <button type="button" @click="rating = star"
                                @mouseenter="hoverRating = star"
                                @mouseleave="hoverRating = 0"
                                class="text-5xl transition transform hover:scale-110"
                                :class="(hoverRating || rating) >= star ? 'text-yellow-400' : 'text-gray-300'">
                            ★
                        </button>
                    </template>
                </div>
                <input type="hidden" name="rating" :value="rating" required>
                <p class="text-center text-sm text-gray-600" x-text="ratingLabel"></p>
                @error('rating') <p class="text-red-500 text-sm text-center mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Comment --}}
            <div class="bg-white rounded-lg shadow p-5 mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">تعليقك (اختياري)</label>
                <textarea name="comment" x-model="comment" rows="4" maxlength="1000"
                          placeholder="شارك تجربتك مع المنتج..."
                          class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                <p class="text-xs text-gray-500 mt-1 text-left">
                    <span x-text="comment.length"></span> / 1000
                </p>
            </div>

            {{-- Images --}}
            <div class="bg-white rounded-lg shadow p-5 mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    صور (اختياري) — الحد الأقصى 5
                </label>
                <input type="file" name="images[]" multiple accept="image/*"
                       @change="handleImages($event)"
                       class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                <p class="text-xs text-gray-500 mt-1">JPG, PNG — كل صورة أقل من 3MB</p>

                <div class="grid grid-cols-5 gap-2 mt-3" x-show="previews.length > 0">
                    <template x-for="(preview, index) in previews" :key="index">
                        <div class="relative">
                            <img :src="preview" class="w-full h-20 object-cover rounded-lg">
                        </div>
                    </template>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('customer.orders.show', $order) }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg">
                    إلغاء
                </a>
                <button type="submit"
                        :disabled="!rating"
                        :class="!rating ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700'"
                        class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold">
                    ⭐ إرسال التقييم
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
function reviewForm() {
    return {
        rating: 0,
        hoverRating: 0,
        comment: '',
        previews: [],

        get ratingLabel() {
            const r = this.hoverRating || this.rating;
            return {
                1: '😞 سيء',
                2: '😐 مقبول',
                3: '🙂 جيد',
                4: '😊 جيد جداً',
                5: '🤩 ممتاز'
            }[r] || 'اختر تقييماً';
        },

        handleImages(event) {
            this.previews = [];
            const files = Array.from(event.target.files).slice(0, 5);
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => this.previews.push(e.target.result);
                reader.readAsDataURL(file);
            });
        }
    }
}
</script>
@endpush