<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FailedLoginAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * عرض صفحة "نسيت كلمة المرور"
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * إرسال رابط إعادة التعيين
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // ✅ Rate Limiting: 5 محاولات كل 15 دقيقة
        $key = 'password-reset:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            // تسجيل المحاولة المشبوهة
            FailedLoginAttempt::create([
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'type' => 'password_reset_blocked',
            ]);

            return back()->withErrors([
                'email' => "⚠️ عدد كبير من المحاولات. الرجاء المحاولة بعد " . ceil($seconds / 60) . " دقيقة",
            ]);
        }

        // محاولة إرسال البريد
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // تسجيل المحاولة
        RateLimiter::hit($key, 900); // 15 دقيقة

        if ($status === Password::RESET_THROTTLED) {
            return back()->withErrors([
                'email' => '⏱️ الرجاء الانتظار قبل محاولة أخرى',
            ]);
        }

        // إذا لم يُوجد البريد، نسجّل المحاولة (لكن لا نُخبر المستخدم لأسباب أمنية)
        if ($status === Password::INVALID_USER) {
            FailedLoginAttempt::create([
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'type' => 'password_reset_invalid_email',
            ]);

            // رسالة عامة لا تكشف وجود البريد
            return back()->with('status', '📧 إذا كان بريدك مسجلاً لدينا، ستستلم رابط إعادة التعيين قريباً.');
        }

        return back()->with('status', '📧 تم إرسال رابط إعادة التعيين إلى بريدك الإلكتروني.');
    }
}