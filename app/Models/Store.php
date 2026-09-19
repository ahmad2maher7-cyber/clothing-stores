<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'name',
        'logo',
        'banner',
        'description',
        'commercial_register',
        'working_hours',
        'address',
        'privacy_policy',
        'return_policy',
        'status',
    ];

    protected $casts = [
        'working_hours' => 'array',
    ];

    // ============ العلاقات ============

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function branches()
    {
        return $this->hasMany(StoreBranch::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function shippingZones()
    {
        return $this->hasMany(ShippingZone::class);
    }

    public function shippingCompanies()
    {
        return $this->belongsToMany(ShippingCompany::class, 'store_shipping_companies')
                    ->withPivot('api_key')
                    ->withTimestamps();
    }

    public function paymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class, 'store_payment_methods')
                    ->withPivot('config')
                    ->withTimestamps();
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function contactMessages()
    {
        return $this->hasMany(ContactMessage::class);
    }

    public function chatConversations()
    {
        return $this->hasMany(ChatConversation::class);
    }
}