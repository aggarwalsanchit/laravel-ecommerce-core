<?php
// database/migrations/2026_04_14_000006_create_product_attribute_values_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('attribute_value_id')->nullable(); // For select/multiselect
            $table->text('value')->nullable(); // For text/number/textarea inputs
            $table->decimal('price_adjustment', 10, 2)->default(0);
            $table->decimal('weight_adjustment', 10, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->onDelete('set null');
            
            $table->unique(['product_id', 'attribute_id', 'attribute_value_id'], 'product_attribute_unique');
            $table->index('product_id');
            $table->index('attribute_id');
            $table->index('attribute_value_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
    }
};