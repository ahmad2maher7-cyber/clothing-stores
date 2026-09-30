<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Get image URL - handles both local paths and external URLs
     */
    public static function url(?string $path): string
    {
        if (!$path) {
            return asset('images/default-product.jpg');
        }

        // External URL (http:// or https://)
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Local storage path
        return asset('storage/' . ltrim($path, '/'));
    }
}