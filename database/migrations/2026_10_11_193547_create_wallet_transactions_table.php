<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->foreignId('withdrawal_request_id')->nullable()->constrained('withdrawal_requests')->onDelete('set null');
            $table->enum('type', ['earning', 'withdrawal', 'refund', 'adjustment']);
            $table->decimal('amount', 12, 2);           // موجب للإيداع، سالب للسحب
            $table->decimal('commission', 12, 2)->default(0); // عمولة المنصة
            $table->string('description');
            $table->timestamps();

            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};