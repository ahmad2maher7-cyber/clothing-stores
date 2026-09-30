<section>
    <header>
        <h2 class="font-display text-lg font-bold text-ink dark:text-cream">
            تغيير كلمة المرور
        </h2>
        <p class="mt-1 text-sm text-ink-muted dark:text-cream/60">
            استخدم كلمة مرور قوية وطويلة للحفاظ على أمان حسابك
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="form-label">كلمة المرور الحالية</label>
            <input id="update_password_current_password" 
                   name="current_password" 
                   type="password" 
                   class="form-input" 
                   autocomplete="current-password"
                   placeholder="••••••••">
            @error('current_password', 'updatePassword') 
                <p class="form-error">{{ $message }}</p> 
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="form-label">كلمة المرور الجديدة</label>
            <input id="update_password_password" 
                   name="password" 
                   type="password" 
                   class="form-input" 
                   autocomplete="new-password"
                   placeholder="••••••••">
            @error('password', 'updatePassword') 
                <p class="form-error">{{ $message }}</p> 
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="form-label">تأكيد كلمة المرور</label>
            <input id="update_password_password_confirmation" 
                   name="password_confirmation" 
                   type="password" 
                   class="form-input" 
                   autocomplete="new-password"
                   placeholder="••••••••">
            @error('password_confirmation', 'updatePassword') 
                <p class="form-error">{{ $message }}</p> 
            @enderror
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-stone-200 dark:border-stone-800">
            <button type="submit" class="btn-solid">
                <i class="fa-solid fa-lock"></i>
                حفظ
            </button>

            @if (session('status') === 'password-updated')
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