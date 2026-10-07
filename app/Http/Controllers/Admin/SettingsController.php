<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /**
     * عرض صفحة الإعدادات
     */
    public function index()
    {
        $user = auth()->user();
        return view('admin.settings.index', compact('user'));
    }

    /**
     * تحديث الملف الشخصي
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'full_name.required' => 'الاسم الكامل مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'avatar.image' => 'الصورة يجب أن تكون صورة',
            'avatar.max' => 'حجم الصورة أقل من 2MB',
        ]);

        // ✅ رفع الصورة إلى Cloudinary
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                CloudinaryService::delete($user->avatar);
            }

            $publicId = CloudinaryService::upload($request->file('avatar'), 'avatars');
            if ($publicId) {
                $validated['avatar'] = $publicId;
            }
        }

        $user->update($validated);

        return back()->with('success', '✅ تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * تحديث كلمة المرور
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \App\Rules\StrongPassword::rules()],
        ], array_merge(
            \App\Rules\StrongPassword::messages(),
            [
                'current_password.required' => 'كلمة المرور الحالية مطلوبة',
                'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة',
                'password.required' => 'كلمة المرور الجديدة مطلوبة',
                'password.confirmed' => 'كلمتا المرور غير متطابقتين',
            ]
        ));

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        try {
            $user->notify(new \App\Notifications\PasswordChangedNotification(
                ipAddress: $request->ip() ?? 'غير معروف',
                userAgent: $request->userAgent() ?? 'غير معروف',
                changedAt: now()->format('Y-m-d H:i:s')
            ));
        } catch (\Throwable $e) {
            \Log::error('Admin password change notification failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', '✅ تم تغيير كلمة المرور بنجاح');
    }
}