<?php
// database/migrations/2026_04_15_000007_create_product_variants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('color_id')->nullable();
            $table->unsignedBigInteger('size_id')->nullable();
            $table->string('sku')->unique();
            $table->decimal('price', 12, 2)->nullable(); // overrides product price
            $table->decimal('compare_price', 12, 2)->nullable();
            $table->decimal('wholesale_price', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->string('stock_status')->default('instock');
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('set null');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('set null');
            
            $table->index('product_id');
            $table->index('sku');
            $table->unique(['product_id', 'color_id', 'size_id'], 'product_variant_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};