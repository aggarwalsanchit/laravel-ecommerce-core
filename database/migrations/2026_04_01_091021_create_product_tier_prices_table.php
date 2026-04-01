<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_product_tier_prices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_tier_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('min_quantity');
            $table->integer('max_quantity')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps();
            
            $table->index(['product_id', 'min_quantity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_tier_prices');
    }
};