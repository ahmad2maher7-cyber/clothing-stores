@extends('customer.layouts.app')

@section('title', 'إضافة تقييم')

@section('content')

    <div class="container-x py-10 lg:py-14">
        <div class="max-w-3xl mx-auto" x-data="reviewForm()">

            {{-- ═══ Breadcrumb ═══ --}}
            <nav class="mb-6 flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
                <a href="{{ route('customer.orders.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                    طلباتي
                </a>
                <span class="opacity-40">/</span>
                <a href="{{ route('customer.orders.show', $order) }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                    {{ $order->order_number }}
                </a>
                <span class="opacity-40">/</span>
                <span class="text-ink dark:text-cream">إضافة تقييم</span>
            </nav>

            {{-- ═══ Header ═══ --}}
            <div class="mb-8">
                <span class="eyebrow block mb-3">— تجربتك</span>
                <h1 class="font-display text-3xl font-bold text-ink dark:text-cream mb-2">
                    إضافة تقييم
                </h1>
                <p class="text-sm text-ink-muted dark:text-cream/60">
                    شاركنا تجربتك مع المنتجات
                </p>
            </div>

            <form action="{{ route('customer.reviews.store', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- ═══ Product Selection ═══ --}}
                @if($productsToReview->count() > 1)
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
                        <label class="form-label">اختر المنتج <span class="text-red-500">*</span></label>
                        <select name="product_id" required x-model="selectedProduct"
                                class="form-input">
                            <option value="">— اختر منتج —</option>
                            @foreach($productsToReview as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('product_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                @else
                    <input type="hidden" name="product_id" value="{{ $productsToReview->first()->id }}">
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded overflow-hidden shrink-0">
                                @if($productsToReview->first()->primaryImage)
                                    <img src="{{ $productsToReview->first()->primaryImage->url }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-ink-muted dark:text-cream/40">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">المنتج</p>
                                <p class="font-display font-bold text-ink dark:text-cream truncate">{{ $productsToReview->first()->name }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ═══ Rating ═══ --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                    <label class="form-label mb-5 text-center block">تقييمك <span class="text-red-500">*</span></label>

                    <div class="flex items-center justify-center gap-3 py-4">
                        <template x-for="star in 5" :key="star">
                            <button type="button" 
                                    @click="rating = star"
                                    @mouseenter="hoverRating = star"
                                    @mouseleave="hoverRating = 0"
                                    class="transition-all duration-200 transform hover:scale-110 focus:outline-none"
                                    :class="(hoverRating || rating) >= star ? 'text-amber-400' : 'text-stone-300 dark:text-stone-700'">
                                <svg class="w-12 h-12" viewBox="0 0 24 24" 
                                     :class="(hoverRating || rating) >= star ? 'fill-current' : 'fill-none'"
                                     stroke="currentColor" stroke-width="1.5">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                        </template>
                    </div>

                    <input type="hidden" name="rating" :value="rating" required>
                    <p class="text-center text-sm text-ink-muted dark:text-cream/60 mt-3 min-h-[1.5rem]" x-text="ratingLabel"></p>

                    @error('rating') <p class="form-error text-center">{{ $message }}</p> @enderror
                </div>

                {{-- ═══ Comment ═══ --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                    <label class="form-label">تعليقك <span class="text-ink-faint dark:text-cream/30">(اختياري)</span></label>
                    <textarea name="comment" x-model="comment" rows="4" maxlength="1000"
                              placeholder="شارك تجربتك مع المنتج..."
                              class="form-input h-auto py-3 resize-none">{{ old('comment') }}</textarea>
                    <p class="text-xs text-ink-muted dark:text-cream/40 mt-2 text-left">
                        <span x-text="comment.length"></span> / 1000
                    </p>
                </div>

                {{-- ═══ Images ═══ --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                    <label class="form-label">
                        صور <span class="text-ink-faint dark:text-cream/30">(اختياري — الحد الأقصى 5)</span>
                    </label>

                    <input type="file" name="images[]" multiple accept="image/*"
                           @change="handleImages($event)"
                           class="form-input file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-forest-50 dark:file:bg-forest-950/40 file:text-forest-700 dark:file:text-gold-400 hover:file:bg-forest-100 dark:hover:file:bg-forest-900/50">
                    <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">JPG, PNG — كل صورة أقل من 3MB</p>

                    {{-- Previews --}}
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-3 mt-4" x-show="previews.length > 0" x-cloak>
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="aspect-square rounded overflow-hidden border border-stone-200 dark:border-stone-800">
                                <img :src="preview" class="w-full h-full object-cover">
                            </div>
                        </template>
                    </div>
                </div>

                {{-- ═══ Actions ═══ --}}
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
                    <a href="{{ route('customer.orders.show', $order) }}"
                       class="btn-outline justify-center">
                        إلغاء
                    </a>
                    <button type="submit"
                            :disabled="!rating"
                            :class="!rating ? 'opacity-50 cursor-not-allowed' : ''"
                            class="btn-solid justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        إرسال التقييم
                    </button>
                </div>
            </form>
        </div>
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
            }[r] || 'اختر تقيماً';
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