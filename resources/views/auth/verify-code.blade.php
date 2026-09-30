<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">

            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded-2xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>

                <span class="eyebrow block mb-3">— تفعيل الحساب</span>
                <h1 class="font-display text-3xl font-bold text-ink dark:text-cream mb-3">
                    تحقّق من بريدك
                </h1>
                <p class="text-sm text-ink-muted dark:text-cream/60 max-w-sm mx-auto leading-relaxed">
                    أرسلنا كود تحقق مكوّن من <strong>6 أرقام</strong> إلى:
                    <br>
                    <strong class="text-ink dark:text-cream">{{ auth()->user()->email }}</strong>
                </p>
            </div>

            {{-- Dev Code --}}
            @if(session('dev_code'))
                <div class="mb-6 px-5 py-4 text-sm bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900/50 text-blue-800 dark:text-blue-300 rounded">
                    <p class="font-bold mb-1">🔧 وضع التطوير</p>
                    <p class="text-xs mb-2">الكود:</p>
                    <p class="font-mono text-3xl font-bold tracking-widest text-blue-900 dark:text-blue-200" dir="ltr">
                        {{ session('dev_code') }}
                    </p>
                </div>
            @endif

            {{-- Errors --}}
            @if ($errors->any())
                <div class="mb-6 px-5 py-4 text-sm bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-300 rounded">
                    <p class="font-bold mb-2">❌ خطأ</p>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 px-5 py-4 text-sm bg-forest-50 dark:bg-forest-950/30 border border-forest-200 dark:border-forest-900/50 text-forest-800 dark:text-forest-300 rounded">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Form - SIMPLE INPUT --}}
            <form method="POST" action="{{ route('verification.verify') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="form-label text-center block mb-3">أدخل كود التحقق (6 أرقام)</label>
                    <input type="text"
                           name="code"
                           id="otp-code"
                           maxlength="6"
                           inputmode="numeric"
                           pattern="[0-9]{6}"
                           required
                           autofocus
                           autocomplete="off"
                           placeholder="123456"
                           value="{{ old('code') }}"
                           dir="ltr"
                           class="w-full h-20 text-center text-4xl font-bold tracking-[1rem] bg-white dark:bg-zinc-900 border-2 border-stone-300 dark:border-stone-700 rounded-lg focus:border-forest-600 dark:focus:border-gold-400 focus:ring-0 transition-colors text-ink dark:text-cream pl-8">
                </div>

                <button type="submit" class="btn-solid w-full">
                    تأكيد الكود
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                    </svg>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-stone-200 dark:border-stone-800 text-center">
                <p class="text-sm text-ink-muted dark:text-cream/60 mb-3">لم تستلم الكود؟</p>
                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                        🔄 إعادة إرسال الكود
                    </button>
                </form>
            </div>

            <div class="mt-6 text-center">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-ink-muted dark:text-cream/40 hover:text-red-600 transition-colors">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-guest-layout>