<x-guest-layout>

    <div class="min-h-screen flex">

        {{-- ═══════════════════════════════════════
             SIDE VISUAL (Desktop)
        ═══════════════════════════════════════ --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-forest-900 via-forest-900 to-forest-950 overflow-hidden">

            {{-- Decorative --}}
            <div class="absolute top-20 right-20 w-96 h-96 bg-gold-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-20 w-96 h-96 bg-gold-500/10 rounded-full blur-3xl"></div>

            {{-- Content --}}
            <div class="relative z-10 flex flex-col justify-between p-12 xl:p-16 w-full">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <div class="w-12 h-12 flex items-center justify-center bg-gold-500 text-forest-950 rounded">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-display text-lg font-bold text-white">متجر الملابس</h1>
                        <p class="text-[10px] tracking-widest uppercase text-gold-400/80">أزياء عصرية</p>
                    </div>
                </a>

                {{-- Center Text --}}
                <div class="max-w-lg">
                    <span class="inline-block text-[10px] font-semibold tracking-widest uppercase text-gold-400 mb-4">
                        — مرحباً بعودتك
                    </span>
                    <h2 class="font-display text-4xl xl:text-5xl font-bold text-white leading-tight mb-6 text-balance">
                        تسوّق الأناقة
                        <br>
                        <span class="text-gold-400">بأبسط طريقة</span>
                    </h2>
                    <p class="text-base text-cream/60 leading-relaxed">
                        سجّل دخولك لمتابعة التسوق، تتبع طلباتك، وإدارة مفضلتك بسهولة.
                    </p>
                </div>

                {{-- Trust Indicators --}}
                <div class="flex flex-wrap gap-6 pt-8 border-t border-white/10">
                    @php
                        $trust = [
                            ['label' => 'توصيل سريع',     'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                            ['label' => 'دفع آمن',         'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                            ['label' => 'إرجاع مجاني',     'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                        ];
                    @endphp
                    @foreach($trust as $item)
                        <div class="flex items-center gap-2 text-sm text-cream/60">
                            <svg class="w-4 h-4 text-gold-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                            </svg>
                            {{ $item['label'] }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════
             LOGIN FORM
        ═══════════════════════════════════════ --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-10 lg:p-12">

            <div class="w-full max-w-md">

                {{-- Mobile Logo --}}
                <div class="lg:hidden text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-forest-900 dark:bg-gold-500 text-white dark:text-ink rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <span class="font-display font-bold text-ink dark:text-cream">متجر الملابس</span>
                    </a>
                </div>

                {{-- Header --}}
                <div class="mb-8">
                    <span class="eyebrow block mb-3">— تسجيل الدخول</span>
                    <h1 class="font-display text-3xl lg:text-4xl font-bold text-ink dark:text-cream mb-3 tracking-tight text-balance">
                        مرحباً بعودتك
                    </h1>
                    <p class="text-sm text-ink-muted dark:text-cream/60">
                        سجّل دخولك لمتابعة التسوق والوصول إلى طلباتك.
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
                        <p class="font-bold mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            تعذر تسجيل الدخول
                        </p>
                        <ul class="space-y-1 pr-6">
                            @foreach ($errors->all() as $error)
                                <li class="text-xs leading-relaxed">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" 
                               required autofocus autocomplete="username"
                               placeholder="you@example.com" dir="ltr"
                               class="form-input text-left">
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label for="password" class="form-label mb-0">كلمة المرور</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   class="text-xs font-semibold text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                                    نسيت كلمة المرور؟
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" 
                               required autocomplete="current-password"
                               placeholder="••••••••"
                               class="form-input">
                    </div>

                    {{-- Remember --}}
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <input type="checkbox" name="remember"
                               class="w-4 h-4 text-forest-700 focus:ring-forest-500 border-stone-300 dark:border-stone-600 rounded">
                        <span class="text-sm text-ink-muted dark:text-cream/60 group-hover:text-ink dark:group-hover:text-cream transition-colors">
                            تذكرني في المرة القادمة
                        </span>
                    </label>

                    {{-- Submit --}}
                    <button type="submit" class="btn-solid w-full">
                        تسجيل الدخول
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
                        </svg>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative my-7">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-stone-200 dark:border-stone-800"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-4 bg-canvas dark:bg-zinc-950 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/40">
                            ليس لديك حساب؟
                        </span>
                    </div>
                </div>

                {{-- Register Link --}}
                <a href="{{ route('register') }}" class="btn-outline w-full">
                    إنشاء حساب جديد
                </a>

                
            </div>
        </div>
    </div>

</x-guest-layout>