<?php

namespace Database\Seeders;

use App\Models\ShippingCompany;
use App\Models\ShippingZone;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        // ============ شركات الشحن ============
        $companies = [
            ['name' => 'أرامكس', 'phone' => '1700800', 'tracking_url' => 'https://www.aramex.com/track'],
            ['name' => 'DHL', 'phone' => '1700900', 'tracking_url' => 'https://www.dhl.com/track'],
            ['name' => 'سمسا', 'phone' => '920011000', 'tracking_url' => 'https://smsaexpress.com/track'],
        ];

        foreach ($companies as $company) {
            ShippingCompany::create($company);
        }

        // ============ مناطق الشحن لكل متجر ============
        $zones = [
            ['country' => 'فلسطين', 'city' => 'غزة', 'cost' => 15, 'estimated_days' => 2],
            ['country' => 'فلسطين', 'city' => 'خان يونس', 'cost' => 20, 'estimated_days' => 3],
            ['country' => 'فلسطين', 'city' => 'رفح', 'cost' => 25, 'estimated_days' => 3],
            ['country' => 'فلسطين', 'city' => 'دير البلح', 'cost' => 18, 'estimated_days' => 2],
            ['country' => 'فلسطين', 'city' => 'جباليا', 'cost' => 15, 'estimated_days' => 2],
            ['country' => 'فلسطين', 'city' => 'بيت لاهيا', 'cost' => 15, 'estimated_days' => 2],
        ];

        foreach (Store::all() as $store) {
            foreach ($zones as $zone) {
                ShippingZone::create([
                    'store_id' => $store->id,
                    ...$zone,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✅ Shipping seeded: ' . ShippingCompany::count() . ' companies, ' . ShippingZone::count() . ' zones');
    }
}