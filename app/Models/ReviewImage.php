<?php

namespace App\Models;

use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'image_url',
    ];

    // ============ العلاقات ============

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    // ═══════════════════════════════════════
    //  Accessor — رابط الصورة
    // ═══════════════════════════════════════

    /**
     * رابط الصورة الكامل
     * الاستخدام: {{ $image->url }}
     */
    public function getUrlAttribute(): ?string
    {
        return CloudinaryService::url($this->image_url);
    }
}