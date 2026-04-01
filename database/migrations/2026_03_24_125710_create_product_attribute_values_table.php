<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_product_attribute_values_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('product_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('attribute_value_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('attribute_id')->nullable()->constrained(); // Denormalized for faster queries

            // Product-specific adjustments
            $table->decimal('additional_price', 10, 2)->default(0);
            $table->decimal('additional_weight', 10, 2)->default(0);
            $table->integer('additional_stock')->default(0);
            $table->string('custom_sku')->nullable();
            $table->string('custom_barcode')->nullable();

            // Discount tracking
            $table->boolean('has_discount')->default(false);
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('discounted_price', 10, 2)->nullable();

            // Analytics
            $table->integer('view_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->decimal('revenue_generated', 10, 2)->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['product_id', 'attribute_value_id']);
            $table->index('attribute_id');
            $table->index('has_discount');
            $table->unique(['product_id', 'attribute_value_id'], 'product_attribute_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
    }
};
