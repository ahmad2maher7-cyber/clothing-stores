@extends('merchant.layouts.app')

@section('title', 'التقييمات')
@section('page-title', 'التقييمات')

@section('content')

    <div class="mb-6">
        <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">التقييمات</h2>
        <p class="text-sm text-ink-muted dark:text-cream/60">إدارة تقييمات الزبائن</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">

        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">إجمالي</p>
            <p class="font-display text-3xl font-bold text-ink dark:text-cream">{{ $stats['total'] }}</p>
        </div>

        <a href="{{ route('merchant.reviews.index', ['status' => 'pending']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-5 hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('status') == 'pending' ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">⏳ قيد المراجعة</p>
            <p class="font-display text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending'] }}</p>
        </a>

        <a href="{{ route('merchant.reviews.index', ['status' => 'approved']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-5 hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('status') == 'approved' ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">✅ منشورة</p>
            <p class="font-display text-3xl font-bold text-forest-700 dark:text-gold-400">{{ $stats['approved'] }}</p>
        </a>

        <a href="{{ route('merchant.reviews.index', ['status' => 'rejected']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-5 hover:border-red-500 transition
                  {{ request('status') == 'rejected' ? 'border-red-600 dark:border-red-500' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">❌ مرفوضة</p>
            <p class="font-display text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['rejected'] }}</p>
        </a>

        <div class="bg-forest-900 dark:bg-forest-950 text-white rounded-lg p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gold-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <p class="relative text-xs tracking-widest uppercase text-gold-400 mb-2">متوسط التقييم</p>
            <div class="relative flex items-baseline gap-1">
                <p class="font-display text-3xl font-bold">{{ number_format($stats['avg_rating'], 1) }}</p>
                <i class="fa-solid fa-star text-gold-400 text-sm"></i>
            </div>
        </div>
    </div>

    @if($stats['approved'] > 0)
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6 mb-6">
            <h3 class="font-display font-bold text-ink dark:text-cream mb-5">توزيع التقييمات</h3>
            <div class="space-y-3">
                @foreach($ratingDistribution as $star => $count)
                    @php $percentage = $stats['approved'] > 0 ? ($count / $stats['approved']) * 100 : 0; @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold w-12 text-ink dark:text-cream flex items-center gap-1">
                            {{ $star }} <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                        </span>
                        <div class="flex-1 h-2 bg-stone-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="h-full bg-forest-700 dark:bg-gold-500 rounded-full transition-all" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-xs text-ink-muted dark:text-cream/60 w-24 text-left">
                            {{ $count }} ({{ round($percentage) }}%)
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث باسم منتج أو زبون..."
                       class="form-input pl-10">
                <i class="fa-solid fa-magnifying-glass text-sm text-ink-muted dark:text-cream/40 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>

            <select name="status" class="form-input">
                <option value="">كل الحالات</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ قيد المراجعة</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ منشورة</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ مرفوضة</option>
            </select>

            <button type="submit" class="btn-solid">
                <i class="fa-solid fa-magnifying-glass"></i>
                بحث
            </button>
        </form>
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

                        <div class="w-20 h-20 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded overflow-hidden shrink-0">
                            @if($review->product->primaryImage)
                                <img src="{{ asset('storage/' . $review->product->primaryImage->image_url) }}"
                                     alt="{{ $review->product->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-ink-muted dark:text-cream/40">
                                    <i class="fa-solid fa-shirt text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">

                            <div class="flex flex-wrap justify-between items-start gap-3 mb-3">
                                <div class="min-w-0">
                                    <p class="font-display font-bold text-sm text-ink dark:text-cream truncate">
                                        {{ $review->product->name }}
                                    </p>
                                    <p class="text-xs text-ink-muted dark:text-cream/50 mt-1 flex items-center gap-1.5">
                                        <i class="fa-solid fa-user text-[10px]"></i>
                                        {{ $review->customer->full_name }}
                                        <span class="opacity-40">•</span>
                                        {{ $review->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <span class="badge {{ $badgeClass }} shrink-0">{{ $statusLabel }}</span>
                            </div>

                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex text-amber-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fa-solid fa-star text-xs"></i>
                                        @else
                                            <i class="fa-regular fa-star text-xs"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-xs text-ink-muted dark:text-cream/50">({{ $review->rating }}/5)</span>
                            </div>

                            @if($review->comment)
                                <p class="text-sm text-ink-soft dark:text-cream/70 leading-relaxed mb-3">{{ $review->comment }}</p>
                            @endif

                            @if($review->images->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($review->images as $image)
                                        <a href="{{ asset('storage/' . $image->image_url) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $image->image_url) }}"
                                                 alt="صورة التقييم"
                                                 class="w-16 h-16 object-cover rounded border border-stone-200 dark:border-stone-800 hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex flex-wrap gap-2 pt-4 border-t border-stone-100 dark:border-stone-800">

                                @if($review->status !== 'approved')
                                    <form action="{{ route('merchant.reviews.approve', $review) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center gap-1.5 h-9 px-4 text-xs font-semibold tracking-widest uppercase bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 hover:bg-forest-800 dark:hover:bg-gold-400 rounded transition-colors">
                                            <i class="fa-solid fa-check text-xs"></i>
                                            اعتماد
                                        </button>
                                    </form>
                                @endif

                                @if($review->status !== 'rejected')
                                    <form action="{{ route('merchant.reviews.reject', $review) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center gap-1.5 h-9 px-4 text-xs font-semibold tracking-widest uppercase text-amber-600 dark:text-amber-400 border border-stone-300 dark:border-stone-700 hover:border-amber-300 dark:hover:border-amber-800 hover:bg-amber-50 dark:hover:bg-amber-950/20 rounded transition-colors">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                            رفض
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('merchant.reviews.destroy', $review) }}" method="POST"
                                      onsubmit="return confirm('هل أنت متأكد من الحذف النهائي؟')"
                                      class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center gap-1.5 h-9 px-4 text-xs font-semibold tracking-widest uppercase text-red-600 dark:text-red-400 border border-stone-300 dark:border-stone-700 hover:border-red-300 dark:hover:border-red-800 hover:bg-red-50 dark:hover:bg-red-950/20 rounded transition-colors">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-center">{{ $reviews->links() }}</div>
    @else
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg text-center py-20">
            <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                <i class="fa-solid fa-star text-4xl text-ink-muted dark:text-cream/40"></i>
            </div>
            <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد تقييمات</h3>
            <p class="text-sm text-ink-muted dark:text-cream/60">ستظهر تقييمات الزبائن هنا</p>
        </div>
    @endif

@endsection