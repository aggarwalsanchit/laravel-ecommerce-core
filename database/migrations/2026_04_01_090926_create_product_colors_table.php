<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_product_colors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('color_id')->constrained()->onDelete('cascade');
            $table->string('color_image')->nullable();
            $table->timestamps();
            
            $table->unique(['product_id', 'color_id']);
            $table->index('color_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_colors');
    }
};