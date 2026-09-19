<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'fabric_type',
        'sku',
        'price',
        'discount_price',
        'stock_quantity',
        'low_stock_threshold',
        'image',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    // ============ العلاقات ============

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class, 'variant_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'variant_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'variant_id');
    }

    // ============ Helper Methods ============

    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }
}