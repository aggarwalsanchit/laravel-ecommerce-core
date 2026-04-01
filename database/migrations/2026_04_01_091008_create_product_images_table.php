<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_product_images_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->string('alt_text')->nullable();
            $table->timestamps();
            
            $table->index(['product_id', 'is_featured']);
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};