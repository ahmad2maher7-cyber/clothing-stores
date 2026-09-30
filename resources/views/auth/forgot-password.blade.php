<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">

            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <div class="w-12 h-12 flex items-center justify-center bg-forest-900 dark:bg-gold-500 text-white dark:text-ink rounded">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </a>
            </div>

            {{-- Header --}}
            <div class="text-center mb-8">
                <span class="eyebrow block mb-3">— نسيت كلمة المرور؟</span>
                <h1 class="font-display text-3xl font-bold text-ink dark:text-cream mb-3 tracking-tight">
                    استعادة الحساب
                </h1>
                <p class="text-sm text-ink-muted dark:text-cream/60 max-w-sm mx-auto">
                    أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور
                </p>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-6 px-5 py-4 text-sm bg-forest-50 dark:bg-forest-950/30 border border-forest-200 dark:border-forest-900/50 text-forest-800 dark:text-forest-300 rounded flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Errors --}}
            @if ($errors->any())
                <div class="mb-6 px-5 py-4 text-sm bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-300 rounded">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs leading-relaxed">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" 
                           required autofocus autocomplete="username"
                           placeholder="you@example.com" dir="ltr"
                           class="form-input text-left">
                </div>

                <button type="submit" class="btn-solid w-full">
                    إرسال رابط الاستعادة
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </button>
            </form>

            {{-- Back --}}
            <div class="mt-8 pt-6 border-t border-stone-200 dark:border-stone-800 text-center">
                <p class="text-sm text-ink-muted dark:text-cream/60">
                    تذكرت كلمة المرور؟
                    <a href="{{ route('login') }}" class="font-semibold text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity mr-1">
                        العودة لتسجيل الدخول
                    </a>
                </p>
            </div>
        </div>
    </div>

</x-guest-layout>