<section class="space-y-6">
    <header>
        <h2 class="font-display text-lg font-bold text-red-600 dark:text-red-400">
            حذف الحساب
        </h2>
        <p class="mt-1 text-sm text-ink-muted dark:text-cream/60">
            عند حذف حسابك، سيتم حذف جميع موارده وبياناته بشكل دائم. قبل الحذف، يرجى تحميل أي بيانات ترغب في الاحتفاظ بها.
        </p>
    </header>

    {{-- Delete Button --}}
    <button type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center justify-center gap-2 h-12 px-6 text-xs font-semibold tracking-widest uppercase text-red-600 dark:text-red-400 border border-stone-300 dark:border-stone-700 hover:border-red-300 dark:hover:border-red-800 hover:bg-red-50 dark:hover:bg-red-950/20 rounded transition-colors">
        <i class="fa-solid fa-trash"></i>
        حذف الحساب
    </button>

    {{-- Confirmation Modal --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="font-display text-lg font-bold text-ink dark:text-cream">
                هل أنت متأكد من حذف حسابك؟
            </h2>

            <p class="mt-2 text-sm text-ink-muted dark:text-cream/60 leading-relaxed">
                عند حذف حسابك، سيتم حذف جميع موارده وبياناته بشكل دائم.
                يرجى إدخال كلمة المرور لتأكيد رغبتك في حذف حسابك نهائياً.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">كلمة المرور</label>
                <input id="password"
                       name="password"
                       type="password"
                       placeholder="كلمة المرور"
                       class="form-input">
                @error('password', 'userDeletion') 
                    <p class="form-error">{{ $message }}</p> 
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" 
                        x-on:click="$dispatch('close')"
                        class="btn-outline">
                    إلغاء
                </button>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 h-12 px-6 text-xs font-semibold tracking-widest uppercase bg-red-600 hover:bg-red-700 text-white rounded transition-colors">
                    <i class="fa-solid fa-trash"></i>
                    حذف الحساب
                </button>
            </div>
        </form>
    </x-modal>
</section>