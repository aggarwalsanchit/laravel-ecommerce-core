<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_product_sizes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_id')->constrained()->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->decimal('price_adjustment', 10, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['product_id', 'size_id']);
            $table->index('size_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sizes');
    }
};