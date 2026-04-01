<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_attribute_values_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('attribute_id')
                  ->constrained()
                  ->onDelete('cascade');
            
            // Value Information
            $table->string('value');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            
            // For Color Attributes
            $table->string('color_code', 7)->nullable(); // Hex color code #RRGGBB
            $table->string('color_name')->nullable();
            
            // For Size Attributes
            $table->string('size_value')->nullable(); // Numeric value for sorting
            $table->string('size_unit')->nullable(); // cm, inches, etc.
            
            // Image Support
            $table->string('image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('icon')->nullable();
            
            // Variant Support
            $table->decimal('price_adjustment', 10, 2)->default(0);
            $table->decimal('weight_adjustment', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            
            // Range Support
            $table->decimal('min_value', 10, 2)->nullable();
            $table->decimal('max_value', 10, 2)->nullable();
            
            // Display Settings
            $table->integer('display_order')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_visible')->default(true);
            
            // Discount Support
            $table->boolean('discount_applicable')->default(true);
            $table->decimal('max_discount_percentage', 5, 2)->nullable(); // Max discount allowed for this value
            
            // Analytics
            $table->integer('view_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            $table->integer('usage_count')->default(0); // How many products use this value
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            $table->timestamps();
            $table->boolean('status')->default(true);
            // Indexes
            $table->index('attribute_id');
            $table->index('value');
            $table->index('display_order');
            $table->index('is_default');
            $table->index('usage_count');
            $table->index('view_count');
            $table->index('order_count');
            $table->index('total_revenue');
            
            // Unique constraint for attribute_id and value
            $table->unique(['attribute_id', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};