<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->unique()->constrained('stores')->onDelete('cascade');
            $table->decimal('balance', 12, 2)->default(0);          // الرصيد الحالي
            $table->decimal('total_earned', 12, 2)->default(0);     // إجمالي الأرباح
            $table->decimal('total_withdrawn', 12, 2)->default(0);  // إجمالي المسحوبات
            $table->decimal('pending_withdrawal', 12, 2)->default(0); // معلق (قيد المراجعة)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};