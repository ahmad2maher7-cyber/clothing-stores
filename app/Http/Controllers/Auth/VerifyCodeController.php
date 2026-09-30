<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyCodeRequest;
use App\Models\EmailVerificationCode;
use App\Services\EmailVerificationService;
use App\Services\SecurityLoggerService;

class VerifyCodeController extends Controller
{
    public function __construct(
        protected EmailVerificationService $verificationService
    ) {}

    /**
     * عرض صفحة إدخال الكود
     */
    public function notice()
    {
        $user = auth()->user();

        // إذا كان مُفعّلاً → Dashboard
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        // ✅ إذا لم يوجد كود نشط → ولّد واحد جديداً
        $hasActiveCode = EmailVerificationCode::where('user_id', $user->id)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->exists();

        if (!$hasActiveCode) {
            // امسح Rate Limiter لإعادة الإرسال
            \Illuminate\Support\Facades\RateLimiter::clear('otp:' . $user->id);

            $result = $this->verificationService->generateAndSend($user);

            if ($result['success']) {
                // في بيئة التطوير: احفظ الكود للمساعدة
                if (app()->isLocal()) {
                    session()->flash('dev_code', $result['code']);
                }
                session()->flash('success', 'تم إرسال كود تحقق جديد إلى بريدك');
            }
        }

        return view('auth.verify-code');
    }

    /**
     * التحقق من الكود
     */
    public function verify(VerifyCodeRequest $request)
    {
        $user = auth()->user();

        // 🔍 Log للتشخيص
        \Log::info('=== OTP Verification Attempt ===', [
            'user_id' => $user->id,
            'email' => $user->email,
            'received_code' => $request->code,
        ]);

        // التحقق من عدم الحظر
        if ($user->isLocked()) {
            $remaining = now()->diffInMinutes($user->locked_until);
            return back()->withErrors([
                'code' => "حسابك محظور مؤقتاً. حاول بعد {$remaining} دقيقة",
            ]);
        }

        // تنفيذ التحقق
        $result = $this->verificationService->verify($user, $request->code);

        \Log::info('Verification result:', $result);

        // ❌ فشل التحقق
        if (!$result['success']) {
            SecurityLoggerService::log(
                type: 'otp',
                email: $user->email,
                userId: $user->id
            );

            $user->incrementFailedAttempts();

            return back()
                ->withErrors(['code' => $result['message']])
                ->withInput();
        }

        // ✅ نجح التحقق
        \Log::info('OTP verified successfully for user: ' . $user->id);

        return redirect()->route('dashboard')
            ->with('success', $result['message']);
    }

    /**
     * إعادة إرسال الكود
     */
    public function resend()
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $result = $this->verificationService->generateAndSend($user);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        if (app()->isLocal()) {
            session()->flash('dev_code', $result['code']);
        }

        return back()->with('success', $result['message']);
    }
}