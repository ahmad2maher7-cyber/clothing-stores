<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'wallet_id',
        'amount',
        'payment_method',
        'account_name',
        'account_number',
        'bank_name',
        'notes',
        'status',
        'admin_note',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => '⏳ قيد المراجعة',
            'approved' => '✅ تمت الموافقة',
            'rejected' => '❌ مرفوض',
            'paid' => '💰 تم التحويل',
            default => $this->status,
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'bank_transfer' => '🏦 تحويل بنكي',
            'palpay' => '📱 PalPay',
            default => $this->payment_method,
        };
    }
}
