<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\ShippingCompany;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreRelationsSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();
        $companies = ShippingCompany::all();
        $methods = PaymentMethod::all();

        foreach ($stores as $store) {
            // ربط المتجر بجميع شركات الشحن
            foreach ($companies as $company) {
                $store->shippingCompanies()->attach($company->id, [
                    'api_key' => 'API_KEY_' . strtoupper(uniqid()),
                ]);
            }

            // ربط المتجر بجميع طرق الدفع
            foreach ($methods as $method) {
                $store->paymentMethods()->attach($method->id, [
                    'config' => json_encode(['enabled' => true]),
                ]);
            }
        }

        $this->command->info('✅ Store relations seeded');
    }
}