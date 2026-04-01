<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_product_variants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('color_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('size_id')->nullable()->constrained()->onDelete('set null');
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->json('custom_attributes')->nullable(); // For dynamic attribute combinations
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->unique(['product_id', 'color_id', 'size_id'], 'product_variant_unique');
            $table->index(['product_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};