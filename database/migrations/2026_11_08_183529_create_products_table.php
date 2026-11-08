<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
                $table->string('name', 100);
                $table->string('image', 255);
                $table->text('details');
                $table->decimal('price', 12, 0);
                $table->decimal('sale_price', 12, 0)->nullable();
                $table->integer('stock_quantity')->default(0);
                $table->string('style', 100)->nullable();
                $table->string('primary_flower', 100)->nullable();
                $table->string('occasion', 100)->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_featured')->default(false);
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('products');
    }
};