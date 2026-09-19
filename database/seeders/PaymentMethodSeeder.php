<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'الدفع عند الاستلام', 'code' => 'cod', 'is_active' => true],
            ['name' => 'البطاقات البنكية', 'code' => 'card', 'is_active' => true],
            ['name' => 'المحافظ الإلكترونية', 'code' => 'wallet', 'is_active' => true],
            ['name' => 'التقسيط', 'code' => 'installments', 'is_active' => true],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }

        $this->command->info('✅ Payment methods seeded: ' . PaymentMethod::count());
    }
}