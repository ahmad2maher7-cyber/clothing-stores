<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        // ═══════════════════════════════════════
        //  إشعار تغيير كلمة المرور
        // ═══════════════════════════════════════
        try {
            $user->notify(new \App\Notifications\PasswordChangedNotification(
                ipAddress: $request->ip() ?? 'غير معروف',
                userAgent: $request->userAgent() ?? 'غير معروف',
                changedAt: now()->format('Y-m-d H:i:s')
            ));
        } catch (\Exception $e) {
            \Log::error('Password changed notification failed: ' . $e->getMessage());
        }

        return back()->with('status', 'password-updated');
    }
}