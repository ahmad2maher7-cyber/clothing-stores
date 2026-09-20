@extends('layouts.public')

@section('title', 'تواصل معنا')

@section('content')

    <section class="bg-gradient-to-l from-indigo-700 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 py-16 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">تواصل معنا 📞</h1>
            <p class="text-lg text-indigo-100">نحن هنا لمساعدتك، تواصل معنا بأي طريقة تفضلها</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-16">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Contact Info --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-2xl mb-3">📍</div>
                    <h3 class="font-bold mb-1">العنوان</h3>
                    <p class="text-gray-600 text-sm">فلسطين - غزة - شارع عمر المختار</p>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-2xl mb-3">📞</div>
                    <h3 class="font-bold mb-1">الهاتف</h3>
                    <p class="text-gray-600 text-sm" dir="ltr">+970 599 000 000</p>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-2xl mb-3">✉️</div>
                    <h3 class="font-bold mb-1">البريد الإلكتروني</h3>
                    <p class="text-gray-600 text-sm">info@clothing-store.com</p>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-2xl mb-3">⏰</div>
                    <h3 class="font-bold mb-1">أوقات العمل</h3>
                    <p class="text-gray-600 text-sm">السبت - الخميس: 9ص - 10م</p>
                    <p class="text-gray-600 text-sm">الجمعة: 2م - 10م</p>
                </div>

                {{-- Social --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-bold mb-3">تابعنا</h3>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:opacity-90">f</a>
                        <a href="#" class="w-10 h-10 bg-pink-600 text-white rounded-lg flex items-center justify-center hover:opacity-90">📷</a>
                        <a href="#" class="w-10 h-10 bg-sky-500 text-white rounded-lg flex items-center justify-center hover:opacity-90">🐦</a>
                        <a href="#" class="w-10 h-10 bg-green-600 text-white rounded-lg flex items-center justify-center hover:opacity-90">💬</a>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow p-8">
                    <h2 class="text-2xl font-bold mb-6">أرسل لنا رسالة ✉️</h2>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            ✅ {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('pages.contact.send') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الاسم *</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()?->full_name) }}" required
                                       class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني *</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                                       class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الموضوع *</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الرسالة *</label>
                            <textarea name="message" rows="6" required maxlength="2000"
                                      class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
                            @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-bold transition">
                            📤 إرسال الرسالة
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection