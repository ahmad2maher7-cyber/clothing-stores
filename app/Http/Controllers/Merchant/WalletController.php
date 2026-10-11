<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
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
     * صفحة المحفظة
     */
    public function index()
    {
        $store = $this->getStore();
        $wallet = WalletService::getOrCreate($store);

        // آخر 20 حركة
        $transactions = $wallet->transactions()->with('order')->take(20)->get();

        // طلبات السحب السابقة
        $withdrawals = $store->withdrawalRequests()->take(10)->get();

        // إحصائيات
        $stats = [
            'balance' => $wallet->balance,
            'available' => $wallet->available_balance,
            'pending' => $wallet->pending_withdrawal,
            'total_earned' => $wallet->total_earned,
            'total_withdrawn' => $wallet->total_withdrawn,
            'commission_rate' => WalletService::COMMISSION_RATE * 100, // 5
        ];

        return view('merchant.wallet.index', compact('store', 'wallet', 'transactions', 'withdrawals', 'stats'));
    }

    /**
     * صفحة طلب سحب جديد
     */
    public function withdrawForm()
    {
        $store = $this->getStore();
        $wallet = WalletService::getOrCreate($store);

        $availableBalance = $wallet->available_balance;

        if ($availableBalance < 50) {
            return redirect()
                ->route('merchant.wallet.index')
                ->with('error', 'الرصيد المتاح أقل من الحد الأدنى للسحب (50 ₪)');
        }

        return view('merchant.wallet.withdraw', compact('store', 'wallet', 'availableBalance'));
    }

    /**
     * حفظ طلب السحب
     */
    public function withdraw(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:50',
            'payment_method' => 'required|in:bank_transfer,palpay',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'bank_name' => 'nullable|string|max:100|required_if:payment_method,bank_transfer',
            'notes' => 'nullable|string|max:500',
        ], [
            'amount.required' => 'المبلغ مطلوب',
            'amount.min' => 'الحد الأدنى للسحب 50 ₪',
            'payment_method.required' => 'طريقة السحب مطلوبة',
            'account_name.required' => 'اسم صاحب الحساب مطلوب',
            'account_number.required' => 'رقم الحساب / رقم المحفظة مطلوب',
            'bank_name.required_if' => 'اسم البنك مطلوب للتحويل البنكي',
        ]);

        $result = WalletService::createWithdrawalRequest($store, $validated);

        if ($result['success']) {
            return redirect()
                ->route('merchant.wallet.index')
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message'])->withInput();
    }
}