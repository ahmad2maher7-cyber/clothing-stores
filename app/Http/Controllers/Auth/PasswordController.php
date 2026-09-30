<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\PasswordChangedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * تحديث كلمة المرور
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // ✅ إرسال إشعار تنبيهي
        $user->notify(new PasswordChangedNotification(
            ipAddress: $request->ip() ?? 'unknown',
            userAgent: $request->userAgent() ?? 'unknown',
            changedAt: now()->format('Y-m-d H:i:s')
        ));

        return back()->with('status', 'password-updated');
    }
}