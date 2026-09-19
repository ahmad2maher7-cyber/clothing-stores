@extends('merchant.layouts.app')

@section('title', 'مناطق الشحن')
@section('page-title', 'إدارة مناطق الشحن')

@section('content')

    {{-- Tabs --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="flex border-b overflow-x-auto">
            <a href="{{ route('merchant.settings.index') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">🏪 المعلومات الأساسية</a>
            <a href="{{ route('merchant.settings.branches') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">🏢 الفروع</a>
            <a href="{{ route('merchant.settings.shipping') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-indigo-600 text-indigo-600 font-medium">🚚 مناطق الشحن</a>
            <a href="{{ route('merchant.settings.policies') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">📜 السياسات</a>
        </div>
    </div>

    <div x-data="{ showForm: false, editZone: null }">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">مناطق الشحن</h2>
                <p class="text-gray-500 text-sm">إدارة المدن التي يتم التوصيل إليها ({{ $zones->count() }})</p>
            </div>
            <button @click="showForm = true; editZone = null"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse">
                <span>➕</span><span>إضافة منطقة</span>
            </button>
        </div>

        {{-- Zones Table --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($zones->count() > 0)
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الدولة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المدينة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">التكلفة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المدة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($zones as $zone)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $zone->country }}</td>
                                <td class="px-4 py-3 font-medium">{{ $zone->city }}</td>
                                <td class="px-4 py-3 font-bold text-indigo-600">{{ number_format($zone->cost, 2) }} ₪</td>
                                <td class="px-4 py-3">{{ $zone->estimated_days }} أيام</td>
                                <td class="px-4 py-3">
                                    @if($zone->is_active)
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">✅ نشط</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">⛔ معطل</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex space-x-2 space-x-reverse">
                                        <button @click="editZone = {{ $zone->toJson() }}; showForm = true"
                                                class="text-indigo-600 hover:text-indigo-900">✏️</button>
                                        <form action="{{ route('merchant.settings.shipping.destroy', $zone) }}"
                                              method="POST" onsubmit="return confirm('حذف المنطقة؟')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-16">
                    <div class="text-6xl mb-4">🚚</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مناطق شحن</h3>
                </div>
            @endif
        </div>

        {{-- Modal --}}
        <div x-show="showForm" x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div @click.outside="showForm = false"
                 class="bg-white rounded-lg shadow-xl w-full max-w-md">

                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="text-lg font-bold" x-text="editZone ? 'تعديل منطقة' : 'إضافة منطقة شحن'"></h3>
                    <button @click="showForm = false" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
                </div>

                <form :action="editZone 
                        ? `{{ url('merchant/settings/shipping') }}/${editZone.id}` 
                        : `{{ route('merchant.settings.shipping.store') }}`"
                      method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="editZone">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الدولة *</label>
                        <input type="text" name="country" :value="editZone?.country || 'فلسطين'" required
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المدينة *</label>
                        <input type="text" name="city" :value="editZone?.city || ''" required
                               placeholder="مثال: غزة"
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تكلفة الشحن (₪) *</label>
                            <input type="number" step="0.01" name="cost" :value="editZone?.cost || ''" required min="0"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">المدة (أيام) *</label>
                            <input type="number" name="estimated_days" :value="editZone?.estimated_days || 2" required min="1" max="60"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500">
                        </div>
                    </div>

                    <label class="flex items-center space-x-2 space-x-reverse">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" 
                               :checked="editZone?.is_active !== false"
                               class="rounded border-gray-300 text-indigo-600">
                        <span class="text-sm">✅ تفعيل الشحن لهذه المنطقة</span>
                    </label>

                    <div class="flex justify-end space-x-2 space-x-reverse pt-4 border-t">
                        <button type="button" @click="showForm = false"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg"
                                x-text="editZone ? '💾 حفظ' : '➕ إضافة'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection