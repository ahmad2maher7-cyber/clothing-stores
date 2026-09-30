@extends('layouts.public')

@section('title', 'مرحباً')

@section('content')

    <div class="container-x py-20 lg:py-28">
        <div class="max-w-2xl mx-auto text-center">

            {{-- Icon --}}
            <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded-2xl">
                <i class="fas fa-tshirt text-4xl"></i>
            </div>

            <span class="eyebrow block mb-3">— مرحباً بك</span>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-ink dark:text-cream mb-4">
                متجر الملابس
            </h1>
            <p class="text-base text-ink-muted dark:text-cream/60 mb-8 max-w-md mx-auto">
                تسوّق الأناقة بأبسط طريقة
            </p>

            <a href="{{ route('products.index') }}" class="btn-solid">
                <i class="fas fa-shopping-bag"></i>
                تسوق الآن
                <i class="fas fa-chevron-left"></i>
            </a>
        </div>
    </div>

@endsection