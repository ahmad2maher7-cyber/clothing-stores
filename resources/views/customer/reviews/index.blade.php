@extends('customer.layouts.app')

@section('title', 'تقييماتي')

@section('content')

    <div class="container-x py-10 lg:py-14">

        {{-- ═══ Header ═══ --}}
        <div class="mb-8">
            <span class="eyebrow block mb-3">— حسابي</span>
            <h1 class="font-display text-3xl font-bold text-ink dark:text-cream mb-2">
                تقييماتي
            </h1>
            <p class="text-sm text-ink-muted dark:text-cream/60">
                تقييماتك للمنتجات التي اشتريتها
            </p>
        </div>

        @if($reviews->count() > 0)
            <div class="space-y-4">
                @foreach($reviews as $review)
                    @php
                        $statusConfig = [
                            'approved' => ['منشور', 'badge-forest'],
                            'pending' => ['قيد المراجعة', 'badge-stone'],
                            'rejected' => ['مرفوض', 'badge-danger'],
                        ];
                        [$statusLabel, $badgeClass] = $statusConfig[$review->status] ?? ['—', 'badge-stone'];
                    @endphp

                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                        <div class="flex gap-4">

                            {{-- Product Image --}}
                            <a href="{{ route('products.show', $review->product->slug) }}"
                               class="w-20 h-20 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded overflow-hidden shrink-0">
                                @if($review->product->primaryImage)
                                    <img src="{{ $review->product->primaryImage->url }}"
                                         alt="{{ $review->product->name }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-ink-muted dark:text-cream/40">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">

                                {{-- Header --}}
                                <div class="flex flex-wrap justify-between items-start gap-3 mb-3">
                                    <div class="min-w-0">
                                        <a href="{{ route('products.show', $review->product->slug) }}"
                                           class="font-display font-bold text-sm text-ink dark:text-cream hover:text-forest-700 dark:hover:text-gold-400 transition-colors line-clamp-1">
                                            {{ $review->product->name }}
                                        </a>
                                        <p class="text-xs text-ink-muted dark:text-cream/50 mt-1 flex items-center gap-1.5">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            {{ $review->product->store->name }}
                                        </p>
                                    </div>
                                    <span class="badge {{ $badgeClass }} shrink-0">{{ $statusLabel }}</span>
                                </div>

                                {{-- Rating --}}
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex text-amber-500">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="1.5">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-xs text-ink-muted dark:text-cream/50">({{ $review->rating }}/5)</span>
                                </div>

                                {{-- Comment --}}
                                @if($review->comment)
                                    <p class="text-sm text-ink-soft dark:text-cream/70 leading-relaxed mb-3">
                                        {{ $review->comment }}
                                    </p>
                                @endif

                                {{-- Images --}}
                                @if($review->images->count() > 0)
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($review->images as $image)
                                            <a href="{{ $image->url }}" target="_blank">
                                                <img src="{{ $image->url }}"
                                                     alt="صورة التقييم"
                                                     class="w-16 h-16 object-cover rounded border border-stone-200 dark:border-stone-800 hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Footer --}}
                                <div class="flex justify-between items-center gap-3 pt-3 border-t border-stone-100 dark:border-stone-800">
                                    <span class="text-xs text-ink-muted dark:text-cream/50 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $review->created_at->diffForHumans() }}
                                    </span>
                                    <form action="{{ route('customer.reviews.destroy', $review) }}" method="POST"
                                          onsubmit="return confirm('هل أنت متأكد من حذف التقييم؟')">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:opacity-70 transition-opacity">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">{{ $reviews->links() }}</div>
        @else
            {{-- ═══ Empty State ═══ --}}
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <svg class="w-10 h-10 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد تقييمات</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60 mb-6">
                    يمكنك تقييم المنتجات بعد استلام الطلبات
                </p>
                <a href="{{ route('customer.orders.index') }}" class="btn-solid inline-flex">
                    عرض طلباتي
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>

@endsection