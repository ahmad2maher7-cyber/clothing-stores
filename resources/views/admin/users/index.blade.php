@extends('admin.layouts.app')

@section('title', 'المستخدمون')
@section('page-title', 'إدارة المستخدمين')

@section('content')

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">👥 المستخدمون</h2>
        <p class="text-gray-500 text-sm">إدارة جميع مستخدمي المنصة</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md {{ !request('role') ? 'ring-2 ring-indigo-500' : '' }}">
            <p class="text-xs text-gray-500">الكل</p>
            <p class="text-2xl font-bold">{{ $stats['all'] }}</p>
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
           class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md {{ request('role') == 'admin' ? 'ring-2 ring-purple-500' : '' }}">
            <p class="text-xs text-gray-500">🛡️ مشرف</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['admins'] }}</p>
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'merchant']) }}"
           class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md {{ request('role') == 'merchant' ? 'ring-2 ring-blue-500' : '' }}">
            <p class="text-xs text-gray-500">🏪 تجار</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['merchants'] }}</p>
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'customer']) }}"
           class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md {{ request('role') == 'customer' ? 'ring-2 ring-green-500' : '' }}">
            <p class="text-xs text-gray-500">👤 زبائن</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['customers'] }}</p>
        </a>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <p class="text-xs text-gray-500">✅ نشط</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <p class="text-xs text-gray-500">⛔ معلق</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['suspended'] }}</p>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 اسم، بريد، أو هاتف..."
                   class="flex-1 border-gray-300 rounded-lg focus:border-indigo-500">
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 rounded-lg">بحث</button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">إعادة</a>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المستخدم</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">التواصل</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الدور</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">التسجيل</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                            {{ mb_substr($user->full_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ $user->full_name }}</p>
                                            <p class="text-xs text-gray-500">#{{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs">{{ $user->email }}</p>
                                    @if($user->phone)
                                        <p class="text-xs text-gray-500">{{ $user->phone }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @switch($user->role)
                                        @case('admin') <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded">🛡️ مشرف</span> @break
                                        @case('merchant') <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">🏪 تاجر</span> @break
                                        @default <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">👤 زبون</span>
                                    @endswitch
                                </td>
                                <td class="px-4 py-3">
                                    @if($user->status === 'active')
                                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded">✅ نشط</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded">⛔ معلق</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    {{ $user->created_at->format('Y/m/d') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                           class="text-indigo-600 hover:text-indigo-900 text-xs">👁️ عرض</a>

                                        @if($user->id !== auth()->id() && $user->role !== 'admin')
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <button type="submit" 
                                                        class="text-xs {{ $user->status === 'active' ? 'text-orange-600' : 'text-green-600' }}">
                                                    {{ $user->status === 'active' ? '⛔ تعليق' : '✅ تفعيل' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t bg-gray-50">{{ $users->links() }}</div>
        @else
            <div class="text-center py-16">
                <div class="text-6xl mb-4">👥</div>
                <p class="text-gray-500">لا يوجد مستخدمون مطابقون</p>
            </div>
        @endif
    </div>

@endsection