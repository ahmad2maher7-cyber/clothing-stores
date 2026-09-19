<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        $comments = [
            'منتج ممتاز وجودة عالية، أنصح به',
            'جيد جداً لكن المقاس صغير قليلاً',
            'رائع! وصل بسرعة والتغليف احترافي',
            'السعر مناسب للجودة',
            'لم يعجبني اللون، لكن القماش ممتاز',
            'خدمة العملاء ممتازة، شكراً',
            'المنتج مطابق للوصف تماماً',
            'جودة جيدة، سأشتري مرة أخرى',
        ];

        foreach ($products as $product) {
            // 2-4 تقييمات لكل منتج
            $reviewCount = rand(2, 4);
            $reviewers = $customers->random(min($reviewCount, $customers->count()));

            foreach ($reviewers as $customer) {
                Review::create([
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'store_id' => $product->store_id,
                    'rating' => rand(3, 5),
                    'comment' => $comments[array_rand($comments)],
                    'status' => 'approved',
                ]);
            }

            // تحديث متوسط التقييم للمنتج
            $avg = $product->reviews()->avg('rating');
            $product->update(['rating_avg' => round($avg, 2)]);
        }

        $this->command->info('✅ Reviews seeded: ' . Review::count() . ' reviews');
    }
}