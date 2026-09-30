@extends('merchant.layouts.app')

@section('title', 'مناطق الشحن')
@section('page-title', 'إدارة مناطق الشحن')

@section('content')

    {{-- ═══ Tabs ═══ --}}
    @include('merchant.settings.partials.tabs', ['activeTab' => 'shipping'])

    <div x-data="{ showForm: false, editZone: null }">

        {{-- ═══ Header ═══ --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">مناطق الشحن</h2>
                <p class="text-sm text-ink-muted dark:text-cream/60">إدارة المدن التي يتم التوصيل إليها ({{ $zones->count() }})</p>
            </div>
            <button @click="showForm = true; editZone = null" class="btn-solid whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                إضافة منطقة
            </button>
        </div>

        {{-- ═══ Zones Table ═══ --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
            @if($zones->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                            <tr>
                                <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الدولة</th>
                                <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المدينة</th>
                                <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">التكلفة</th>
                                <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المدة</th>
                                <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الحالة</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                            @foreach($zones as $zone)
                                <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <td class="px-4 py-3 text-ink-soft dark:text-cream/70">{{ $zone->country }}</td>
                                    <td class="px-4 py-3 font-medium text-ink dark:text-cream">{{ $zone->city }}</td>
                                    <td class="px-4 py-3">
                                        <span class="font-display font-bold text-forest-700 dark:text-gold-400">
                                            {{ number_format($zone->cost, 2) }} ₪
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-ink-soft dark:text-cream/70">{{ $zone->estimated_days }} أيام</td>
                                    <td class="px-4 py-3">
                                        @if($zone->is_active)
                                            <span class="badge badge-forest">نشط</span>
                                        @else
                                            <span class="badge badge-stone">معطل</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <button @click="editZone = {{ $zone->toJson() }}; showForm = true"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-forest-700 dark:text-gold-400 hover:bg-forest-50 dark:hover:bg-forest-950/40 rounded transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <form action="{{ route('merchant.settings.shipping.destroy', $zone) }}"
                                                  method="POST" onsubmit="return confirm('حذف المنطقة؟')" class="inline">
                                                @csrf @method('DELETE')
                                                <button class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 rounded transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                {{-- ═══ Empty State ═══ --}}
                <div class="text-center py-20">
                    <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                        <svg class="w-10 h-10 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد مناطق شحن</h3>
                    <p class="text-sm text-ink-muted dark:text-cream/60 mb-6">أضف أول منطقة شحن لعملائك</p>
                    <button @click="showForm = true; editZone = null" class="btn-solid inline-flex">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        إضافة منطقة
                    </button>
                </div>
            @endif
        </div>

        {{-- ═══ Modal ═══ --}}
        <div x-show="showForm" x-cloak
             class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div @click.outside="showForm = false"
                 x-transition
                 class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg shadow-xl w-full max-w-md">

                <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex justify-between items-center">
                    <h3 class="font-display font-bold text-ink dark:text-cream" 
                        x-text="editZone ? 'تعديل منطقة' : 'إضافة منطقة شحن'"></h3>
                    <button @click="showForm = false" 
                            class="w-8 h-8 flex items-center justify-center text-ink-muted dark:text-cream/40 hover:text-ink dark:hover:text-cream rounded transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form :action="editZone 
                        ? `{{ url('merchant/settings/shipping') }}/${editZone.id}` 
                        : `{{ route('merchant.settings.shipping.store') }}`"
                      method="POST" class="p-5 space-y-4">
                    @csrf
                    <template x-if="editZone">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="form-label">الدولة <span class="text-red-500">*</span></label>
                        <input type="text" name="country" :value="editZone?.country || 'فلسطين'" required
                               class="form-input">
                    </div>

                    <div>
                        <label class="form-label">المدينة <span class="text-red-500">*</span></label>
                        <input type="text" name="city" :value="editZone?.city || ''" required
                               placeholder="مثال: غزة"
                               class="form-input">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">تكلفة الشحن (₪) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="cost" :value="editZone?.cost || ''" required min="0"
                                   class="form-input">
                        </div>
                        <div>
                            <label class="form-label">المدة (أيام) <span class="text-red-500">*</span></label>
                            <input type="number" name="estimated_days" :value="editZone?.estimated_days || 2" required min="1" max="60"
                                   class="form-input">
                        </div>
                    </div>

                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" 
                               :checked="editZone?.is_active !== false"
                               class="w-4 h-4 text-forest-700 focus:ring-forest-500 border-stone-300 dark:border-stone-600 rounded">
                        <span class="text-sm text-ink dark:text-cream">✅ تفعيل الشحن لهذه المنطقة</span>
                    </label>

                    <div class="flex justify-end gap-3 pt-4 border-t border-stone-200 dark:border-stone-800">
                        <button type="button" @click="showForm = false" class="btn-outline">
                            إلغاء
                        </button>
                        <button type="submit" class="btn-solid"
                                x-text="editZone ? 'حفظ التعديلات' : 'إضافة المنطقة'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection