<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_attributes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->nullable()->unique();
            $table->text('description')->nullable();
            
            // Attribute Type Configuration
            $table->enum('type', [
                'text',          // Simple text input
                'textarea',      // Multi-line text
                'number',        // Numeric value
                'select',        // Single select dropdown
                'multiselect',   // Multiple select dropdown
                'color',         // Color picker with preview
                'size',          // Size selector (S, M, L, XL)
                'checkbox',      // Single checkbox
                'radio',         // Radio buttons
                'date',          // Date picker
                'datetime',      // Date and time picker
                'boolean',       // Yes/No toggle
                'range'          // Min/Max range
            ])->default('text');
            
            $table->string('unit')->nullable(); // e.g., GB, MHz, cm, kg
            
            // Display Settings
            $table->integer('display_order')->default(0);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_filterable')->default(true);
            $table->boolean('is_visible_on_product_page')->default(true);
            $table->boolean('is_visible_on_shop_page')->default(true);
            $table->boolean('show_in_search')->default(true);
            
            // Variant Support
            $table->boolean('is_variant')->default(false);
            $table->boolean('affects_price')->default(false);
            $table->boolean('affects_stock')->default(false);
            $table->boolean('affects_weight')->default(false);
            
            // Image & Media
            $table->boolean('has_image')->default(false);
            $table->boolean('has_thumbnail')->default(false);
            $table->string('placeholder_image')->nullable();
            $table->string('icon')->nullable();
            
            // Discount Support
            $table->boolean('discount_applicable')->default(true);
            $table->boolean('can_be_used_in_bogo')->default(true);
            
            // Analytics
            $table->boolean('track_analytics')->default(true);
            $table->boolean('track_views')->default(true);
            $table->boolean('track_clicks')->default(true);
            $table->boolean('track_conversion')->default(true);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Organization
            $table->foreignId('attribute_group_id')->nullable()->constrained('attribute_groups')->onDelete('set null');
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            
            // Status
            $table->boolean('status')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            
            // Source Information (for migrated data)
            $table->enum('source', ['core', 'dynamic'])->default('dynamic');
            $table->string('source_table')->nullable();
            $table->string('source_model')->nullable();
            
            // Statistics
            $table->integer('total_products')->default(0);
            $table->integer('total_views')->default(0);
            $table->integer('total_clicks')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index('type');
            $table->index('status');
            $table->index('display_order');
            $table->index('is_filterable');
            $table->index('source');
            $table->index('total_products');
            $table->index('total_revenue');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};