<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_code', 30)->unique();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('name', 100);
                $table->string('phone', 15);
                $table->string('address', 500);
                $table->string('note', 500)->nullable();
                $table->timestamp('delivery_time')->nullable();
                $table->string('delivery_type', 20)->default('scheduled');
                $table->string('payment_status', 30)->default('unpaid');
                $table->decimal('total_price', 12, 0);
                $table->string('order_status', 30)->default('pending');
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};