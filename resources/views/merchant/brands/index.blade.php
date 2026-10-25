@extends('merchant.layouts.app')

@section('title', 'الماركات')
@section('page-title', 'الماركات')

@section('content')

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">الماركات</h2>
            <p class="text-sm text-ink-muted dark:text-cream/60">إدارة ماركات المنتجات</p>
        </div>
        <a href="{{ route('merchant.brands.create') }}" class="btn-solid whitespace-nowrap">
            <i class="fa-solid fa-plus"></i>
            إضافة ماركة
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 mb-6">
        <form method="GET" class="flex gap-2">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث باسم الماركة..."
                       class="form-input pl-10">
                <i class="fa-solid fa-magnifying-glass text-sm text-ink-muted dark:text-cream/40 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>
            <button type="submit" class="btn-solid">
                بحث
            </button>
            @if(request('search'))
                <a href="{{ route('merchant.brands.index') }}" class="btn-outline">
                    إعادة
                </a>
            @endif
        </form>
    </div>

    {{-- Brands Grid --}}
    @if($brands->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-6">
            @foreach($brands as $brand)
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden hover:border-forest-500 dark:hover:border-gold-500 transition group">
                    {{-- Logo --}}
                    <div class="h-32 bg-stone-50 dark:bg-zinc-800 flex items-center justify-center border-b border-stone-200 dark:border-stone-800">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}"
                                 alt="{{ $brand->name }}"
                                 class="max-h-24 max-w-[80%] object-contain group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-16 h-16 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded-full">
                                <i class="fa-solid fa-tag text-2xl"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <h3 class="font-display font-bold text-ink dark:text-cream mb-1 line-clamp-1">{{ $brand->name }}</h3>
                        <p class="text-xs text-ink-muted dark:text-cream/50 mb-3 line-clamp-2 min-h-[2rem]">
                            {{ $brand->description ?? 'لا يوجد وصف' }}
                        </p>

                        <div class="flex items-center justify-between pt-3 border-t border-stone-100 dark:border-stone-800">
                            <span class="badge badge-stone">
                                <i class="fa-solid fa-box text-[10px]"></i>
                                {{ $brand->products_count }} منتج
                            </span>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('merchant.brands.edit', $brand) }}"
                                   class="text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('merchant.brands.destroy', $brand) }}" method="POST"
                                      onsubmit="return confirm('هل أنت متأكد من حذف الماركة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-medium text-red-600 dark:text-red-400 hover:opacity-70 transition-opacity">
                                        <i class="fa-solid fa-trash"></i>
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
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg text-center py-20">
            <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                <i class="fa-solid fa-tags text-4xl text-ink-muted dark:text-cream/40"></i>
            </div>
            <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد ماركات</h3>
            <p class="text-sm text-ink-muted dark:text-cream/60 mb-6">ابدأ بإضافة ماركات لمنتجاتك</p>
            <a href="{{ route('merchant.brands.create') }}" class="btn-solid inline-flex">
                <i class="fa-solid fa-plus"></i>
                إضافة ماركة
            </a>
        </div>
    @endif

@endsection