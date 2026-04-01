<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_indexes_to_attribute_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Additional indexes for performance with shorter names
        Schema::table('attributes', function (Blueprint $table) {
            // Use shorter index names
            $table->index(['status', 'is_filterable'], 'attr_status_filter');
            $table->index(['status', 'is_visible_on_product_page'], 'attr_status_visible');
            $table->index(['is_variant', 'status'], 'attr_variant_status');
        });
        
        Schema::table('attribute_values', function (Blueprint $table) {
            $table->index(['is_default', 'display_order'], 'attr_val_default_order');
            $table->index(['attribute_id', 'is_visible'], 'attr_val_attr_visible');
            $table->index(['discount_applicable', 'status'], 'attr_val_discount_status');
        });
        
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->index(['product_id', 'has_discount'], 'prod_attr_discount');
            $table->index(['attribute_id', 'additional_price'], 'prod_attr_price');
        });
        
        Schema::table('attribute_analytics_logs', function (Blueprint $table) {
            // Use much shorter names for this table
            $table->index(['event_type', 'created_at'], 'analytics_event_date');
            $table->index(['attribute_id', 'event_type', 'created_at'], 'analytics_attr_event_date');
        });
    }

    public function down(): void
    {
        // Drop indexes using the shorter names
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropIndex('attr_status_filter');
            $table->dropIndex('attr_status_visible');
            $table->dropIndex('attr_variant_status');
        });
        
        Schema::table('attribute_values', function (Blueprint $table) {
            $table->dropIndex('attr_val_default_order');
            $table->dropIndex('attr_val_attr_visible');
            $table->dropIndex('attr_val_discount_status');
        });
        
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->dropIndex('prod_attr_discount');
            $table->dropIndex('prod_attr_price');
        });
        
        Schema::table('attribute_analytics_logs', function (Blueprint $table) {
            $table->dropIndex('analytics_event_date');
            $table->dropIndex('analytics_attr_event_date');
        });
    }
};