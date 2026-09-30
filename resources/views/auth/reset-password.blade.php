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
                <span class="eyebrow block mb-3">— كلمة مرور جديدة</span>
                <h1 class="font-display text-3xl font-bold text-ink dark:text-cream mb-3 tracking-tight">
                    إعادة التعيين
                </h1>
                <p class="text-sm text-ink-muted dark:text-cream/60 max-w-sm mx-auto">
                    أدخل كلمة المرور الجديدة لحسابك
                </p>
            </div>

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
            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email --}}
                <div>
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" 
                           required autofocus autocomplete="username" dir="ltr" readonly
                           class="form-input text-left bg-stone-50 dark:bg-zinc-800 cursor-not-allowed">
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="form-label">كلمة المرور الجديدة</label>
                    <input id="password" type="password" name="password" 
                           required autocomplete="new-password"
                           placeholder="••••••••"
                           class="form-input">
                </div>

                {{-- Confirm --}}
                <div>
                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" 
                           required autocomplete="new-password"
                           placeholder="••••••••"
                           class="form-input">
                </div>

                <button type="submit" class="btn-solid w-full">
                    إعادة تعيين كلمة المرور
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
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