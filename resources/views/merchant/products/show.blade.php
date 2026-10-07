@extends('merchant.layouts.app')

@section('title', $product->name)
@section('page-title', 'تفاصيل المنتج')

@section('content')

    {{-- ═══ Breadcrumb + Actions ═══ --}}
    <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
        <nav class="flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 flex-wrap">
            <a href="{{ route('merchant.products.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                المنتجات
            </a>
            <span class="opacity-40">/</span>
            <span class="text-ink dark:text-cream">{{ $product->name }}</span>
        </nav>
        <a href="{{ route('merchant.products.edit', $product) }}" class="btn-solid">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            تعديل
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ═══ Images ═══ --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4">
                @if($product->images->count() > 0)
                    <div class="aspect-square rounded overflow-hidden border border-stone-200 dark:border-stone-800 mb-4">
                        <img src="{{ $product->images->first()->url }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images as $image)
                            <div class="aspect-square rounded overflow-hidden border border-stone-200 dark:border-stone-800 cursor-pointer hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                                <img src="{{ $image->url }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="aspect-square flex items-center justify-center bg-stone-50 dark:bg-zinc-950 rounded border border-stone-200 dark:border-stone-800">
                        <svg class="w-20 h-20 text-ink-muted dark:text-cream/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══ Details ═══ --}}
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
            <span class="eyebrow block mb-3">— المنتج</span>
            <h1 class="font-display text-2xl md:text-3xl font-bold text-ink dark:text-cream mb-5">
                {{ $product->name }}
            </h1>

            <div class="grid grid-cols-2 gap-5 mb-6">
                <div>
                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">التصنيف</p>
                    <p class="font-medium text-ink dark:text-cream">{{ $product->category?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">الماركة</p>
                    <p class="font-medium text-ink dark:text-cream">{{ $product->brand?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">الفئة</p>
                    <p class="font-medium text-ink dark:text-cream">
                        @switch($product->gender)
                            @case('men') 👔 رجالي @break
                            @case('women') 👗 نسائي @break
                            @case('kids') 🧒 أطفال @break
                            @default 🧥 للجنسين
                        @endswitch
                    </p>
                </div>
                <div>
                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">الحالة</p>
                    @if($product->status === 'active')
                        <span class="badge badge-forest">✅ نشط</span>
                    @elseif($product->status === 'draft')
                        <span class="badge badge-stone">📝 مسودة</span>
                    @else
                        <span class="badge badge-danger">⛔ نفد</span>
                    @endif
                </div>
                <div>
                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">السعر</p>
                    <div class="flex items-baseline gap-2">
                        @if($product->discount_price)
                            <span class="font-display text-2xl font-bold text-forest-700 dark:text-gold-400">
                                {{ number_format($product->discount_price, 0) }} ₪
                            </span>
                            <span class="text-sm text-ink-muted dark:text-cream/40 line-through">
                                {{ number_format($product->base_price, 0) }} ₪
                            </span>
                        @else
                            <span class="font-display text-2xl font-bold text-forest-700 dark:text-gold-400">
                                {{ number_format($product->base_price, 0) }} ₪
                            </span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">التقييم</p>
                    <p class="font-medium text-ink dark:text-cream flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-500 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        {{ $product->rating_avg }} / 5
                    </p>
                </div>
            </div>

            @if($product->description)
                <div class="border-t border-stone-200 dark:border-stone-800 pt-5">
                    <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">الوصف</p>
                    <p class="text-sm text-ink-soft dark:text-cream/70 leading-relaxed">{{ $product->description }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ═══ Variants ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden mt-6">
        <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
            </div>
            <div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream">
                    المتغيرات <span class="text-ink-muted dark:text-cream/50 text-sm">({{ $product->variants->count() }})</span>
                </h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">SKU</th>
                        <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المقاس</th>
                        <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">اللون</th>
                        <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">القماش</th>
                        <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">السعر</th>
                        <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المخزون</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                    @foreach($product->variants as $variant)
                        <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-ink-muted dark:text-cream/60">{{ $variant->sku }}</td>
                            <td class="px-4 py-3">
                                <span class="badge badge-stone">{{ $variant->size }}</span>
                            </td>
                            <td class="px-4 py-3 text-ink-soft dark:text-cream/70">{{ $variant->color }}</td>
                            <td class="px-4 py-3 text-ink-soft dark:text-cream/70">{{ $variant->fabric_type ?? '—' }}</td>
                            <td class="px-4 py-3 font-display font-bold text-ink dark:text-cream">{{ $variant->price }} ₪</td>
                            <td class="px-4 py-3">
                                @if($variant->stock_quantity <= $variant->low_stock_threshold)
                                    <span class="badge badge-danger">⚠️ {{ $variant->stock_quantity }}</span>
                                @else
                                    <span class="badge badge-forest">{{ $variant->stock_quantity }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection