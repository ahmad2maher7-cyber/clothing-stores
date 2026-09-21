<?php

namespace App\Helpers;

use App\Models\Store;

class StoreColorHelper
{
    /**
     * الحصول على المتجر الحالي (من الـ URL أو الـ session)
     */
    public static function currentStore(): ?Store
    {
        // 1. من الـ Route (إذا كنا في صفحة متجر)
        if (request()->route('store') instanceof Store) {
            return request()->route('store');
        }

        // 2. من الـ Session (آخر متجر تم زيارته)
        if (session()->has('current_store_id')) {
            return Store::find(session('current_store_id'));
        }

        // 3. من الـ User (إذا كان تاجر)
        if (auth()->check() && auth()->user()->role === 'merchant') {
            return auth()->user()->stores()->first();
        }

        return null;
    }

    /**
     * الحصول على ألوان المتجر الحالي (أو الافتراضية)
     */
    public static function colors(): array
    {
        $store = self::currentStore();

        // القيم الافتراضية (للموقع العام)
        $defaults = [
            'primary' => '#4A4A9D',
            'primary_dark' => '#353575',
            'primary_soft' => '#E8E8F5',
            'primary_soft_dark' => '#1A1A35',
            'primary_glow' => 'rgba(74, 74, 157, 0.12)',

            'secondary' => '#1E293B',
            'secondary_dark' => '#0F172A',
            'secondary_light' => '#E2E8F0',
            'secondary_soft' => '#F1F5F9',
            'secondary_soft_dark' => '#0A0E17',
            'secondary_glow' => 'rgba(30, 41, 59, 0.12)',

            'accent' => '#EF4444',
            'accent_dark' => '#B91C1C',
            'accent_soft' => '#FEE2E2',
            'accent_soft_dark' => '#450A0A',
            'accent_glow' => 'rgba(239, 68, 68, 0.12)',

            'theme_mode' => 'light',
        ];

        if (!$store) {
            return $defaults;
        }

        return [
            'primary' => $store->primary_color,
            'primary_dark' => $store->primary_color_dark,
            'primary_soft' => $store->primary_color_soft,
            'primary_soft_dark' => $store->primary_color_soft_dark,
            'primary_glow' => $store->primary_color_glow,

            'secondary' => $store->secondary_color,
            'secondary_dark' => $store->secondary_color_dark,
            'secondary_light' => $store->secondary_color_light,
            'secondary_soft' => $store->secondary_color_soft,
            'secondary_soft_dark' => $store->secondary_color_soft_dark,
            'secondary_glow' => $store->secondary_color_glow,

            'accent' => $store->accent_color,
            'accent_dark' => $store->accent_color_dark,
            'accent_soft' => $store->accent_color_soft,
            'accent_soft_dark' => $store->accent_color_soft_dark,
            'accent_glow' => $store->accent_color_glow,

            'theme_mode' => $store->theme_mode ?? 'light',
        ];
    }
}