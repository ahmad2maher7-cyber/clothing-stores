<?php

namespace App\Services;

use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmailVerificationService
{
    public const CODE_EXPIRY_MINUTES = 10;
    public const RESEND_COOLDOWN_MINUTES = 10;
    public const CODE_LENGTH = 6;

    /**
     * توليد كود OTP جديد وإرساله
     */
    public function generateAndSend(User $user): array
    {
        // التحقق من Rate Limiting
        if ($this->hasRecentCode($user)) {
            $remaining = $this->getRemainingCooldown($user);

            return [
                'success' => false,
                'message' => "يمكنك إعادة الإرسال بعد {$remaining} دقيقة",
                'code' => null,
            ];
        }

        // توليد كود 6 أرقام
        $code = str_pad(random_int(0, 999999), self::CODE_LENGTH, '0', STR_PAD_LEFT);

        // حذف الأكواد القديمة
        EmailVerificationCode::where('user_id', $user->id)
            ->whereNull('used_at')
            ->delete();

        // إنشاء كود جديد
        EmailVerificationCode::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::CODE_EXPIRY_MINUTES),
        ]);

        // إرسال الإشعار
        $user->notify(new \App\Notifications\EmailVerificationNotification($code));

        return [
            'success' => true,
            'message' => 'تم إرسال كود التحقق إلى بريدك الإلكتروني',
            'code' => $code,
        ];
    }

    /**
     * التحقق من الكود
     */
    public function verify(User $user, string $code): array
    {
        // البحث عن آخر كود نشط
        $verificationCode = EmailVerificationCode::where('user_id', $user->id)
            ->whereNull('used_at')
            ->latest()
            ->first();

        // لا يوجد كود
        if (!$verificationCode) {
            return [
                'success' => false,
                'message' => 'لا يوجد كود نشط. الرجاء طلب كود جديد',
            ];
        }

        // الكود منتهي
        if ($verificationCode->expires_at->isPast()) {
            return [
                'success' => false,
                'message' => 'انتهت صلاحية الكود. الرجاء طلب كود جديد',
            ];
        }

        // الكود خاطئ
        if (!Hash::check($code, $verificationCode->code)) {
            return [
                'success' => false,
                'message' => 'الكود غير صحيح',
            ];
        }

        // ✅ نجح التحقق
        $verificationCode->markAsUsed();

        // ✅ تحديث المستخدم باستخدام forceFill
        $user->forceFill([
            'email_verified_at' => now(),
            'failed_attempts' => 0,
            'locked_until' => null,
        ])->save();

        // ✅ تحديث الـ Session فوراً
        auth()->setUser($user->fresh());

        return [
            'success' => true,
            'message' => 'تم التحقق من بريدك الإلكتروني بنجاح',
        ];
    }

    /**
     * هل يوجد كود حديث؟
     */
    protected function hasRecentCode(User $user): bool
    {
        return EmailVerificationCode::where('user_id', $user->id)
            ->where('created_at', '>', now()->subMinutes(self::RESEND_COOLDOWN_MINUTES))
            ->exists();
    }

    /**
     * حساب المدة المتبقية
     */
    protected function getRemainingCooldown(User $user): int
    {
        $lastCode = EmailVerificationCode::where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$lastCode) {
            return 0;
        }

        $availableAt = $lastCode->created_at->addMinutes(self::RESEND_COOLDOWN_MINUTES);

        return max(0, (int) now()->diffInMinutes($availableAt, false));
    }
}