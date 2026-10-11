<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    /**
     * قائمة طلبات السحب
     */
    public function index(Request $request)
    {
        $query = WithdrawalRequest::with(['store', 'wallet', 'processedBy']);

        // فلترة حسب الحالة
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // بحث
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('store', fn($q2) => $q2->where('name', 'like', '%' . $request->search . '%'))
                  ->orWhere('account_name', 'like', '%' . $request->search . '%')
                  ->orWhere('id', $request->search);
            });
        }

        $withdrawals = $query->latest()->paginate(20);

        // إحصائيات
        $stats = [
            'all' => WithdrawalRequest::count(),
            'pending' => WithdrawalRequest::where('status', 'pending')->count(),
            'approved' => WithdrawalRequest::where('status', 'approved')->count(),
            'paid' => WithdrawalRequest::where('status', 'paid')->count(),
            'rejected' => WithdrawalRequest::where('status', 'rejected')->count(),
            'pending_amount' => WithdrawalRequest::where('status', 'pending')->sum('amount'),
            'paid_amount' => WithdrawalRequest::where('status', 'paid')->sum('amount'),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'stats'));
    }

    /**
     * تفاصيل طلب سحب
     */
    public function show(WithdrawalRequest $withdrawal)
    {
        $withdrawal->load(['store.merchant', 'wallet', 'processedBy']);

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    /**
     * الموافقة على الطلب
     */
    public function approve(Request $request, WithdrawalRequest $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'لا يمكن الموافقة على طلب بحالة ' . $withdrawal->status);
        }

        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:500',
        ]);

        WalletService::approveWithdrawal($withdrawal, $validated['admin_note'] ?? null);

        return back()->with('success', '✅ تمت الموافقة على الطلب. تذكّر تحويل المبلغ للتاجر');
    }

    /**
     * تأكيد التحويل (تم إرسال المبلغ)
     */
    public function markAsPaid(Request $request, WithdrawalRequest $withdrawal)
    {
        if (!in_array($withdrawal->status, ['pending', 'approved'])) {
            return back()->with('error', 'لا يمكن تأكيد التحويل لطلب بحالة ' . $withdrawal->status);
        }

        $validated = $request->validate([
            'admin_note' => 'required|string|max:500',
        ], [
            'admin_note.required' => 'يجب إدخال رقم الحوالة أو تفاصيل التحويل',
        ]);

        WalletService::markAsPaid($withdrawal, $validated['admin_note']);

        return back()->with('success', '💰 تم تسجيل تحويل المبلغ للتاجر');
    }

    /**
     * رفض الطلب
     */
    public function reject(Request $request, WithdrawalRequest $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'لا يمكن رفض طلب بحالة ' . $withdrawal->status);
        }

        $validated = $request->validate([
            'admin_note' => 'required|string|max:500',
        ], [
            'admin_note.required' => 'يجب إدخال سبب الرفض',
        ]);

        WalletService::rejectWithdrawal($withdrawal, $validated['admin_note']);

        return back()->with('success', 'تم رفض طلب السحب');
    }
}
