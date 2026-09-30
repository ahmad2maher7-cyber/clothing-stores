<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — خطأ في السيرفر</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Playfair+Display:wght@700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>

<body class="bg-canvas dark:bg-zinc-950 min-h-screen flex items-center justify-center p-4">

    <div class="text-center max-w-lg">

        {{-- ═══ Big 500 ═══ --}}
        <div class="font-display text-[180px] md:text-[220px] leading-none font-black text-red-100 dark:text-red-950/40 select-none mb-4">
            500
        </div>

        {{-- ═══ Icon ═══ --}}
        <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-red-50 dark:bg-red-950/30 rounded-full">
            <svg class="w-10 h-10 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        {{-- ═══ Title ═══ --}}
        <span class="eyebrow block mb-3">— خطأ 500</span>
        <h1 class="font-display text-3xl md:text-4xl font-bold text-ink dark:text-cream mb-4 tracking-tight">
            حدث خطأ غير متوقع
        </h1>
        <p class="text-base text-ink-muted dark:text-cream/60 mb-10 max-w-md mx-auto leading-relaxed">
            نعتذر عن الإزعاج، حدث خطأ في السيرفر. فريقنا التقني يعمل على حل المشكلة. يرجى المحاولة لاحقاً.
        </p>

        {{-- ═══ Actions ═══ --}}
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="{{ url('/') }}" class="btn-solid group">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                الرئيسية
            </a>
            <button onclick="location.reload()" class="btn-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                إعادة المحاولة
            </button>
        </div>

        {{-- ═══ Support ═══ --}}
        <div class="mt-10 pt-6 border-t border-stone-200 dark:border-stone-800">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/40 mb-3">
                هل تحتاج مساعدة؟
            </p>
            <a href="{{ url('/contact') }}" 
               class="inline-flex items-center gap-2 text-sm font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                تواصل مع فريق الدعم
            </a>
        </div>
    </div>

</body>

</html>