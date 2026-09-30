<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">

            {{-- Icon --}}
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded-2xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <span class="eyebrow block mb-3">— منطقة آمنة</span>
                <h1 class="font-display text-3xl font-bold text-ink dark:text-cream mb-3 tracking-tight">
                    تأكيد كلمة المرور
                </h1>
                <p class="text-sm text-ink-muted dark:text-cream/60 max-w-sm mx-auto">
                    هذه منطقة محمية. الرجاء تأكيد كلمة المرور قبل المتابعة.
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

            {{-- Info --}}
            <div class="mb-6 flex items-start gap-3 p-4 bg-stone-50 dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded">
                <svg class="w-4 h-4 shrink-0 mt-0.5 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs leading-relaxed text-ink-muted dark:text-cream/60">
                    هذا الإجراء للحماية الإضافية. لن تحتاج لتكراره إلا عند تعديل بيانات حساسة.
                </p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="password" class="form-label">كلمة المرور</label>
                    <input id="password" type="password" name="password" 
                           required autofocus autocomplete="current-password"
                           placeholder="••••••••"
                           class="form-input">
                </div>

                <button type="submit" class="btn-solid w-full">
                    تأكيد كلمة المرور
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </button>
            </form>

            {{-- Back --}}
            <div class="mt-8 pt-6 border-t border-stone-200 dark:border-stone-800 text-center">
                <p class="text-sm text-ink-muted dark:text-cream/60">
                    <a href="{{ route('home') }}" class="font-semibold text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                        العودة للصفحة الرئيسية
                    </a>
                </p>
            </div>
        </div>
    </div>

</x-guest-layout>