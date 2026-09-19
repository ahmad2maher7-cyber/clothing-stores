<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'address',
        'phone',
        'latitude',
        'longitude',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}