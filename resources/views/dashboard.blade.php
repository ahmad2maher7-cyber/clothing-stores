@extends('layouts.public')

@section('title', 'لوحة التحكم')

@section('content')

    <div class="container-x py-20 lg:py-28">
        <div class="max-w-2xl mx-auto text-center">

            {{-- Icon --}}
            <div class="w-24 h-24 mx-auto mb-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded-3xl">
                <i class="fas fa-shield-alt text-5xl"></i>
            </div>

            {{-- Header --}}
            <span class="eyebrow block mb-3">— مرحباً</span>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-ink dark:text-cream mb-4 text-balance">
                تم تسجيل دخولك بنجاح
            </h1>
            <p class="text-base text-ink-muted dark:text-cream/60 mb-10 max-w-md mx-auto text-pretty">
                مرحباً {{ auth()->user()->full_name }}! جاهز للاستمرار؟
            </p>

            {{-- Actions --}}
            <div class="flex flex-wrap gap-3 justify-center">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn-solid">
                        <i class="fas fa-shield-alt"></i>
                        لوحة المشرف
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @elseif(auth()->user()->role === 'merchant')
                    <a href="{{ route('merchant.dashboard') }}" class="btn-solid">
                        <i class="fas fa-store"></i>
                        لوحة التاجر
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="btn-solid">
                        <i class="fas fa-user"></i>
                        حسابي
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif
                <a href="{{ route('home') }}" class="btn-outline">
                    <i class="fas fa-home"></i>
                    الرئيسية
                </a>
            </div>
        </div>
    </div>

@endsection