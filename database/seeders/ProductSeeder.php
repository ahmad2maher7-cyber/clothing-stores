<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();
        $brands = Brand::all();

        // بيانات المنتجات حسب الجنس
        $productsData = [
            // رجالي
            ['name' => 'قميص قطني كلاسيك', 'gender' => 'men', 'category' => 'قمصان', 'price' => 150],
            ['name' => 'تيشيرت رياضي', 'gender' => 'men', 'category' => 'تيشيرتات', 'price' => 80],
            ['name' => 'بنطال جينز', 'gender' => 'men', 'category' => 'بناطيل', 'price' => 200],
            ['name' => 'جاكيت جلد', 'gender' => 'men', 'category' => 'جاكيتات', 'price' => 450],
            ['name' => 'بدلة رسمية', 'gender' => 'men', 'category' => 'بدلات', 'price' => 700],

            // نسائي
            ['name' => 'فستان سهرة', 'gender' => 'women', 'category' => 'فساتين', 'price' => 500],
            ['name' => 'بلوزة حرير', 'gender' => 'women', 'category' => 'بلوزات', 'price' => 180],
            ['name' => 'تنورة طويلة', 'gender' => 'women', 'category' => 'تنانير', 'price' => 160],
            ['name' => 'عباية مطرزة', 'gender' => 'women', 'category' => 'عبايات', 'price' => 350],
            ['name' => 'جاكيت نسائي شتوي', 'gender' => 'women', 'category' => 'جاكيتات', 'price' => 400],

            // أطفال
            ['name' => 'طقم أولادي', 'gender' => 'kids', 'category' => 'أولادي', 'price' => 120],
            ['name' => 'فستان بناتي', 'gender' => 'kids', 'category' => 'بناتي', 'price' => 140],
            ['name' => 'طقم حديثي الولادة', 'gender' => 'kids', 'category' => 'حديثي الولادة', 'price' => 100],

            // رياضي
            ['name' => 'طقم رياضي كامل', 'gender' => 'unisex', 'category' => 'أطقم رياضية', 'price' => 250],
            ['name' => 'حذاء رياضي', 'gender' => 'unisex', 'category' => 'أحذية رياضية', 'price' => 300],
        ];

        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
        $colors = ['أبيض', 'أسود', 'أزرق', 'أحمر', 'رمادي'];
        $fabrics = ['قطن', 'بوليستر', 'كتان', 'حرير'];

        foreach ($stores as $store) {
            foreach ($productsData as $productData) {
                // البحث عن التصنيف المناسب
                $category = Category::where('store_id', $store->id)
                    ->where('name', $productData['category'])
                    ->first();

                if (!$category) continue;

                // إنشاء المنتج
                $product = Product::create([
                    'store_id' => $store->id,
                    'category_id' => $category->id,
                    'brand_id' => $brands->random()->id,
                    'name' => $productData['name'],
                    'slug' => Str::slug($productData['name']) . '-' . $store->id . '-' . uniqid(),
                    'description' => 'منتج عالي الجودة - ' . $productData['name'] . '. متوفر بمقاسات وألوان متعددة.',
                    'gender' => $productData['gender'],
                    'base_price' => $productData['price'],
                    'discount_price' => rand(0, 1) ? $productData['price'] * 0.85 : null,
                    'currency' => 'ILS',
                    'status' => 'active',
                ]);

                // إضافة صور
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => 'https://via.placeholder.com/600x800?text=' . urlencode($product->name),
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);

                // إنشاء المتغيرات (3-5 متغيرات لكل منتج)
                $variantCount = rand(3, 5);
                $usedCombos = [];

                for ($i = 0; $i < $variantCount; $i++) {
                    do {
                        $size = $sizes[array_rand($sizes)];
                        $color = $colors[array_rand($colors)];
                        $combo = $size . '-' . $color;
                    } while (in_array($combo, $usedCombos));

                    $usedCombos[] = $combo;

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => $color,
                        'fabric_type' => $fabrics[array_rand($fabrics)],
                        'sku' => strtoupper(Str::random(3)) . '-' . $product->id . '-' . $size . '-' . $i,
                        'price' => $productData['price'],
                        'discount_price' => rand(0, 1) ? $productData['price'] * 0.9 : null,
                        'stock_quantity' => rand(5, 50),
                        'low_stock_threshold' => 5,
                        'status' => 'active',
                    ]);
                }
            }
        }

        $this->command->info('✅ Products seeded: ' . Product::count() . ' products, ' . ProductVariant::count() . ' variants');
    }
}