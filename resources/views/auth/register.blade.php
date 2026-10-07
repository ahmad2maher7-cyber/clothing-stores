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
                <form method="POST" action="{{ route('register') }}" class="space-y-5"
                      x-data="registerForm()">
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
                        <div class="relative">
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" 
                                   required autocomplete="new-password"
                                   placeholder="••••••••"
                                   x-model="password"
                                   class="form-input pl-12" dir="ltr">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-ink-muted dark:text-cream/50 hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Strength Indicator --}}
                        <div class="mt-3" x-show="password.length > 0" x-cloak>
                            <div class="flex gap-1 mb-2">
                                <div class="h-1 flex-1 rounded transition-colors" :class="strength.score >= 1 ? 'bg-red-500' : 'bg-stone-200 dark:bg-stone-700'"></div>
                                <div class="h-1 flex-1 rounded transition-colors" :class="strength.score >= 2 ? 'bg-orange-500' : 'bg-stone-200 dark:bg-stone-700'"></div>
                                <div class="h-1 flex-1 rounded transition-colors" :class="strength.score >= 3 ? 'bg-yellow-500' : 'bg-stone-200 dark:bg-stone-700'"></div>
                                <div class="h-1 flex-1 rounded transition-colors" :class="strength.score >= 4 ? 'bg-lime-500' : 'bg-stone-200 dark:bg-stone-700'"></div>
                                <div class="h-1 flex-1 rounded transition-colors" :class="strength.score >= 5 ? 'bg-forest-700 dark:bg-gold-500' : 'bg-stone-200 dark:bg-stone-700'"></div>
                            </div>
                            <p class="text-xs font-medium" :class="strength.color" x-text="strength.label"></p>
                        </div>

                        {{-- Requirements Checklist --}}
                        <div class="mt-4 p-4 bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded">
                            <p class="text-xs font-semibold text-ink dark:text-cream mb-3">متطلبات كلمة المرور:</p>
                            <ul class="space-y-2 text-xs">
                                <li class="flex items-center gap-2 transition-colors" :class="checks.length ? 'text-green-600 dark:text-green-400' : 'text-ink-muted dark:text-cream/50'">
                                    <svg class="w-4 h-4 shrink-0" :class="checks.length ? 'text-green-500' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="checks.length ? 'M5 13l4 4L19 7' : 'M12 8v4m0 4h.01'"/>
                                    </svg>
                                    10 أحرف على الأقل
                                </li>
                                <li class="flex items-center gap-2 transition-colors" :class="(checks.lower && checks.upper) ? 'text-green-600 dark:text-green-400' : 'text-ink-muted dark:text-cream/50'">
                                    <svg class="w-4 h-4 shrink-0" :class="(checks.lower && checks.upper) ? 'text-green-500' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="(checks.lower && checks.upper) ? 'M5 13l4 4L19 7' : 'M12 8v4m0 4h.01'"/>
                                    </svg>
                                    حرف كبير (A-Z) وحرف صغير (a-z)
                                </li>
                                <li class="flex items-center gap-2 transition-colors" :class="checks.number ? 'text-green-600 dark:text-green-400' : 'text-ink-muted dark:text-cream/50'">
                                    <svg class="w-4 h-4 shrink-0" :class="checks.number ? 'text-green-500' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="checks.number ? 'M5 13l4 4L19 7' : 'M12 8v4m0 4h.01'"/>
                                    </svg>
                                    رقم واحد على الأقل (0-9)
                                </li>
                                <li class="flex items-center gap-2 transition-colors" :class="checks.symbol ? 'text-green-600 dark:text-green-400' : 'text-ink-muted dark:text-cream/50'">
                                    <svg class="w-4 h-4 shrink-0" :class="checks.symbol ? 'text-green-500' : 'text-stone-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="checks.symbol ? 'M5 13l4 4L19 7' : 'M12 8v4m0 4h.01'"/>
                                    </svg>
                                    رمز خاص (@, #, $, !, %, ...)
                                </li>
                            </ul>
                        </div>

                        @error('password') <p class="form-error mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Confirm --}}
                    <div>
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" 
                               required autocomplete="new-password"
                               placeholder="••••••••"
                               x-model="passwordConfirmation"
                               class="form-input" dir="ltr">
                        <p class="text-xs mt-2 font-medium transition-colors"
                           x-show="passwordConfirmation.length > 0"
                           x-cloak
                           :class="passwordsMatch ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                           x-text="passwordsMatch ? '✅ كلمتا المرور متطابقتان' : '❌ كلمتا المرور غير متطابقتين'"></p>
                    </div>

                    {{-- Terms --}}
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" name="terms" value="1" required
                               {{ old('terms') ? 'checked' : '' }}
                               class="mt-0.5 w-4 h-4 text-forest-700 focus:ring-forest-500 border-stone-300 dark:border-stone-600 rounded">
                        <span class="text-xs leading-relaxed text-ink-muted dark:text-cream/60 group-hover:text-ink dark:group-hover:text-cream transition-colors">
                            أوافق على 
                            <a href="#" class="font-semibold text-forest-700 dark:text-gold-400 hover:opacity-70 underline underline-offset-4">الشروط والأحكام</a>
                            و 
                            <a href="#" class="font-semibold text-forest-700 dark:text-gold-400 hover:opacity-70 underline underline-offset-4">سياسة الخصوصية</a>
                        </span>
                    </label>
                    @error('terms') <p class="form-error">{{ $message }}</p> @enderror

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

    {{-- Register Form Alpine.js Logic --}}
    <script>
        function registerForm() {
            return {
                role: '{{ old('role', 'customer') }}',
                showPassword: false,
                password: '{{ old('password') }}',
                passwordConfirmation: '',

                get passwordsMatch() {
                    return this.password === this.passwordConfirmation 
                        && this.password.length > 0 
                        && this.passwordConfirmation.length > 0;
                },

                get checks() {
                    return {
                        length: this.password.length >= 10,
                        lower: /[a-z]/.test(this.password),
                        upper: /[A-Z]/.test(this.password),
                        number: /[0-9]/.test(this.password),
                        symbol: /[^A-Za-z0-9]/.test(this.password),
                    };
                },

                get strength() {
                    const c = this.checks;
                    const score = Object.values(c).filter(Boolean).length;

                    const labels = {
                        0: { label: '', color: '' },
                        1: { label: '🔴 ضعيفة جداً', color: 'text-red-600 dark:text-red-400' },
                        2: { label: '🟠 ضعيفة', color: 'text-orange-600 dark:text-orange-400' },
                        3: { label: '🟡 متوسطة', color: 'text-yellow-600 dark:text-yellow-400' },
                        4: { label: '🟢 قوية', color: 'text-lime-600 dark:text-lime-400' },
                        5: { label: '💪 قوية جداً', color: 'text-forest-700 dark:text-gold-400' },
                    };

                    return { ...labels[score], score };
                }
            }
        }
    </script>

</x-guest-layout>