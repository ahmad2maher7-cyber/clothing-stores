@extends('admin.layouts.app')

@section('title', 'الماركات')
@section('page-title', 'إدارة الماركات')

@section('content')

    <div class="mb-6">
        <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">الماركات</h2>
        <p class="text-sm text-ink-muted dark:text-cream/60">إدارة ماركات المنتجات في المنصة</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">إجمالي الماركات</p>
            <p class="font-display text-3xl font-bold text-ink dark:text-cream">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">مرتبطة بمنتجات</p>
            <p class="font-display text-3xl font-bold text-forest-700 dark:text-gold-400">{{ $stats['with_products'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">فارغة</p>
            <p class="font-display text-3xl font-bold text-ink-muted dark:text-cream/60">{{ $stats['empty'] }}</p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 mb-6">
        <form method="GET" class="flex-1 max-w-md">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث باسم الماركة..."
                       class="form-input pl-10">
                <i class="fa-solid fa-magnifying-glass text-sm text-ink-muted dark:text-cream/40 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>
        </form>
        <a href="{{ route('admin.brands.create') }}" class="btn-solid whitespace-nowrap">
            <i class="fa-solid fa-plus"></i>
            إضافة ماركة
        </a>
    </div>

    @if($brands->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
            @foreach($brands as $brand)
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden hover:border-forest-500 dark:hover:border-gold-500 transition-colors group">

                    <div class="h-32 bg-stone-50 dark:bg-zinc-950 flex items-center justify-center border-b border-stone-200 dark:border-stone-800">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}"
                                 alt="{{ $brand->name }}"
                                 class="max-h-24 max-w-[80%] object-contain">
                        @else
                            <i class="fa-solid fa-tag text-5xl text-stone-300 dark:text-stone-700"></i>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3 class="font-display font-bold text-ink dark:text-cream mb-1 line-clamp-1 group-hover:text-forest-700 dark:group-hover:text-gold-400 transition-colors">
                            {{ $brand->name }}
                        </h3>

                        @if($brand->description)
                            <p class="text-xs text-ink-muted dark:text-cream/60 line-clamp-2 mb-3 min-h-[2rem]">
                                {{ $brand->description }}
                            </p>
                        @else
                            <p class="text-xs text-ink-faint dark:text-cream/30 mb-3 min-h-[2rem]">بدون وصف</p>
                        @endif

                        <div class="flex items-center justify-between pt-3 border-t border-stone-100 dark:border-stone-800">
                            <span class="badge badge-forest">{{ $brand->products_count }} منتج</span>

                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.brands.edit', $brand) }}"
                                   title="تعديل"
                                   class="w-8 h-8 flex items-center justify-center text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400 hover:bg-stone-100 dark:hover:bg-zinc-800 rounded transition-colors">
                                    <i class="fa-solid fa-pen text-sm"></i>
                                </a>
                                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST"
                                      onsubmit="return confirm('هل أنت متأكد من حذف الماركة؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            title="حذف"
                                            class="w-8 h-8 flex items-center justify-center text-ink-muted dark:text-cream/60 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 rounded transition-colors">
                                        <i class="fa-solid fa-trash text-sm"></i>
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
                <i class="fa-solid fa-tag text-4xl text-ink-muted dark:text-cream/40"></i>
            </div>
            <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد ماركات</h3>
            <p class="text-sm text-ink-muted dark:text-cream/60 mb-6">ابدأ بإضافة ماركات للمنصة</p>
            <a href="{{ route('admin.brands.create') }}" class="btn-solid inline-flex">
                <i class="fa-solid fa-plus"></i>
                إضافة ماركة
            </a>
        </div>
    @endif

@endsection