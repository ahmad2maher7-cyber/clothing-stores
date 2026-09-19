<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ============ المشرف ============
        User::create([
            'full_name' => 'المشرف العام',
            'email' => 'admin@store.com',
            'phone' => '0599000001',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // ============ التجار ============
        $merchants = [
            [
                'full_name' => 'أحمد الأناقة',
                'email' => 'ahmed@store.com',
                'phone' => '0599000002',
            ],
            [
                'full_name' => 'سارة الأزياء',
                'email' => 'sara@store.com',
                'phone' => '0599000003',
            ],
            [
                'full_name' => 'خالد الموضة',
                'email' => 'khaled@store.com',
                'phone' => '0599000004',
            ],
        ];

        foreach ($merchants as $merchant) {
            User::create([
                ...$merchant,
                'password' => Hash::make('password'),
                'role' => 'merchant',
                'status' => 'active',
            ]);
        }

        // ============ الزبائن ============
        $customers = [
            ['full_name' => 'محمد العميل', 'email' => 'mohamed@test.com', 'phone' => '0599111111'],
            ['full_name' => 'فاطمة الزهراء', 'email' => 'fatima@test.com', 'phone' => '0599222222'],
            ['full_name' => 'علي الحسن', 'email' => 'ali@test.com', 'phone' => '0599333333'],
            ['full_name' => 'نور الهدى', 'email' => 'noor@test.com', 'phone' => '0599444444'],
            ['full_name' => 'يوسف الكريم', 'email' => 'yousef@test.com', 'phone' => '0599555555'],
        ];

        foreach ($customers as $customer) {
            User::create([
                ...$customer,
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'active',
            ]);
        }

        $this->command->info('✅ Users seeded: 1 admin + 3 merchants + 5 customers');
    }
}