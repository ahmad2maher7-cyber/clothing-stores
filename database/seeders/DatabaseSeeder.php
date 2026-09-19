<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 بدء زراعة البيانات...');
        $this->command->newLine();

        // الترتيب مهم جداً!
        $this->call([
            UserSeeder::class,
            StoreSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            PaymentMethodSeeder::class,
            ShippingSeeder::class,
            StoreRelationsSeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
            ReviewSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('🎉 تم زراعة جميع البيانات بنجاح!');
        $this->command->newLine();
        $this->command->table(
            ['البريد الإلكتروني', 'كلمة المرور', 'الدور'],
            [
                ['admin@store.com', 'password', 'مشرف'],
                ['ahmed@store.com', 'password', 'تاجر'],
                ['sara@store.com', 'password', 'تاجر'],
                ['khaled@store.com', 'password', 'تاجر'],
                ['mohamed@test.com', 'password', 'زبون'],
            ]
        );
    }
}