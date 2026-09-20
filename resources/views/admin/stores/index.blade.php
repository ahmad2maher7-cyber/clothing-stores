@extends('admin.layouts.app')

@section('title', 'المتاجر')
@section('page-title', 'إدارة المتاجر')

@section('content')

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">🏪 المتاجر</h2>
        <p class="text-gray-500 text-sm">إدارة والموافقة على متاجر المنصة</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <a href="{{ route('admin.stores.index') }}"
           class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md {{ !request('status') ? 'ring-2 ring-indigo-500' : '' }}">
            <p class="text-xs text-gray-500">الكل</p>
            <p class="text-2xl font-bold">{{ $stats['all'] }}</p>
        </a>
        <a href="{{ route('admin.stores.index', ['status' => 'active']) }}"
           class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md {{ request('status') == 'active' ? 'ring-2 ring-green-500' : '' }}">
            <p class="text-xs text-gray-500">✅ نشط</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </a>
        <a href="{{ route('admin.stores.index', ['status' => 'pending']) }}"
           class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md {{ request('status') == 'pending' ? 'ring-2 ring-yellow-500' : '' }}">
            <p class="text-xs text-gray-500">⏳ معلق</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </a>
        <a href="{{ route('admin.stores.index', ['status' => 'inactive']) }}"
           class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md {{ request('status') == 'inactive' ? 'ring-2 ring-red-500' : '' }}">
            <p class="text-xs text-gray-500">⛔ موقوف</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['inactive'] }}</p>
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 ابحث باسم المتجر..."
                   class="flex-1 border-gray-300 rounded-lg focus:border-indigo-500">
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 rounded-lg">بحث</button>
        </form>
    </div>

    {{-- Stores Grid --}}
    @if($stores->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($stores as $store)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                    @if($store->banner)
                        <img src="{{ asset('storage/' . $store->banner) }}" class="w-full h-32 object-cover">
                    @else
                        <div class="w-full h-32 bg-gradient-to-l from-indigo-400 to-purple-500"></div>
                    @endif

                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold">{{ $store->name }}</h3>
                            @if($store->status === 'active')
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded">✅</span>
                            @elseif($store->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded">⏳</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded">⛔</span>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 mb-3">
                            👤 {{ $store->merchant->full_name }}
                        </p>

                        <div class="flex gap-2 text-xs mb-3">
                            <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded">
                                👕 {{ $store->products_count }}
                            </span>
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                🛒 {{ $store->orders_count }}
                            </span>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.stores.show', $store) }}"
                               class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded text-sm">
                                👁️ عرض
                            </a>

                            @if($store->status === 'pending')
                                <form action="{{ route('admin.stores.approve', $store) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                        ✅
                                    </button>
                                </form>
                            @elseif($store->status === 'active')
                                <form action="{{ route('admin.stores.suspend', $store) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded text-sm">
                                        ⛔
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.stores.approve', $store) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                        ✅
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $stores->links() }}</div>
    @else
        <div class="bg-white rounded-lg shadow text-center py-16">
            <div class="text-6xl mb-4">🏪</div>
            <p class="text-gray-500">لا توجد متاجر</p>
        </div>
    @endif

@endsection
