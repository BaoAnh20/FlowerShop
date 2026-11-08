<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->string('product_name', 100);
                $table->string('image', 255)->nullable();
                $table->decimal('price', 12, 0);
                $table->integer('quantity');
                $table->decimal('subtotal', 12, 0);
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};