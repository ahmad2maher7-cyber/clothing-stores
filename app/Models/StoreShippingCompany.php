<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreShippingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'shipping_company_id',
        'api_key',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function shippingCompany()
    {
        return $this->belongsTo(ShippingCompany::class);
    }
}