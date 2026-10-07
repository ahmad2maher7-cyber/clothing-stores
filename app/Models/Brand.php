<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
public function getLogoUrlAttribute(): ?string
{
    return \App\Services\CloudinaryService::url($this->logo);
}

}