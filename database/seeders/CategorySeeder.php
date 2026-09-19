<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // التصنيفات العامة لكل متجر
        $generalCategories = [
            // رجالي
            ['name' => 'ملابس رجالية', 'children' => ['قمصان', 'بناطيل', 'تيشيرتات', 'جاكيتات', 'بدلات']],
            // نسائي
            ['name' => 'ملابس نسائية', 'children' => ['فساتين', 'بلوزات', 'تنانير', 'عبايات', 'جاكيتات']],
            // أطفال
            ['name' => 'ملابس أطفال', 'children' => ['أولادي', 'بناتي', 'حديثي الولادة']],
            // رياضية
            ['name' => 'ملابس رياضية', 'children' => ['أطقم رياضية', 'أحذية رياضية', 'إكسسوارات رياضية']],
        ];

        $stores = Store::all();

        foreach ($stores as $store) {
            foreach ($generalCategories as $catData) {
                // التصنيف الرئيسي
                $parent = Category::create([
                    'store_id' => $store->id,
                    'name' => $catData['name'],
                    'slug' => Str::slug($catData['name']) . '-' . $store->id,
                    'status' => 'active',
                    'sort_order' => 0,
                ]);

                // التصنيفات الفرعية
                foreach ($catData['children'] as $childName) {
                    Category::create([
                        'store_id' => $store->id,
                        'parent_id' => $parent->id,
                        'name' => $childName,
                        'slug' => Str::slug($childName) . '-' . $store->id . '-' . uniqid(),
                        'status' => 'active',
                        'sort_order' => 0,
                    ]);
                }
            }
        }

        $this->command->info('✅ Categories seeded: ' . Category::count() . ' categories');
    }
}