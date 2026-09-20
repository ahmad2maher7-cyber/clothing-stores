@extends('merchant.layouts.app')

@section('title', 'قيد المراجعة')
@section('page-title', 'حالة المتجر')

@section('content')
    <div class="max-w-3xl mx-auto">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">

            {{-- Header --}}
            @if($store->banner)
                <img src="{{ asset('storage/' . $store->banner) }}" class="w-full h-40 object-cover">
            @else
                <div class="w-full h-40 bg-gradient-to-l from-yellow-400 to-orange-500"></div>
            @endif

            <div class="p-8 text-center">

                {{-- Logo --}}
                <div class="w-24 h-24 mx-auto -mt-20 mb-4 rounded-full bg-white shadow-lg flex items-center justify-center text-4xl border-4 border-white">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" class="w-full h-full object-cover rounded-full">
                    @else
                        🏪
                    @endif
                </div>

                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $store->name }}</h1>

                {{-- Status Badge --}}
                @if($store->status === 'pending')
                    <div class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full mb-4">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span>
                        <span class="font-medium">⏳ قيد المراجعة</span>
                    </div>
                @elseif($store->status === 'inactive')
                    <div class="inline-flex items-center gap-2 bg-red-100 text-red-800 px-4 py-2 rounded-full mb-4">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        <span class="font-medium">❌ متجر مرفوض</span>
                    </div>
                @endif

                <p class="text-gray-600 leading-relaxed mb-6">
                    @if($store->status === 'pending')
                        طلب إنشاء متجرك قيد المراجعة من قبل الإدارة.<br>
                        سيتم تفعيل متجرك خلال 24-48 ساعة.
                    @elseif($store->status === 'inactive')
                        للأسف، لم يتم اعتماد متجرك.<br>
                        للاستفسار، يرجى التواصل مع الدعم الفني.
                    @endif
                </p>

                {{-- Timeline --}}
                <div class="bg-gray-50 rounded-lg p-6 text-right mb-6">
                    <h3 class="font-bold text-gray-800 mb-4">📋 مراحل الإنشاء</h3>

                    <div class="space-y-4">
                        {{-- Step 1 --}}
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm">✓</div>
                                <div class="w-0.5 h-full bg-green-300 my-1"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="font-medium text-green-700">تم إنشاء الحساب</p>
                                <p class="text-xs text-gray-500">{{ $store->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                        </div>

                        {{-- Step 2 --}}
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                @if($store->status === 'pending')
                                    <div class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center text-sm animate-pulse">⏳</div>
                                @elseif($store->status === 'active')
                                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm">✓</div>
                                @else
                                    <div class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm">✕</div>
                                @endif
                                <div class="w-0.5 h-full bg-gray-300 my-1"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="font-medium {{ $store->status === 'pending' ? 'text-yellow-700' : ($store->status === 'active' ? 'text-green-700' : 'text-red-700') }}">
                                    مراجعة الإدارة
                                </p>
                                <p class="text-xs text-gray-500">
                                    @if($store->status === 'pending')
                                        جاري المراجعة...
                                    @elseif($store->status === 'active')
                                        تم الاعتماد
                                    @else
                                        تم الرفض
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Step 3 --}}
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                @if($store->status === 'active')
                                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm">✓</div>
                                @else
                                    <div class="w-8 h-8 bg-gray-300 text-white rounded-full flex items-center justify-center text-sm">🔓</div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-medium {{ $store->status === 'active' ? 'text-green-700' : 'text-gray-400' }}">
                                    تفعيل المتجر
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $store->status === 'active' ? 'متجرك جاهز للبيع' : 'في انتظار المراجعة' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-wrap gap-3 justify-center">
                    @if($store->status === 'active')
                        <a href="{{ route('merchant.dashboard') }}"
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium">
                            🚀 الذهاب للوحة التحكم
                        </a>
                    @else
                        <a href="{{ route('pages.contact') }}"
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium">
                            📞 تواصل مع الدعم
                        </a>
                        <a href="{{ route('home') }}"
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium">
                            🏠 الرئيسية
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection