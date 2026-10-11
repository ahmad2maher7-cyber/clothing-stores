<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'balance',
        'total_earned',
        'total_withdrawn',
        'pending_withdrawal',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'pending_withdrawal' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class)->latest();
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class)->latest();
    }

    /**
     * الرصيد المتاح للسحب = الرصيد - المعلق
     */
    public function getAvailableBalanceAttribute(): float
    {
        return max(0, (float) $this->balance - (float) $this->pending_withdrawal);
    }
}
