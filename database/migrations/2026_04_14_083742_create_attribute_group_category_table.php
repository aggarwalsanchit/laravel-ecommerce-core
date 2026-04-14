<?php
// database/migrations/2026_04_14_000005_create_attribute_group_category_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_group_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_group_id');
            $table->unsignedBigInteger('category_id');
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->foreign('attribute_group_id')->references('id')->on('attribute_groups')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            
            $table->unique(['attribute_group_id', 'category_id']);
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_group_category');
    }
};