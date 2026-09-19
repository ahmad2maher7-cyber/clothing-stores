<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected function getStore()
    {
        $store = auth()->user()->stores()->first();
        if (!$store) {
            abort(404, 'لا يوجد متجر مرتبط بحسابك');
        }
        return $store;
    }

    public function index(Request $request)
    {
        $store = $this->getStore();

        $query = $store->coupons();

        if ($request->search) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        if ($request->status === 'active') {
            $query->where('status', 'active')
                  ->where('end_date', '>=', now())
                  ->where(fn($q) => $q->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit'));
        } elseif ($request->status === 'expired') {
            $query->where(fn($q) => $q->where('end_date', '<', now())
                ->orWhere('status', 'expired'));
        }

        $coupons = $query->latest()->paginate(15);

        $stats = [
            'total' => $store->coupons()->count(),
            'active' => $store->coupons()->where('status', 'active')->where('end_date', '>=', now())->count(),
            'expired' => $store->coupons()->where('end_date', '<', now())->count(),
            'total_used' => $store->coupons()->sum('used_count'),
        ];

        return view('merchant.coupons.index', compact('store', 'coupons', 'stats'));
    }

    public function create()
    {
        $store = $this->getStore();
        return view('merchant.coupons.create', compact('store'));
    }

    public function store(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code|regex:/^[A-Z0-9\-]+$/',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,expired',
        ], [
            'code.required' => 'كود الكوبون مطلوب',
            'code.unique' => 'هذا الكود مستخدم بالفعل',
            'code.regex' => 'الكود يجب أن يحتوي على أحرف إنجليزية وأرقام فقط',
            'type.required' => 'نوع الخصم مطلوب',
            'value.required' => 'قيمة الخصم مطلوبة',
            'start_date.required' => 'تاريخ البداية مطلوب',
            'end_date.required' => 'تاريخ الانتهاء مطلوب',
            'end_date.after' => 'تاريخ الانتهاء يجب أن يكون بعد تاريخ البداية',
        ]);

        // إذا كان النوع نسبة مئوية، تأكد أن القيمة ≤ 100
        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return back()->withErrors(['value' => 'نسبة الخصم لا يمكن أن تتجاوز 100%'])->withInput();
        }

        $validated['store_id'] = $store->id;
        $validated['code'] = strtoupper($validated['code']);

        Coupon::create($validated);

        return redirect()
            ->route('merchant.coupons.index')
            ->with('success', 'تم إنشاء الكوبون بنجاح');
    }

    public function edit(Coupon $coupon)
    {
        $store = $this->getStore();
        if ($coupon->store_id !== $store->id) {
            abort(403);
        }

        return view('merchant.coupons.edit', compact('store', 'coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $store = $this->getStore();
        if ($coupon->store_id !== $store->id) {
            abort(403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id . '|regex:/^[A-Z0-9\-]+$/',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,expired',
        ]);

        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return back()->withErrors(['value' => 'نسبة الخصم لا يمكن أن تتجاوز 100%'])->withInput();
        }

        $validated['code'] = strtoupper($validated['code']);
        $coupon->update($validated);

        return redirect()
            ->route('merchant.coupons.index')
            ->with('success', 'تم تحديث الكوبون بنجاح');
    }

    public function destroy(Coupon $coupon)
    {
        $store = $this->getStore();
        if ($coupon->store_id !== $store->id) {
            abort(403);
        }

        // التحقق من عدم استخدامه في طلبات
        if ($coupon->used_count > 0) {
            return back()->with('error', 'لا يمكن حذف كوبون تم استخدامه في طلبات');
        }

        $coupon->delete();

        return redirect()
            ->route('merchant.coupons.index')
            ->with('success', 'تم حذف الكوبون بنجاح');
    }
}