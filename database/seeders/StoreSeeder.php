<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreBranch;
use App\Models\User;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $merchants = User::where('role', 'merchant')->get();

        $storesData = [
            [
                'name' => 'متجر الأناقة',
                'description' => 'أفضل الملابس الرجالية العصرية بأحدث الموديلات العالمية',
                'commercial_register' => 'CR-12345',
                'address' => 'غزة - شارع عمر المختار',
                'working_hours' => [
                    'saturday' => '09:00 - 22:00',
                    'sunday' => '09:00 - 22:00',
                    'monday' => '09:00 - 22:00',
                    'tuesday' => '09:00 - 22:00',
                    'wednesday' => '09:00 - 22:00',
                    'thursday' => '09:00 - 23:00',
                    'friday' => '14:00 - 22:00',
                ],
                'privacy_policy' => 'نحن نحترم خصوصيتك ولا نشارك بياناتك مع أي طرف ثالث.',
                'return_policy' => 'يمكنك الاستبدال أو الإرجاع خلال 14 يوماً من تاريخ الاستلام.',
            ],
            [
                'name' => 'أزياء سارة',
                'description' => 'ملابس نسائية راقية وموديلات حصرية',
                'commercial_register' => 'CR-67890',
                'address' => 'غزة - الرمال - شارع الوحدة',
                'working_hours' => [
                    'saturday' => '10:00 - 21:00',
                    'sunday' => '10:00 - 21:00',
                    'monday' => '10:00 - 21:00',
                    'tuesday' => '10:00 - 21:00',
                    'wednesday' => '10:00 - 21:00',
                    'thursday' => '10:00 - 22:00',
                    'friday' => 'CLOSED',
                ],
                'privacy_policy' => 'سياسة الخصوصية: جميع البيانات محفوظة ولا تُستخدم إلا لتحسين الخدمة.',
                'return_policy' => 'الإرجاع خلال 7 أيام بشرط عدم الاستخدام.',
            ],
            [
                'name' => 'عالم الأطفال',
                'description' => 'كل ما يحتاجه طفلك من ملابس وأزياء',
                'commercial_register' => 'CR-11111',
                'address' => 'غزة - تل الهوا',
                'working_hours' => [
                    'saturday' => '09:00 - 20:00',
                    'sunday' => '09:00 - 20:00',
                    'monday' => '09:00 - 20:00',
                    'tuesday' => '09:00 - 20:00',
                    'wednesday' => '09:00 - 20:00',
                    'thursday' => '09:00 - 21:00',
                    'friday' => 'CLOSED',
                ],
                'privacy_policy' => 'نحمي بيانات أطفالك وعملائنا الكرام.',
                'return_policy' => 'إرجاع مجاني خلال 30 يوماً.',
            ],
        ];

        foreach ($merchants as $index => $merchant) {
            $store = Store::create([
                'merchant_id' => $merchant->id,
                'name' => $storesData[$index]['name'],
                'logo' => null,
                'banner' => null,
                'description' => $storesData[$index]['description'],
                'commercial_register' => $storesData[$index]['commercial_register'],
                'working_hours' => $storesData[$index]['working_hours'],
                'address' => $storesData[$index]['address'],
                'privacy_policy' => $storesData[$index]['privacy_policy'],
                'return_policy' => $storesData[$index]['return_policy'],
                'status' => 'active',
            ]);

            // إضافة فرع رئيسي
            StoreBranch::create([
                'store_id' => $store->id,
                'name' => 'الفرع الرئيسي',
                'address' => $storesData[$index]['address'],
                'phone' => $merchant->phone,
                'latitude' => 31.5017 + ($index * 0.01),
                'longitude' => 34.4668 + ($index * 0.01),
                'is_main' => true,
            ]);
        }

        $this->command->info('✅ Stores seeded: 3 stores + 3 main branches');
    }
}