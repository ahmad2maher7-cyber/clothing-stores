<x-guest-layout>

    <div class="min-h-screen flex">

        {{-- ═══════════════════════════════════════
             SIDE VISUAL (Desktop)
        ═══════════════════════════════════════ --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-forest-900 via-forest-900 to-forest-950 overflow-hidden">

            <div class="absolute top-20 right-20 w-96 h-96 bg-gold-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-20 w-96 h-96 bg-gold-500/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col justify-between p-12 xl:p-16 w-full">

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

                <div class="max-w-lg">
                    <span class="inline-block text-[10px] font-semibold tracking-widest uppercase text-gold-400 mb-4">
                        — انضم إلينا
                    </span>
                    <h2 class="font-display text-4xl xl:text-5xl font-bold text-white leading-tight mb-6 text-balance">
                        ابدأ رحلتك
                        <br>
                        <span class="text-gold-400">معنا اليوم</span>
                    </h2>
                    <p class="text-base text-cream/60 leading-relaxed">
                        أنشئ حسابك في دقيقة واحدة واستمتع بتجربة تسوق فريدة مع أفضل المتاجر والمنتجات.
                    </p>

                    {{-- Features --}}
                    <div class="mt-10 space-y-4">
                        @php
                            $features = [
                                'تصفح آلاف المنتجات من متاجر موثوقة',
                                'تتبع طلباتك بسهولة من حسابك',
                                'احفظ منتجاتك المفضلة للرجوع إليها',
                                'استفد من العروض والخصومات الحصرية',
                            ];
                        @endphp
                        @foreach($features as $feature)
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 flex items-center justify-center bg-gold-500/20 rounded-full shrink-0">
                                    <svg class="w-3.5 h-3.5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-cream/80">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <p class="text-xs text-cream/40">
                    © {{ date('Y') }} متجر الملابس — جميع الحقوق محفوظة
                </p>
            </div>
        </div>

        {{-- ═══════════════════════════════════════
             REGISTER FORM
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
                    <span class="eyebrow block mb-3">— حساب جديد</span>
                    <h1 class="font-display text-3xl lg:text-4xl font-bold text-ink dark:text-cream mb-3 tracking-tight">
                        إنشاء حسابك
                    </h1>
                    <p class="text-sm text-ink-muted dark:text-cream/60">
                        انضم إلينا واستمتع بتجربة تسوق سريعة وسهلة.
                    </p>
                </div>

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="mb-6 px-5 py-4 text-sm bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-300 rounded">
                        <p class="font-bold mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            تعذر إنشاء الحساب
                        </p>
                        <ul class="space-y-1 pr-6">
                            @foreach ($errors->all() as $error)
                                <li class="text-xs leading-relaxed">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ role: '{{ old('role', 'customer') }}' }">
                    @csrf

                    {{-- Role --}}
                    <div>
                        <label class="form-label">نوع الحساب</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="customer" x-model="role" class="sr-only">
                                <div class="border-2 rounded p-4 text-center transition-all"
                                     :class="role === 'customer' 
                                            ? 'border-forest-700 bg-forest-50 dark:border-gold-500 dark:bg-forest-950/40' 
                                            : 'border-stone-200 dark:border-stone-800 hover:border-forest-500 dark:hover:border-gold-400'">
                                    <div class="flex justify-center mb-2">
                                        <svg class="w-6 h-6 transition-colors" 
                                             :class="role === 'customer' ? 'text-forest-700 dark:text-gold-400' : 'text-ink-muted dark:text-cream/50'"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-ink dark:text-cream mb-0.5">زبون</p>
                                    <p class="text-[10px] text-ink-muted dark:text-cream/50">للتسوق والشراء</p>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="merchant" x-model="role" class="sr-only">
                                <div class="border-2 rounded p-4 text-center transition-all"
                                     :class="role === 'merchant' 
                                            ? 'border-forest-700 bg-forest-50 dark:border-gold-500 dark:bg-forest-950/40' 
                                            : 'border-stone-200 dark:border-stone-800 hover:border-forest-500 dark:hover:border-gold-400'">
                                    <div class="flex justify-center mb-2">
                                        <svg class="w-6 h-6 transition-colors"
                                             :class="role === 'merchant' ? 'text-forest-700 dark:text-gold-400' : 'text-ink-muted dark:text-cream/50'"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-ink dark:text-cream mb-0.5">تاجر</p>
                                    <p class="text-[10px] text-ink-muted dark:text-cream/50">لبيع منتجاتك</p>
                                </div>
                            </label>
                        </div>
                        @error('role') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Name --}}
                    <div>
                        <label for="full_name" class="form-label">الاسم الكامل</label>
                        <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" 
                               required autofocus autocomplete="name"
                               placeholder="محمد أحمد"
                               class="form-input">
                        @error('full_name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" 
                               required autocomplete="username"
                               placeholder="you@example.com" dir="ltr"
                               class="form-input text-left">
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="form-label">رقم الهاتف (اختياري)</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="0599000000" dir="ltr"
                               class="form-input text-left">
                        @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input id="password" type="password" name="password" 
                               required autocomplete="new-password"
                               placeholder="••••••••"
                               class="form-input">
                        <p class="text-xs text-ink-muted dark:text-cream/40 mt-1">8 أحرف على الأقل</p>
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Confirm --}}
                    <div>
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" 
                               required autocomplete="new-password"
                               placeholder="••••••••"
                               class="form-input">
                    </div>

                    {{-- Terms --}}
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" required
                               class="mt-0.5 w-4 h-4 text-forest-700 focus:ring-forest-500 border-stone-300 dark:border-stone-600 rounded">
                        <span class="text-xs leading-relaxed text-ink-muted dark:text-cream/60 group-hover:text-ink dark:group-hover:text-cream transition-colors">
                            أوافق على 
                            <a href="#" class="font-semibold text-forest-700 dark:text-gold-400 hover:opacity-70 underline underline-offset-4">الشروط والأحكام</a>
                            و 
                            <a href="#" class="font-semibold text-forest-700 dark:text-gold-400 hover:opacity-70 underline underline-offset-4">سياسة الخصوصية</a>
                        </span>
                    </label>

                    {{-- Submit --}}
                    <button type="submit" class="btn-solid w-full">
                        إنشاء الحساب
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
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
                            لديك حساب؟
                        </span>
                    </div>
                </div>

                {{-- Login Link --}}
                <a href="{{ route('login') }}" class="btn-outline w-full">
                    تسجيل الدخول
                </a>
            </div>
        </div>
    </div>

</x-guest-layout>