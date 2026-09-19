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

        // إحصائيات
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
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'full_name.required' => 'الاسم الكامل مطلوب',
        ]);

        // رفع الصورة
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()
            ->route('customer.profile')
            ->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * تغيير كلمة المرور
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('customer.profile')
            ->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}