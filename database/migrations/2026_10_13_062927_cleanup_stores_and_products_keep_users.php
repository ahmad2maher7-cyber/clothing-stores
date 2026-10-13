<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        $tables = [
            'review_images', 'reviews', 'wishlists', 'order_status_history', 'shipments',
            'order_items', 'orders', 'cart_items', 'carts', 'inventory_logs',
            'product_variants', 'product_images', 'products', 'coupons', 'offers',
            'categories', 'brands', 'store_shipping_companies', 'store_payment_methods',
            'store_branches', 'shipping_zones', 'chat_messages', 'chat_conversations',
            'contact_messages', 'stores'
        ];

        if ($driver === 'pgsql') {
            // ✅ PostgreSQL بدون superuser: TRUNCATE ... CASCADE
            $existing = array_filter($tables, fn($t) => Schema::hasTable($t));
            
            if (!empty($existing)) {
                $quoted = implode(', ', array_map(fn($t) => "\"{$t}\"", $existing));
                DB::statement("TRUNCATE TABLE {$quoted} CASCADE");
                echo "✅ PostgreSQL: TRUNCATE CASCADE على " . count($existing) . " جدول\n";
            }
        } else {
            // ✅ MySQL: تعطيل FK checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    try {
                        $count = DB::table($table)->count();
                        DB::table($table)->truncate();
                        echo "✅ {$table}: حذف {$count} سجل\n";
                    } catch (\Throwable $e) {
                        echo "⚠️ {$table}: {$e->getMessage()}\n";
                    }
                }
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down(): void
    {
        // لا رجعة — البيانات محذوفة نهائياً
    }
};