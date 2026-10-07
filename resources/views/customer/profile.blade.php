@extends('customer.layouts.app')

@section('title', 'الملف الشخصي')

@section('content')

    <div class="container-x py-10 lg:py-14">

        {{-- ═══ Header ═══ --}}
        <div class="mb-8">
            <span class="eyebrow block mb-3">— حسابي</span>
            <h1 class="font-display text-3xl font-bold text-ink dark:text-cream mb-2">
                الملف الشخصي
            </h1>
            <p class="text-sm text-ink-muted dark:text-cream/60">
                إدارة معلوماتك الشخصية
            </p>
        </div>

        {{-- ═══ Stats ═══ --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 text-center">
                <p class="font-display text-2xl font-bold text-ink dark:text-cream">{{ $stats['orders'] }}</p>
                <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mt-1">طلب</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 text-center">
                <p class="font-display text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['wishlist'] }}</p>
                <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mt-1">مفضلة</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 text-center">
                <p class="font-display text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['reviews'] }}</p>
                <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mt-1">تقييم</p>
            </div>
            <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 text-center">
                <p class="font-display text-2xl font-bold text-forest-700 dark:text-gold-400">{{ number_format($stats['total_spent'], 0) }}</p>
                <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mt-1">₪ مشتريات</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- ═══ Avatar ═══ --}}
            <div class="md:col-span-1">
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6 text-center">

                    @if($user->avatar)
                        <img src="{{ $user->avatar_url }}"
                             alt="{{ $user->full_name }}"
                             class="w-24 h-24 rounded-full mx-auto object-cover mb-4 border-4 border-stone-100 dark:border-zinc-800">
                    @else
                        <div class="w-24 h-24 rounded-full mx-auto bg-forest-50 dark:bg-forest-950/40 flex items-center justify-center text-4xl font-bold text-forest-700 dark:text-gold-400 mb-4 border-4 border-stone-100 dark:border-zinc-800">
                            {{ mb_substr($user->full_name, 0, 1) }}
                        </div>
                    @endif

                    <h2 class="font-display text-xl font-bold text-ink dark:text-cream mb-1">
                        {{ $user->full_name }}
                    </h2>
                    <p class="text-sm text-ink-muted dark:text-cream/60 mb-4">
                        {{ $user->email }}
                    </p>

                    <div class="badge badge-forest inline-flex">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        عضو منذ {{ $user->created_at->format('Y/m/Y') }}
                    </div>
                </div>
            </div>

            {{-- ═══ Forms ═══ --}}
            <div class="md:col-span-2 space-y-6">

                {{-- Basic Info --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                    <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="font-display font-bold text-ink dark:text-cream">المعلومات الأساسية</h3>
                    </div>

                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-5">
                        @csrf @method('PUT')

                        <div>
                            <label class="form-label">الاسم الكامل <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                                   class="form-input">
                            @error('full_name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="form-input bg-stone-50 dark:bg-zinc-800 cursor-not-allowed opacity-70">
                            <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">لا يمكن تغيير البريد الإلكتروني</p>
                        </div>

                        <div>
                            <label class="form-label">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   placeholder="0599000000" dir="ltr"
                                   class="form-input">
                            @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">الصورة الشخصية</label>
                            <input type="file" name="avatar" accept="image/*"
                                   class="form-input file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-forest-50 dark:file:bg-forest-950/40 file:text-forest-700 dark:file:text-gold-400 hover:file:bg-forest-100 dark:hover:file:bg-forest-900/50">
                            <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">JPG, PNG — أقل من 2MB</p>
                            @error('avatar') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end pt-4 border-t border-stone-200 dark:border-stone-800">
                            <button type="submit" class="btn-solid">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Password --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                    <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="font-display font-bold text-ink dark:text-cream">تغيير كلمة المرور</h3>
                    </div>

                    <form action="{{ route('customer.profile.password') }}" method="POST" class="p-5 space-y-5">
                        @csrf @method('PUT')

                        <div>
                            <label class="form-label">كلمة المرور الحالية <span class="text-red-500">*</span></label>
                            <input type="password" name="current_password" required
                                   placeholder="••••••••"
                                   class="form-input">
                            @error('current_password') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">كلمة المرور الجديدة <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required
                                   placeholder="••••••••"
                                   class="form-input">
                            @error('password') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required
                                   placeholder="••••••••"
                                   class="form-input">
                        </div>

                        <div class="flex justify-end pt-4 border-t border-stone-200 dark:border-stone-800">
                            <button type="submit" class="btn-solid">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                تغيير كلمة المرور
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Security Activity --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                    <div class="p-5 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="font-display font-bold text-ink dark:text-cream">نشاط الأمان</h3>
                    </div>

                    <div class="p-5">
                        @php
                            $recentAttempts = \App\Models\FailedLoginAttempt::where('user_id', auth()->id())
                                ->orWhere('email', auth()->user()->email)
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp

                        @if($recentAttempts->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentAttempts as $attempt)
                                    <div class="flex items-start gap-3 p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 rounded">
                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <div class="flex-1 text-sm">
                                            <p class="font-medium text-red-800 dark:text-red-300">
                                                @switch($attempt->type)
                                                    @case('login') محاولة تسجيل دخول فاشلة @break
                                                    @case('otp') كود تحقق خاطئ @break
                                                    @case('password_reset_blocked') تجاوز حد إعادة التعيين @break
                                                    @case('password_reset_invalid_email') محاولة إعادة تعيين لبريد غير مسجل @break
                                                    @default نشاط مشبوه
                                                @endswitch
                                            </p>
                                            <p class="text-xs text-red-700 dark:text-red-400 mt-1">
                                                {{ $attempt->created_at->diffForHumans() }} • IP: {{ $attempt->ip_address }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-ink-muted dark:text-cream/50 mt-4 text-center">
                                إذا لم تتعرف على أي نشاط، قم بتغيير كلمة المرور فوراً
                            </p>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 rounded-full">
                                    <svg class="w-8 h-8 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-ink dark:text-cream">لا يوجد نشاط مريب</p>
                                <p class="text-xs text-ink-muted dark:text-cream/50 mt-1">حسابك آمن</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection