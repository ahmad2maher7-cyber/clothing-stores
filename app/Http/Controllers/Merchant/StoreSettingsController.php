<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use App\Models\Store;
use App\Models\StoreBranch;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class StoreSettingsController extends Controller
{
    protected function getStore()
    {
        $store = auth()->user()->stores()->first();
        if (!$store) {
            abort(404, 'لا يوجد متجر مرتبط بحسابك');
        }
        return $store;
    }

    /**
     * عرض إعدادات المتجر
     */
    public function index()
    {
        $store = $this->getStore();
        return view('merchant.settings.index', compact('store'));
    }

    /**
     * تحديث الإعدادات الأساسية
     */
    public function update(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'commercial_register' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'working_hours' => 'nullable|array',
            'working_hours.*' => 'nullable|string|max:50',
        ], [
            'name.required' => 'اسم المتجر مطلوب',
            'logo.image' => 'الشعار يجب أن يكون صورة',
            'logo.max' => 'حجم الشعار أقل من 2MB',
            'banner.image' => 'الغلاف يجب أن يكون صورة',
            'banner.max' => 'حجم الغلاف أقل من 3MB',
        ]);

        // ✅ رفع الشعار إلى Cloudinary
        if ($request->hasFile('logo')) {
            // حذف القديم من Cloudinary
            if ($store->logo) {
                CloudinaryService::delete($store->logo);
            }
            $publicId = CloudinaryService::upload($request->file('logo'), 'stores/logos');
            if ($publicId) {
                $validated['logo'] = $publicId;
            }
        }

        // ✅ رفع الغلاف إلى Cloudinary
        if ($request->hasFile('banner')) {
            if ($store->banner) {
                CloudinaryService::delete($store->banner);
            }
            $publicId = CloudinaryService::upload($request->file('banner'), 'stores/banners');
            if ($publicId) {
                $validated['banner'] = $publicId;
            }
        }

        // تنقية أوقات العمل (إزالة الفارغة)
        if (isset($validated['working_hours'])) {
            $validated['working_hours'] = array_filter($validated['working_hours'], fn($v) => !empty($v));
        }

        $store->update($validated);

        return redirect()
            ->route('merchant.settings.index')
            ->with('success', 'تم تحديث إعدادات المتجر بنجاح');
    }

    /**
     * تحديث السياسات
     */
    public function updatePolicies(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            'privacy_policy' => 'nullable|string|max:10000',
            'return_policy' => 'nullable|string|max:10000',
        ]);

        $store->update($validated);

        return redirect()
            ->route('merchant.settings.policies')
            ->with('success', 'تم تحديث السياسات بنجاح');
    }

    /**
     * صفحة السياسات
     */
    public function policies()
    {
        $store = $this->getStore();
        return view('merchant.settings.policies', compact('store'));
    }

    /**
     * ========== الفروع ==========
     */
    public function branches()
    {
        $store = $this->getStore();
        $branches = $store->branches()->orderByDesc('is_main')->get();
        return view('merchant.settings.branches', compact('store', 'branches'));
    }

    public function storeBranch(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_main' => 'boolean',
        ]);

        $validated['store_id'] = $store->id;

        if (!empty($validated['is_main'])) {
            $store->branches()->update(['is_main' => false]);
        }

        StoreBranch::create($validated);

        return redirect()
            ->route('merchant.settings.branches')
            ->with('success', 'تم إضافة الفرع بنجاح');
    }

    public function updateBranch(Request $request, StoreBranch $branch)
    {
        $store = $this->getStore();
        if ($branch->store_id !== $store->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_main' => 'boolean',
        ]);

        if (!empty($validated['is_main'])) {
            $store->branches()->where('id', '!=', $branch->id)->update(['is_main' => false]);
        }

        $branch->update($validated);

        return redirect()
            ->route('merchant.settings.branches')
            ->with('success', 'تم تحديث الفرع بنجاح');
    }

    public function destroyBranch(StoreBranch $branch)
    {
        $store = $this->getStore();
        if ($branch->store_id !== $store->id) {
            abort(403);
        }

        if ($branch->is_main && $store->branches()->count() === 1) {
            return back()->with('error', 'لا يمكن حذف الفرع الرئيسي الوحيد');
        }

        $branch->delete();

        return redirect()
            ->route('merchant.settings.branches')
            ->with('success', 'تم حذف الفرع بنجاح');
    }

    /**
     * ========== مناطق الشحن ==========
     */
    public function shipping()
    {
        $store = $this->getStore();
        $zones = $store->shippingZones()->orderBy('city')->get();
        return view('merchant.settings.shipping', compact('store', 'zones'));
    }

    public function storeShipping(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'cost' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1|max:60',
            'is_active' => 'boolean',
        ], [
            'country.required' => 'الدولة مطلوبة',
            'city.required' => 'المدينة مطلوبة',
            'cost.required' => 'تكلفة الشحن مطلوبة',
            'estimated_days.required' => 'المدة التقديرية مطلوبة',
        ]);

        $validated['store_id'] = $store->id;

        ShippingZone::create($validated);

        return redirect()
            ->route('merchant.settings.shipping')
            ->with('success', 'تم إضافة منطقة الشحن بنجاح');
    }

    public function updateShipping(Request $request, ShippingZone $zone)
    {
        $store = $this->getStore();
        if ($zone->store_id !== $store->id) {
            abort(403);
        }

        $validated = $request->validate([
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'cost' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1|max:60',
            'is_active' => 'boolean',
        ]);

        $zone->update($validated);

        return redirect()
            ->route('merchant.settings.shipping')
            ->with('success', 'تم تحديث منطقة الشحن بنجاح');
    }

    public function destroyShipping(ShippingZone $zone)
    {
        $store = $this->getStore();
        if ($zone->store_id !== $store->id) {
            abort(403);
        }

        $zone->delete();

        return redirect()
            ->route('merchant.settings.shipping')
            ->with('success', 'تم حذف منطقة الشحن بنجاح');
    }

    /**
     * صفحة الهوية البصرية
     */
    public function appearance()
    {
        $store = $this->getStore();
        return view('merchant.settings.appearance', compact('store'));
    }

    /**
     * تحديث الألوان
     */
    public function updateAppearance(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'accent_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'theme_mode' => 'required|in:light,dark',
        ], [
            'primary_color.regex' => 'اللون الأساسي غير صالح',
            'secondary_color.regex' => 'اللون الثانوي غير صالح',
            'accent_color.regex' => 'لون التمييز غير صالح',
        ]);

        $store->update($validated);

        return redirect()
            ->route('merchant.settings.appearance')
            ->with('success', 'تم تحديث الهوية البصرية بنجاح');
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
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        try {
            $user->notify(new \App\Notifications\PasswordChangedNotification(
                ipAddress: $request->ip() ?? 'غير معروف',
                userAgent: $request->userAgent() ?? 'غير معروف',
                changedAt: now()->format('Y-m-d H:i:s')
            ));
        } catch (\Throwable $e) {
            \Log::error('Password changed notification failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('merchant.settings.index')
            ->with('success', '✅ تم تغيير كلمة المرور بنجاح');
    }
}