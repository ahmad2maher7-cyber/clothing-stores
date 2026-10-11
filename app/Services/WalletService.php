<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Store;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletService
{
    /** نسبة عمولة المنصة */
    public const COMMISSION_RATE = 0.05; // 5%

    /**
     * الحصول على محفظة المتجر (أو إنشاؤها)
     */
    public static function getOrCreate(Store $store): Wallet
    {
        return Wallet::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0, 'pending_withdrawal' => 0]
        );
    }

    /**
     * إضافة أرباح الطلب لمحفظة التاجر (بعد التسليم)
     */
    public static function creditFromOrder(Order $order): void
    {
        // تجنّب الإضافة المزدوجة
        if ($order->wallet_credited_at) {
            return;
        }

        $store = $order->store;
        if (!$store) {
            Log::warning('Credit order: no store', ['order' => $order->id]);
            return;
        }

        // العمولة على (المجموع الفرعي - الخصم) — لا نحسب الشحن
        $baseAmount = (float) $order->subtotal - (float) $order->discount;
        if ($baseAmount <= 0) {
            $order->update(['wallet_credited_at' => now()]);
            return;
        }

        $commission = round($baseAmount * self::COMMISSION_RATE, 2);
        $netAmount = round($baseAmount - $commission, 2);

        try {
            DB::transaction(function () use ($order, $store, $netAmount, $commission) {
                $wallet = self::getOrCreate($store);

                $wallet->increment('balance', $netAmount);
                $wallet->increment('total_earned', $netAmount);

                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'order_id' => $order->id,
                    'type' => 'earning',
                    'amount' => $netAmount,
                    'commission' => $commission,
                    'description' => "أرباح الطلب {$order->order_number}",
                ]);

                $order->update(['wallet_credited_at' => now()]);
            });

            Log::info('Wallet credited', [
                'order' => $order->order_number,
                'net' => $netAmount,
                'commission' => $commission,
            ]);

        } catch (\Throwable $e) {
            Log::error('Wallet credit failed: ' . $e->getMessage(), [
                'order_id' => $order->id,
            ]);
        }
    }

    /**
     * إنشاء طلب سحب
     */
    public static function createWithdrawalRequest(Store $store, array $data): array
    {
        $wallet = self::getOrCreate($store);
        $availableBalance = $wallet->available_balance;

        $amount = (float) $data['amount'];

        // الحد الأدنى للسحب
        $minWithdrawal = 50;
        if ($amount < $minWithdrawal) {
            return [
                'success' => false,
                'message' => "الحد الأدنى للسحب: {$minWithdrawal} ₪",
            ];
        }

        if ($amount > $availableBalance) {
            return [
                'success' => false,
                'message' => "الرصيد المتاح للسحب: " . number_format($availableBalance, 2) . " ₪",
            ];
        }

        try {
            DB::transaction(function () use ($wallet, $store, $data, $amount) {
                // إنشاء طلب السحب
                $request = WithdrawalRequest::create([
                    'store_id' => $store->id,
                    'wallet_id' => $wallet->id,
                    'amount' => $amount,
                    'payment_method' => $data['payment_method'],
                    'account_name' => $data['account_name'],
                    'account_number' => $data['account_number'],
                    'bank_name' => $data['bank_name'] ?? null,
                    'notes' => $data['notes'] ?? null,
                    'status' => 'pending',
                ]);

                // حجز المبلغ (لا يُخصم من balance حتى الآن، فقط يُحجز)
                $wallet->increment('pending_withdrawal', $amount);

                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'withdrawal_request_id' => $request->id,
                    'type' => 'adjustment',
                    'amount' => 0,
                    'description' => "طلب سحب #{$request->id} — قيد المراجعة ({$amount} ₪)",
                ]);

                // إشعار المشرفين
                \App\Services\NotificationService::sendToAdmins(
                    title: '💸 طلب سحب جديد',
                    body: "التاجر {$store->name} طلب سحب " . number_format($amount, 2) . " ₪",
                    type: 'withdrawal',
                    actionUrl: route('admin.withdrawals.index')
                );
            });

            return [
                'success' => true,
                'message' => 'تم إرسال طلب السحب بنجاح',
            ];

        } catch (\Throwable $e) {
            Log::error('Withdrawal request failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الطلب: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * موافقة المشرف على طلب السحب
     */
    public static function approveWithdrawal(WithdrawalRequest $request, ?string $adminNote = null): void
    {
        if ($request->status !== 'pending') {
            return;
        }

        $request->update([
            'status' => 'approved',
            'admin_note' => $adminNote,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        // إشعار التاجر
        try {
            \App\Services\NotificationService::send(
                user: $request->store->merchant,
                type: 'withdrawal',
                title: '✅ تمت الموافقة على طلب السحب',
                body: "طلب السحب #{$request->id} بمبلغ " . number_format($request->amount, 2) . " ₪ تمت الموافقة عليه",
                actionUrl: route('merchant.wallet.index')
            );
        } catch (\Throwable $e) {
            Log::error('Withdrawal approval notification failed: ' . $e->getMessage());
        }
    }

    /**
     * تأكيد التحويل الفعلي (بعد ما يحوّل المشرف)
     */
    public static function markAsPaid(WithdrawalRequest $request, ?string $adminNote = null): void
    {
        if (!in_array($request->status, ['pending', 'approved'])) {
            return;
        }

        $wallet = $request->wallet;

        try {
            DB::transaction(function () use ($request, $wallet, $adminNote) {
                // خصم المبلغ من الرصيد
                $wallet->decrement('balance', $request->amount);
                $wallet->increment('total_withdrawn', $request->amount);
                $wallet->decrement('pending_withdrawal', $request->amount);

                // تحديث الطلب
                $request->update([
                    'status' => 'paid',
                    'admin_note' => $adminNote ?? $request->admin_note,
                    'processed_by' => auth()->id(),
                    'processed_at' => now(),
                ]);

                // معاملة
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'withdrawal_request_id' => $request->id,
                    'type' => 'withdrawal',
                    'amount' => -$request->amount,
                    'description' => "تم تحويل مبلغ السحب #{$request->id}",
                ]);
            });

            // إشعار التاجر
            try {
                \App\Services\NotificationService::send(
                    user: $request->store->merchant,
                    type: 'withdrawal',
                    title: '💰 تم تحويل أموالك',
                    body: "تم تحويل " . number_format($request->amount, 2) . " ₪ إلى حسابك",
                    actionUrl: route('merchant.wallet.index')
                );
            } catch (\Throwable $e) {
                Log::error('Withdrawal paid notification failed: ' . $e->getMessage());
            }

        } catch (\Throwable $e) {
            Log::error('Withdrawal mark paid failed: ' . $e->getMessage());
        }
    }

    /**
     * رفض طلب السحب
     */
    public static function rejectWithdrawal(WithdrawalRequest $request, string $reason): void
    {
        if ($request->status !== 'pending') {
            return;
        }

        $request->update([
            'status' => 'rejected',
            'admin_note' => $reason,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        // إلغاء الحجز
        $request->wallet->decrement('pending_withdrawal', $request->amount);

        // إشعار التاجر
        try {
            \App\Services\NotificationService::send(
                user: $request->store->merchant,
                type: 'withdrawal',
                title: '❌ تم رفض طلب السحب',
                body: "طلب السحب #{$request->id} تم رفضه. السبب: {$reason}",
                actionUrl: route('merchant.wallet.index')
            );
        } catch (\Throwable $e) {
            Log::error('Withdrawal rejection notification failed: ' . $e->getMessage());
        }
    }
}