<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorePaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'payment_method_id',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}