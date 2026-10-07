<?php

namespace App\Models;

use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_url',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // ═══════════════════════════════════════
    //  العلاقات
    // ═══════════════════════════════════════

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ═══════════════════════════════════════
    //  Accessors — للعرض التلقائي
    // ═══════════════════════════════════════

    /**
     * رابط الصورة الكامل (Cloudinary أو محلي)
     * الاستخدام: {{ $image->url }}
     */
    public function getUrlAttribute(): ?string
    {
        return CloudinaryService::url($this->image_url);
    }

    /**
     * رابط مصغّر
     * الاستخدام: {{ $image->thumbnail }}
     */
    public function getThumbnailAttribute(): ?string
    {
        return CloudinaryService::thumbnail($this->image_url);
    }
}