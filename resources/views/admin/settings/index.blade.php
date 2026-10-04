@extends('admin.layouts.app')

@section('title', 'الإعدادات')
@section('page-title', 'إعدادات الحساب')

@section('content')

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">⚙️ الإعدادات</h2>
        <p class="text-gray-500 text-sm">إدارة حسابك وإعداداتك الشخصية</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- بطاقة المستخدم --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow p-6 text-center">
                <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gray-900 text-white flex items-center justify-center text-4xl font-bold overflow-hidden">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                    @else
                        {{ mb_substr($user->full_name, 0, 1) }}
                    @endif
                </div>
                <h3 class="text-lg font-bold">{{ $user->full_name }}</h3>
                <p class="text-sm text-gray-500 mb-3">{{ $user->email }}</p>
                <span class="inline-block bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-full">
                    🛡️ مشرف عام
                </span>

                <div class="mt-6 pt-6 border-t text-sm space-y-2 text-right">
                    <div class="flex justify-between">
                        <span class="text-gray-500">رقم الهاتف:</span>
                        <span>{{ $user->phone ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">تاريخ التسجيل:</span>
                        <span>{{ $user->created_at->format('Y/m/d') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">آخر تحديث:</span>
                        <span>{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- النماذج --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- نموذج الملف الشخصي --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold mb-4">👤 الملف الشخصي</h3>

                <form action="{{ route('admin.settings.profile') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل *</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الصورة الشخصية</label>
                        <input type="file" name="avatar" accept="image/*"
                               class="w-full border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG — أقل من 2MB</p>
                        @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-lg font-medium transition">
                            💾 حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>

            {{-- نموذج كلمة المرور --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold mb-4">🔐 تغيير كلمة المرور</h3>

                <form action="{{ route('admin.settings.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الحالية *</label>
                        <input type="password" name="current_password" required
                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة *</label>
                            <input type="password" name="password" required
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور *</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                        💡 كلمة المرور يجب أن تكون 8 أحرف على الأقل
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-lg font-medium transition">
                            🔐 تغيير كلمة المرور
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection