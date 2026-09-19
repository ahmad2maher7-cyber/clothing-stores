<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('order_number')->unique();
    $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
    $table->foreignId('shipping_zone_id')->nullable()->constrained('shipping_zones')->onDelete('set null');
    $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null');
    $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
    $table->decimal('subtotal', 10, 2);
    $table->decimal('discount', 10, 2)->default(0);
    $table->decimal('shipping_cost', 10, 2)->default(0);
    $table->decimal('total', 10, 2);
    $table->enum('status', ['pending', 'processing', 'shipped', 'delivering', 'delivered', 'cancelled', 'returned'])->default('pending');
    $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
    $table->text('shipping_address');
    $table->text('notes')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
