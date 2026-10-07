@extends('merchant.layouts.app')

@section('title', 'التصنيفات')
@section('page-title', 'التصنيفات')

@section('content')

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">التصنيفات</h2>
            <p class="text-sm text-ink-muted dark:text-cream/60">{{ $categories->total() }} تصنيف</p>
        </div>
        <a href="{{ route('merchant.categories.create') }}" class="btn-solid whitespace-nowrap">
            <i class="fa-solid fa-plus"></i>
            إضافة تصنيف
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث باسم التصنيف..."
                       class="form-input pl-10">
                <i class="fa-solid fa-magnifying-glass text-sm text-ink-muted dark:text-cream/40 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>

            <select name="status" class="form-input">
                <option value="">كل الحالات</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ نشط</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>⛔ معطل</option>
            </select>

            <button type="submit" class="btn-solid">
                <i class="fa-solid fa-magnifying-glass"></i>
                بحث
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
        @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الصورة</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الاسم</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الأب</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المنتجات</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الحالة</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($categories as $category)
                            <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-4 py-3">
                                    @if($category->image)
                                        <img src="{{ $category->image_url }}"
                                             alt="{{ $category->name }}"
                                             class="w-10 h-10 rounded object-cover border border-stone-200 dark:border-stone-800">
                                    @else
                                        <div class="w-10 h-10 rounded bg-stone-100 dark:bg-zinc-800 flex items-center justify-center">
                                            <i class="fa-solid fa-folder text-ink-muted dark:text-cream/40"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-ink dark:text-cream">{{ $category->name }}</p>
                                </td>
                                <td class="px-4 py-3 text-ink-muted dark:text-cream/60">
                                    {{ $category->parent?->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge badge-stone">{{ $category->products_count }} منتج</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($category->status === 'active')
                                        <span class="badge badge-forest">نشط</span>
                                    @else
                                        <span class="badge badge-stone">معطل</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('merchant.categories.edit', $category) }}"
                                           class="inline-flex items-center gap-1 text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                            تعديل
                                        </a>
                                        <form action="{{ route('merchant.categories.destroy', $category) }}"
                                              method="POST" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف التصنيف؟')"
                                              class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 text-xs font-medium text-red-600 dark:text-red-400 hover:opacity-70 transition-opacity">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-stone-200 dark:border-stone-800">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <i class="fa-solid fa-folder-tree text-4xl text-ink-muted dark:text-cream/40"></i>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد تصنيفات</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60 mb-6">ابدأ بإضافة أول تصنيف</p>
                <a href="{{ route('merchant.categories.create') }}" class="btn-solid inline-flex">
                    <i class="fa-solid fa-plus"></i>
                    إضافة تصنيف
                </a>
            </div>
        @endif
    </div>

@endsection