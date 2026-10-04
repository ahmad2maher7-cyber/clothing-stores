<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * عرض الملف الشخصي
     */
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'orders' => $user->orders()->count(),
            'wishlist' => $user->wishlists()->count(),
            'reviews' => $user->reviews()->count(),
            'total_spent' => $user->orders()->where('payment_status', 'paid')->sum('total'),
        ];

        return view('customer.profile', compact('user', 'stats'));
    }

    /**
     * تحديث المعلومات الأساسية
     */
        /**
     * تحديث المعلومات الأساسية
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'full_name.required' => 'الاسم الكامل مطلوب',
            'avatar.image' => 'الصورة يجب أن تكون صورة',
            'avatar.max' => 'حجم الصورة أقل من 2MB',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()
            ->route('customer.profile')
            ->with('success', '✅ تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * تغيير كلمة المرور
     */
        /**
     * تغيير كلمة المرور
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة',
            'password.required' => 'كلمة المرور الجديدة مطلوبة',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        ]);

        $user = auth()->user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // إشعار تغيير كلمة المرور (مع حماية شاملة)
        try {
            $user->notify(new \App\Notifications\PasswordChangedNotification(
                ipAddress: $request->ip() ?? 'غير معروف',
                userAgent: $request->userAgent() ?? 'غير معروف',
                changedAt: now()->format('Y-m-d H:i:s')
            ));
        } catch (\Throwable $e) {
            \Log::error('Customer password change notification failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }

        return redirect()
            ->route('customer.profile')
            ->with('success', '✅ تم تغيير كلمة المرور بنجاح. تحقق من بريدك الإلكتروني');
    }
}