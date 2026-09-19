<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Offer;
use App\Models\Store;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();

        foreach ($stores as $store) {
            // كوبون 1: نسبة مئوية
            Coupon::create([
                'store_id' => $store->id,
                'code' => 'WELCOME' . $store->id,
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 100,
                'max_discount' => 50,
                'usage_limit' => 100,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'status' => 'active',
            ]);

            // كوبون 2: مبلغ ثابت (مع إضافة store_id للكود)
            Coupon::create([
                'store_id' => $store->id,
                'code' => 'SAVE50-' . $store->id,  // ← الحل هنا
                'type' => 'fixed',
                'value' => 50,
                'min_order_amount' => 300,
                'usage_limit' => 50,
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'status' => 'active',
            ]);

            // كوبون 3: إضافي
            Coupon::create([
                'store_id' => $store->id,
                'code' => 'VIP' . $store->id,
                'type' => 'percentage',
                'value' => 15,
                'min_order_amount' => 200,
                'max_discount' => 100,
                'usage_limit' => 20,
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
                'status' => 'active',
            ]);

            // عروض
            Offer::create([
                'store_id' => $store->id,
                'title' => 'تخفيضات الموسم',
                'description' => 'خصم 20% على جميع المنتجات',
                'discount_percent' => 20,
                'start_date' => now(),
                'end_date' => now()->addMonth(),
            ]);

            Offer::create([
                'store_id' => $store->id,
                'title' => 'عرض نهاية الأسبوع',
                'description' => 'خصم 30% على الملابس الرياضية',
                'discount_percent' => 30,
                'start_date' => now(),
                'end_date' => now()->addWeeks(2),
            ]);
        }

        $this->command->info('✅ Coupons & Offers seeded: ' . Coupon::count() . ' coupons, ' . Offer::count() . ' offers');
    }
}