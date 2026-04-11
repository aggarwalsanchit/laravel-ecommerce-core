<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_vendor_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('parent_category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->foreignId('grandparent_category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->integer('level')->default(1); // 1, 2, or 3
            $table->timestamps();

            $table->index(['shop_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_categories');
    }
};
