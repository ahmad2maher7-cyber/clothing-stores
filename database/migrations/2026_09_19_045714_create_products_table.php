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
        Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
    $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
    $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->enum('gender', ['men', 'women', 'kids', 'unisex'])->default('unisex');
    $table->decimal('base_price', 10, 2);
    $table->decimal('discount_price', 10, 2)->nullable();
    $table->string('currency', 10)->default('ILS');
    $table->enum('status', ['active', 'draft', 'out_of_stock'])->default('draft');
    $table->decimal('rating_avg', 3, 2)->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
