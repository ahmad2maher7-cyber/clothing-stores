<?php

namespace App\Services;

use App\Models\FailedLoginAttempt;
use Illuminate\Http\Request;

class SecurityLoggerService
{
    /**
     * تسجيل محاولة فاشلة
     */
    public static function log(
        string $type,
        ?string $email = null,
        ?int $userId = null,
        ?Request $request = null
    ): FailedLoginAttempt {
        $request = $request ?? request();

        return FailedLoginAttempt::create([
            'email' => $email,
            'user_id' => $userId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'type' => $type,
        ]);
    }

    /**
     * هل IP محظور مؤقتاً؟
     */
    public static function isIpBlocked(string $ip, string $type, int $maxAttempts = 10, int $minutes = 15): bool
    {
        $count = FailedLoginAttempt::where('ip_address', $ip)
            ->where('type', $type)
            ->where('created_at', '>=', now()->subMinutes($minutes))
            ->count();

        return $count >= $maxAttempts;
    }

    /**
     * إحصائيات آخر 24 ساعة
     */
    public static function getStats(): array
    {
        $since = now()->subDay();

        return [
            'total_24h' => FailedLoginAttempt::where('created_at', '>=', $since)->count(),
            'login_failures' => FailedLoginAttempt::where('type', 'login')->where('created_at', '>=', $since)->count(),
            'otp_failures' => FailedLoginAttempt::where('type', 'otp')->where('created_at', '>=', $since)->count(),
            'password_reset_attempts' => FailedLoginAttempt::where('type', 'like', 'password_reset%')->where('created_at', '>=', $since)->count(),
            'unique_ips' => FailedLoginAttempt::where('created_at', '>=', $since)->distinct('ip_address')->count('ip_address'),
        ];
    }

    /**
     * حذف السجلات القديمة
     */
    public static function cleanup(int $days = 30): int
    {
        return FailedLoginAttempt::where('created_at', '<', now()->subDays($days))->delete();
    }
}
