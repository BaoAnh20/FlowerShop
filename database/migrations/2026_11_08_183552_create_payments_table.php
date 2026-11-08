<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
                $table->string('transaction_code', 100)->nullable()->unique(); //[cite: 1]
                $table->decimal('amount', 12, 0); //[cite: 1]
                $table->string('payment_status', 20)->default('pending'); //[cite: 1]
                $table->timestamp('paid_at')->nullable(); //[cite: 1]
                $table->timestamps(); //[cite: 1]
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('payments');
    }
};