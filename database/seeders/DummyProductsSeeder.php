<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class DummyProductsSeeder extends Seeder
{
    public function run(): void
    {
        // ============ 1. التأكد من وجود تاجر ومتجر ============
        $merchant = User::where('role', 'merchant')->first();

        if (!$merchant) {
            $merchant = User::create([
                'full_name' => 'أحمد التاجر',
                'email'     => 'ahmed@store.com',
                'phone'     => '0599000000',
                'password'  => bcrypt('password'),
                'role'      => 'merchant',
                'status'    => 'active',
            ]);
        }

        $store = $merchant->stores()->first();

        if (!$store) {
            $store = Store::create([
                'merchant_id' => $merchant->id,
                'name'        => 'متجر الأناقة',
                'description' => 'أفضل الملابس العصرية',
                'status'      => 'active',
            ]);
        }

        // ============ 2. إنشاء الماركات ============
        $brands = [];
        foreach (['Zara', 'H&M', 'Nike', 'Adidas'] as $brandName) {
            $brands[$brandName] = Brand::firstOrCreate(
                ['name' => $brandName],
                ['description' => "ماركة {$brandName} العالمية"]
            );
        }

        // ============ 3. إنشاء التصنيفات ============
        $categoriesData = [
            'رجالي' => ['قمصان', 'تيشيرتات', 'بناطيل', 'جاكيتات'],
            'نسائي' => ['فساتين', 'بلوزات', 'تنانير', 'بناطيل'],
            'أطفال' => ['تيشيرتات', 'بناطيل'],
        ];

        $categories = [];
        foreach ($categoriesData as $parentName => $children) {
            $parent = Category::firstOrCreate(
                ['store_id' => $store->id, 'name' => $parentName],
                [
                    'slug'   => Str::slug($parentName) . '-' . $store->id,
                    'status' => 'active',
                ]
            );
            $categories[$parentName] = $parent;

            foreach ($children as $childName) {
                $categories[$parentName . '/' . $childName] = Category::firstOrCreate(
                    ['store_id' => $store->id, 'name' => $childName],
                    [
                        'parent_id' => $parent->id,
                        'slug'      => Str::slug($parentName . '-' . $childName) . '-' . $store->id,
                        'status'    => 'active',
                    ]
                );
            }
        }

        // ============ 4. المنتجات ============
        $products = [
            // ---------- رجالي ----------
            [
                'name'        => 'قميص قطني كلاسيك',
                'gender'      => 'men',
                'category'    => 'رجالي/قمصان',
                'brand'       => 'Zara',
                'base_price'  => 149.00,
                'discount'    => 119.00,
                'description' => 'قميص قطني 100% بتصميم كلاسيكي أنيق مناسب لكل المناسبات.',
                'images'      => ['men-shirt-1.jpg', 'men-shirt-2.jpg', 'men-shirt-3.jpg'],
                'variants'    => [
                    ['size' => 'S',  'color' => 'أبيض'],
                    ['size' => 'M',  'color' => 'أبيض'],
                    ['size' => 'L',  'color' => 'أزرق'],
                    ['size' => 'XL', 'color' => 'أزرق'],
                ],
            ],
            [
                'name'        => 'تيشيرت أساسي',
                'gender'      => 'men',
                'category'    => 'رجالي/تيشيرتات',
                'brand'       => 'H&M',
                'base_price'  => 79.00,
                'discount'    => 59.00,
                'description' => 'تيشيرت قطني مريح للاستخدام اليومي، متوفر بعدة ألوان.',
                'images'      => ['men-tshirt-1.jpg', 'men-tshirt-2.jpg'],
                'variants'    => [
                    ['size' => 'S',  'color' => 'أسود'],
                    ['size' => 'M',  'color' => 'أسود'],
                    ['size' => 'L',  'color' => 'رمادي'],
                    ['size' => 'XL', 'color' => 'رمادي'],
                    ['size' => 'XXL','color' => 'أبيض'],
                ],
            ],
            [
                'name'        => 'بنطال جينز',
                'gender'      => 'men',
                'category'    => 'رجالي/بناطيل',
                'brand'       => 'Nike',
                'base_price'  => 199.00,
                'discount'    => null,
                'description' => 'بنطال جينز عالي الجودة، تصميم عملي وأنيق.',
                'images'      => ['men-jeans-1.jpg', 'men-jeans-2.jpg'],
                'variants'    => [
                    ['size' => 'M',  'color' => 'أزرق داكن'],
                    ['size' => 'L',  'color' => 'أزرق داكن'],
                    ['size' => 'XL', 'color' => 'أسود'],
                ],
            ],
            [
                'name'        => 'جاكيت شتوي',
                'gender'      => 'men',
                'category'    => 'رجالي/جاكيتات',
                'brand'       => 'Adidas',
                'base_price'  => 349.00,
                'discount'    => 279.00,
                'description' => 'جاكيت شتوي دافئ ومقاوم للماء، مثالي لفصل الشتاء.',
                'images'      => ['men-jacket-1.jpg', 'men-jacket-2.jpg'],
                'variants'    => [
                    ['size' => 'M',  'color' => 'أسود'],
                    ['size' => 'L',  'color' => 'أسود'],
                    ['size' => 'XL', 'color' => 'رمادي'],
                ],
            ],

            // ---------- نسائي ----------
            [
                'name'        => 'فستان صيفي',
                'gender'      => 'women',
                'category'    => 'نسائي/فساتين',
                'brand'       => 'Zara',
                'base_price'  => 259.00,
                'discount'    => 199.00,
                'description' => 'فستان صيفي خفيف بتصميم أنيق، مثالي للمناسبات الصيفية.',
                'images'      => ['women-dress-1.jpg', 'women-dress-2.jpg', 'women-dress-3.jpg'],
                'variants'    => [
                    ['size' => 'S',  'color' => 'وردي'],
                    ['size' => 'M',  'color' => 'وردي'],
                    ['size' => 'L',  'color' => 'أزرق فاتح'],
                    ['size' => 'XL', 'color' => 'أزرق فاتح'],
                ],
            ],
            [
                'name'        => 'بلوزة حريرية',
                'gender'      => 'women',
                'category'    => 'نسائي/بلوزات',
                'brand'       => 'H&M',
                'base_price'  => 159.00,
                'discount'    => null,
                'description' => 'بلوزة حريرية فاخرة بتفاصيل دقيقة، تناسب الإطلالات الرسمية.',
                'images'      => ['women-blouse-1.jpg', 'women-blouse-2.jpg'],
                'variants'    => [
                    ['size' => 'S',  'color' => 'بيج'],
                    ['size' => 'M',  'color' => 'بيج'],
                    ['size' => 'L',  'color' => 'أسود'],
                ],
            ],
            [
                'name'        => 'تنورة بليسيه',
                'gender'      => 'women',
                'category'    => 'نسائي/تنانير',
                'brand'       => 'Zara',
                'base_price'  => 189.00,
                'discount'    => 149.00,
                'description' => 'تنورة بليسيه عصرية بتصميم انسيابي، مناسبة للعمل والخروج.',
                'images'      => ['women-skirt-1.jpg', 'women-skirt-2.jpg'],
                'variants'    => [
                    ['size' => 'S',  'color' => 'بني'],
                    ['size' => 'M',  'color' => 'بني'],
                    ['size' => 'L',  'color' => 'أسود'],
                ],
            ],
            [
                'name'        => 'جينز نسائي ضيق',
                'gender'      => 'women',
                'category'    => 'نسائي/بناطيل',
                'brand'       => 'Nike',
                'base_price'  => 179.00,
                'discount'    => null,
                'description' => 'بنطال جينز نسائي بقصة ضيقة ومريحة، متوفر بألوان متعددة.',
                'images'      => ['women-jeans-1.jpg', 'women-jeans-2.jpg'],
                'variants'    => [
                    ['size' => 'S',  'color' => 'أزرق'],
                    ['size' => 'M',  'color' => 'أزرق'],
                    ['size' => 'L',  'color' => 'أسود'],
                    ['size' => 'XL', 'color' => 'أسود'],
                ],
            ],

            // ---------- أطفال ----------
            [
                'name'        => 'تيشيرت أطفال ملون',
                'gender'      => 'kids',
                'category'    => 'أطفال/تيشيرتات',
                'brand'       => 'H&M',
                'base_price'  => 49.00,
                'discount'    => 39.00,
                'description' => 'تيشيرت أطفال قطني ناعم بألوان مبهجة، مريح للعب اليومي.',
                'images'      => ['kids-tshirt-1.jpg', 'kids-tshirt-2.jpg'],
                'variants'    => [
                    ['size' => 'S', 'color' => 'أحمر'],
                    ['size' => 'M', 'color' => 'أزرق'],
                    ['size' => 'L', 'color' => 'أصفر'],
                ],
            ],
            [
                'name'        => 'بنطال أطفال',
                'gender'      => 'kids',
                'category'    => 'أطفال/بناطيل',
                'brand'       => 'Adidas',
                'base_price'  => 69.00,
                'discount'    => null,
                'description' => 'بنطال أطفال عملي ومريح، مصنوع من قطن عالي الجودة.',
                'images'      => ['kids-pants-1.jpg', 'kids-pants-2.jpg'],
                'variants'    => [
                    ['size' => 'S', 'color' => 'رمادي'],
                    ['size' => 'M', 'color' => 'رمادي'],
                    ['size' => 'L', 'color' => 'أسود'],
                ],
            ],
        ];

        // ============ 5. إنشاء المنتجات ============
        foreach ($products as $index => $data) {
            // حذف القديم (لتفادي التكرار عند إعادة التشغيل)
            $slug = Str::slug($data['name']) . '-' . $store->id;
            Product::where('slug', $slug)->delete();

            $product = Product::create([
                'store_id'       => $store->id,
                'category_id'    => $categories[$data['category']]->id ?? null,
                'brand_id'       => $brands[$data['brand']]->id ?? null,
                'name'           => $data['name'],
                'slug'           => $slug,
                'description'    => $data['description'],
                'gender'         => $data['gender'],
                'base_price'     => $data['base_price'],
                'discount_price' => $data['discount'],
                'currency'       => 'ILS',
                'status'         => 'active',
                'rating_avg'     => 0,
            ]);

            // --- الصور ---
            foreach ($data['images'] as $i => $filename) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url'  => '/images/products/' . $filename,
                    'is_primary' => $i === 0,
                    'sort_order' => $i,
                ]);
            }

            // --- المتغيرات ---
            foreach ($data['variants'] as $j => $variant) {
                ProductVariant::create([
                    'product_id'           => $product->id,
                    'size'                 => $variant['size'],
                    'color'                => $variant['color'],
                    'fabric_type'          => 'قطن',
                    'sku'                  => 'SKU-' . $product->id . '-' . $variant['size'] . '-' . $j,
                    'price'                => $data['base_price'],
                    'discount_price'       => $data['discount'],
                    'stock_quantity'       => rand(10, 50),
                    'low_stock_threshold'  => 5,
                    'status'               => 'active',
                ]);
            }

            $this->command->info("✅ منتج: {$data['name']}");
        }

        $this->command->info('');
        $this->command->info('🎉 تم إنشاء ' . count($products) . ' منتجاً بنجاح!');
        $this->command->info('📸 الصور مأخوذة من: /images/products/');
    }
}