@extends('customer.layouts.app')

@section('title', 'الملف الشخصي')

@section('content')

    <div class="max-w-4xl mx-auto px-4 py-8">

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-1">👤 الملف الشخصي</h1>
            <p class="text-gray-500">إدارة معلوماتك الشخصية وكلمة المرور</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-2xl font-bold text-indigo-600">{{ $stats['orders'] }}</p>
                <p class="text-xs text-gray-500">طلب</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-2xl font-bold text-red-600">{{ $stats['wishlist'] }}</p>
                <p class="text-xs text-gray-500">مفضلة</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['reviews'] }}</p>
                <p class="text-xs text-gray-500">تقييم</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_spent'], 0) }}</p>
                <p class="text-xs text-gray-500">₪ مشتريات</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Avatar --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}"
                             class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-indigo-100 mb-4">
                    @else
                        <div class="w-32 h-32 rounded-full mx-auto bg-indigo-100 flex items-center justify-center text-5xl font-bold text-indigo-600 mb-4">
                            {{ mb_substr($user->full_name, 0, 1) }}
                        </div>
                    @endif

                    <h2 class="font-bold text-lg">{{ $user->full_name }}</h2>
                    <p class="text-sm text-gray-500 mb-4">{{ $user->email }}</p>

                    <div class="text-xs text-gray-400">
                        عضو منذ {{ $user->created_at->format('Y/m/Y') }}
                    </div>
                </div>
            </div>

            {{-- Forms --}}
            <div class="md:col-span-2 space-y-6">

                {{-- Basic Info --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-5 border-b">
                        <h3 class="font-bold">📝 المعلومات الأساسية</h3>
                    </div>
                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل *</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            @error('full_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">لا يمكن تغيير البريد الإلكتروني</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الصورة الشخصية</label>
                            <input type="file" name="avatar" accept="image/*"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG — أقل من 2MB</p>
                            @error('avatar') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                                💾 حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Password --}}
                <div class="bg-white rounded-lg shadow">
                    <div class="p-5 border-b">
                        <h3 class="font-bold">🔒 تغيير كلمة المرور</h3>
                    </div>
                    <form action="{{ route('customer.profile.password') }}" method="POST" class="p-5 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الحالية *</label>
                            <input type="password" name="current_password" required
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            @error('current_password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة *</label>
                            <input type="password" name="password" required
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور *</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">
                                🔒 تغيير كلمة المرور
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection