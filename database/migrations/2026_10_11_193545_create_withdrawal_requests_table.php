<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['bank_transfer', 'palpay']);
            $table->string('account_name');
            $table->string('account_number');       // IBAN أو رقم محفظة PalPay
            $table->string('bank_name')->nullable(); // اسم البنك (للتحويل البنكي)
            $table->text('notes')->nullable();        // ملاحظات التاجر
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->text('admin_note')->nullable();   // ملاحظة المشرف (رقم الحوالة مثلاً)
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};