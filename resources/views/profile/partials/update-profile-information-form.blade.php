<section>
    <header>
        <h2 class="font-display text-lg font-bold text-ink dark:text-cream">
            المعلومات الشخصية
        </h2>
        <p class="mt-1 text-sm text-ink-muted dark:text-cream/60">
            حدّث معلومات حسابك والبريد الإلكتروني
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="full_name" class="form-label">الاسم الكامل</label>
            <input id="full_name" 
                   name="full_name" 
                   type="text" 
                   class="form-input" 
                   value="{{ old('full_name', $user->full_name) }}" 
                   required autofocus autocomplete="name">
            @error('full_name') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input id="email" 
                   name="email" 
                   type="email" 
                   class="form-input" 
                   value="{{ old('email', $user->email) }}" 
                   required autocomplete="username"
                   dir="ltr">
            @error('email') <p class="form-error">{{ $message }}</p> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-sm text-amber-700 dark:text-amber-400">
                        بريدك الإلكتروني غير مُفعّل.

                        <button form="send-verification" 
                                class="underline text-sm text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                            اضغط هنا لإعادة إرسال رابط التفعيل
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 inline-flex items-center gap-1.5 font-medium text-sm text-forest-700 dark:text-gold-400">
                            <i class="fa-solid fa-circle-check"></i>
                            تم إرسال رابط تفعيل جديد إلى بريدك الإلكتروني
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-stone-200 dark:border-stone-800">
            <button type="submit" class="btn-solid">
                <i class="fa-solid fa-check"></i>
                حفظ
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="inline-flex items-center gap-1.5 text-sm text-forest-700 dark:text-gold-400 font-medium">
                    <i class="fa-solid fa-circle-check"></i>
                    تم الحفظ
                </p>
            @endif
        </div>
    </form>
</section>