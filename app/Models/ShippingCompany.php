<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'tracking_url',
        'logo',
    ];

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_shipping_companies')
                    ->withPivot('api_key')
                    ->withTimestamps();
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}