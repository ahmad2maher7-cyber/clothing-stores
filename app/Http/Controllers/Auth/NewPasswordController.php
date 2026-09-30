<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PasswordChangedNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * عرض صفحة إعادة تعيين كلمة المرور
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * معالجة طلب إعادة التعيين
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                // ✅ إطلاق حدث Laravel
                event(new PasswordReset($user));

                // ✅ إرسال إشعار تغيير كلمة المرور
                $user->notify(new PasswordChangedNotification(
                    ipAddress: $request->ip() ?? 'unknown',
                    userAgent: $request->userAgent() ?? 'unknown',
                    changedAt: now()->format('Y-m-d H:i:s')
                ));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')
                ->with('status', '✅ تم إعادة تعيين كلمة المرور بنجاح. الرجاء تسجيل الدخول')
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}