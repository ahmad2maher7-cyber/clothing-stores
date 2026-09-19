@extends('merchant.layouts.app')

@section('title', 'فروع المتجر')
@section('page-title', 'إدارة الفروع')

@section('content')

    {{-- Tabs --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="flex border-b overflow-x-auto">
            <a href="{{ route('merchant.settings.index') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">🏪 المعلومات الأساسية</a>
            <a href="{{ route('merchant.settings.branches') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-indigo-600 text-indigo-600 font-medium">🏢 الفروع</a>
            <a href="{{ route('merchant.settings.shipping') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">🚚 مناطق الشحن</a>
            <a href="{{ route('merchant.settings.policies') }}" class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-indigo-600">📜 السياسات</a>
        </div>
    </div>

    <div x-data="{ showForm: false, editBranch: null }">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">الفروع</h2>
                <p class="text-gray-500 text-sm">إدارة فروع متجرك ({{ $branches->count() }})</p>
            </div>
            <button @click="showForm = true; editBranch = null"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 space-x-reverse">
                <span>➕</span><span>إضافة فرع</span>
            </button>
        </div>

        {{-- Branches Grid --}}
        @if($branches->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($branches as $branch)
                    <div class="bg-white rounded-lg shadow p-5 hover:shadow-lg transition">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-bold text-gray-800">{{ $branch->name }}</h3>
                            @if($branch->is_main)
                                <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded">⭐ رئيسي</span>
                            @endif
                        </div>

                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p>📍 {{ $branch->address }}</p>
                            @if($branch->phone)
                                <p>📞 {{ $branch->phone }}</p>
                            @endif
                            @if($branch->latitude && $branch->longitude)
                                <p class="text-xs text-gray-400">
                                    🌐 {{ $branch->latitude }}, {{ $branch->longitude }}
                                </p>
                            @endif
                        </div>

                        <div class="flex space-x-2 space-x-reverse pt-3 border-t">
                            <button @click="editBranch = {{ $branch->toJson() }}; showForm = true"
                                    class="flex-1 text-indigo-600 hover:bg-indigo-50 text-sm py-1.5 rounded">
                                ✏️ تعديل
                            </button>
                            <form action="{{ route('merchant.settings.branches.destroy', $branch) }}"
                                  method="POST" onsubmit="return confirm('حذف الفرع؟')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:bg-red-50 text-sm px-3 py-1.5 rounded">🗑️</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow text-center py-16">
                <div class="text-6xl mb-4">🏢</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد فروع</h3>
                <p class="text-gray-500 mb-4">ابدأ بإضافة أول فرع لمتجرك</p>
            </div>
        @endif

        {{-- Modal Form --}}
        <div x-show="showForm" x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div @click.outside="showForm = false"
                 class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">

                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="text-lg font-bold" x-text="editBranch ? 'تعديل فرع' : 'إضافة فرع جديد'"></h3>
                    <button @click="showForm = false" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
                </div>

                <form :action="editBranch 
                        ? `{{ url('merchant/settings/branches') }}/${editBranch.id}` 
                        : `{{ route('merchant.settings.branches.store') }}`"
                      method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="editBranch">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم الفرع *</label>
                        <input type="text" name="name" :value="editBranch?.name || ''" required
                               placeholder="مثال: فرع الرمال"
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">العنوان *</label>
                        <textarea name="address" rows="2" required
                                  :value="editBranch?.address || ''"
                                  placeholder="المدينة - الحي - الشارع"
                                  class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                        <input type="text" name="phone" :value="editBranch?.phone || ''"
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">خط العرض</label>
                            <input type="number" step="0.0000001" name="latitude" :value="editBranch?.latitude || ''"
                                   class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">خط الطول</label>
                            <input type="number" step="0.0000001" name="longitude" :value="editBranch?.longitude || ''"
                                   class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500">
                        </div>
                    </div>

                    <label class="flex items-center space-x-2 space-x-reverse">
                        <input type="hidden" name="is_main" value="0">
                        <input type="checkbox" name="is_main" value="1" 
                               :checked="editBranch?.is_main"
                               class="rounded border-gray-300 text-indigo-600">
                        <span class="text-sm">⭐ تعيين كفرع رئيسي</span>
                    </label>

                    <div class="flex justify-end space-x-2 space-x-reverse pt-4 border-t">
                        <button type="button" @click="showForm = false"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg"
                                x-text="editBranch ? '💾 حفظ' : '➕ إضافة'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection