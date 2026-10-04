<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * نموذج إنشاء المتجر
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->stores()->exists()) {
            $store = $user->stores()->first();

            if ($store->status === 'active') {
                return redirect()->route('merchant.dashboard');
            }

            return redirect()->route('merchant.store.pending');
        }

        return view('merchant.store.create');
    }

    /**
     * حفظ المتجر الجديد (بحالة pending)
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->stores()->exists()) {
            return redirect()->route('merchant.dashboard');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:stores,name',
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'commercial_register' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'phone' => 'required|string|max:20',
        ], [
            'name.required' => 'اسم المتجر مطلوب',
            'name.unique' => 'اسم المتجر مستخدم بالفعل',
            'address.required' => 'العنوان مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
        ]);

        // رفع الشعار
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('stores/logos', 'public');
        }

        // رفع الغلاف
        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('stores/banners', 'public');
        }

        // إنشاء المتجر بحالة pending
        Store::create([
            'merchant_id' => $user->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'address' => $validated['address'],
            'commercial_register' => $validated['commercial_register'] ?? null,
            'logo' => $validated['logo'] ?? null,
            'banner' => $validated['banner'] ?? null,
            'status' => 'pending',
        ]);

        // تحديث رقم الهاتف في حساب المستخدم
        $user->update(['phone' => $validated['phone']]);

        return redirect()
            ->route('merchant.store.pending')
            ->with('success', 'تم إرسال طلبك بنجاح! سيتم مراجعته من قبل الإدارة');
    }

    /**
     * صفحة انتظار الموافقة
     */
    public function pending()
    {
        $user = auth()->user();
        $store = $user->stores()->first();

        if (!$store) {
            return redirect()->route('merchant.store.create');
        }

        if ($store->status === 'active') {
            return redirect()->route('merchant.dashboard');
        }

        return view('merchant.store.pending', compact('store'));
    }


        /**
     * تحديث كلمة المرور
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)],
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة',
            'password.required' => 'كلمة المرور الجديدة مطلوبة',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        ]);

        $user = auth()->user();

        $user->update([
            'password' => \Hash::make($validated['password']),
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

        return redirect()
            ->route('merchant.settings.index')
            ->with('success', 'تم تغيير كلمة المرور بنجاح. تحقق من بريدك الإلكتروني');
    }
    
}