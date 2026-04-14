<?php
// database/migrations/2026_04_14_000004_create_attribute_category_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('group_id')->nullable(); // Attribute group within category
            $table->integer('order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('attribute_groups')->onDelete('set null');
            
            $table->unique(['attribute_id', 'category_id']);
            $table->index('category_id');
            $table->index('group_id');
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_category');
    }
};