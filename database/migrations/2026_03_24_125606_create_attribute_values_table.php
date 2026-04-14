<?php
// database/migrations/2026_04_14_000003_create_attribute_values_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->unsignedBigInteger('attribute_id');
            $table->string('value');
            $table->string('label')->nullable();
            $table->string('color_code')->nullable(); // For color attributes
            $table->string('image')->nullable(); // For image attributes
            $table->string('image_alt')->nullable();
            $table->integer('order')->default(0);
            
            // Additional Info
            $table->text('description')->nullable();
            $table->decimal('price_adjustment', 10, 2)->default(0); // Price adjustment for this value
            $table->decimal('weight_adjustment', 10, 2)->default(0); // Weight adjustment
            
            // Status
            $table->boolean('status')->default(true);
            $table->boolean('is_default')->default(false);
            
            // Timestamps
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            
            // Indexes
            $table->index('attribute_id');
            $table->index('order');
            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};