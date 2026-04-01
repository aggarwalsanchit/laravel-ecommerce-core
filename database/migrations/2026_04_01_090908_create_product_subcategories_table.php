<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_product_subcategories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['product_id', 'category_id']);
            $table->index(['category_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_subcategories');
    }
};