<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerifiedCustom
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // إذا لم يكن مسجلاً → لصفحة الدخول
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // إذا كان بريده مُفعّلاً → تجاوز
        if ($user->hasVerifiedEmail()) {
            return $next($request);
        }

        // إعادة التوجيه لصفحة OTP
        return redirect()->route('verification.notice')
            ->with('error', 'يجب تفعيل بريدك الإلكتروني أولاً');
    }
}