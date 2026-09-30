<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">

            {{-- Icon --}}
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded-2xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>

                <span class="eyebrow block mb-3">— تفعيل الحساب</span>
                <h1 class="font-display text-3xl font-bold text-ink dark:text-cream mb-3 tracking-tight">
                    تحقّق من بريدك
                </h1>
                <p class="text-sm text-ink-muted dark:text-cream/60 max-w-sm mx-auto leading-relaxed">
                    شكراً لتسجيلك. قبل البدء، يرجى تأكيد بريدك الإلكتروني من خلال الرابط الذي أرسلناه لك.
                </p>
            </div>

            {{-- Status --}}
            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 px-5 py-4 text-sm bg-forest-50 dark:bg-forest-950/30 border border-forest-200 dark:border-forest-900/50 text-forest-800 dark:text-forest-300 rounded flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.</span>
                </div>
            @endif

            {{-- Info --}}
            <div class="mb-6 flex items-start gap-3 p-4 bg-stone-50 dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded">
                <svg class="w-4 h-4 shrink-0 mt-0.5 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs leading-relaxed text-ink-muted dark:text-cream/60">
                    تحقق من مجلد <span class="font-semibold text-ink dark:text-cream">البريد العشوائي (Spam)</span> إذا لم يظهر البريد في صندوق الوارد خلال دقائق.
                </p>
            </div>

            {{-- Resend --}}
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-solid w-full">
                    إعادة إرسال رابط التحقق
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </button>
            </form>

            {{-- Logout --}}
            <div class="mt-8 pt-6 border-t border-stone-200 dark:border-stone-800 text-center">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/40 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        استخدام حساب آخر
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-guest-layout>