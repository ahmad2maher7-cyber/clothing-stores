<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. حذف عمود wallet_credited_at من orders
        if (Schema::hasColumn('orders', 'wallet_credited_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('wallet_credited_at');
            });
        }

        // 2. حذف الجداول
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('withdrawal_requests');
        Schema::dropIfExists('wallets');
    }

    public function down(): void
    {
        // لا نعيد إنشاء الجداول
    }
};