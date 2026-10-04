<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
 public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = auth()->user();

    // ═══════════════════════════════════════
    //  1. إشعار في قاعدة البيانات
    // ═══════════════════════════════════════
    try {
        \App\Services\NotificationService::send(
            userId: $user->id,
            title: 'تسجيل دخول جديد',
            body: 'تم تسجيل دخول جديد إلى حسابك من IP: ' . $request->ip(),
            type: 'login'
        );
    } catch (\Exception $e) {
        \Log::error('DB notification failed: ' . $e->getMessage());
    }

    // ═══════════════════════════════════════
    //  2. إيميل إشعار
    // ═══════════════════════════════════════
    try {
        \Illuminate\Support\Facades\Mail::to($user->email)
            ->send(new \App\Mail\LoginNotificationMail(
                userName: $user->full_name,
                ipAddress: $request->ip(),
                userAgent: $request->userAgent() ?? 'Unknown',
                loginTime: now()->format('Y-m-d H:i:s')
            ));
    } catch (\Exception $e) {
        \Log::error('Login email failed: ' . $e->getMessage());
    }

    return redirect()->intended(match ($user->role) {
        'admin' => route('admin.dashboard'),
        'merchant' => route('merchant.dashboard'),
        default => route('customer.dashboard'),
    });
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
