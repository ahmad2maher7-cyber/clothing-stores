<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Nike', 'description' => 'ماركة رياضية عالمية'],
            ['name' => 'Adidas', 'description' => 'ماركة رياضية ألمانية'],
            ['name' => 'Zara', 'description' => 'أزياء إسبانية عصرية'],
            ['name' => 'H&M', 'description' => 'أزياء سويدية بأسعار مناسبة'],
            ['name' => 'Levi\'s', 'description' => 'ماركة بناطيل جينز أمريكية'],
            ['name' => 'Puma', 'description' => 'ماركة رياضية'],
            ['name' => 'Gucci', 'description' => 'ماركة فاخرة إيطالية'],
            ['name' => 'Mango', 'description' => 'أزياء نسائية إسبانية'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        $this->command->info('✅ Brands seeded: ' . Brand::count() . ' brands');
    }
}