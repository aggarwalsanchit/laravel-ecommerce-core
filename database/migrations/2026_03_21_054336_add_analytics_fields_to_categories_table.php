<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_analytics_fields_to_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // SEO Fields
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords');
            $table->string('schema_markup')->nullable()->after('og_image');

            // Analytics Fields
            $table->integer('view_count')->default(0)->after('schema_markup');
            $table->integer('product_count')->default(0)->after('view_count');
            $table->integer('order_count')->default(0)->after('product_count');
            $table->decimal('total_revenue', 10, 2)->default(0)->after('order_count');
            $table->decimal('avg_price', 10, 2)->default(0)->after('total_revenue');

            // Featured & Popularity
            $table->boolean('is_featured')->default(false)->after('avg_price');
            $table->boolean('is_popular')->default(false)->after('is_featured');
            $table->boolean('is_trending')->default(false)->after('is_popular');

            // Display Settings
            $table->string('banner_image')->nullable()->after('is_trending');
            $table->string('thumbnail_image')->nullable()->after('banner_image');
            $table->text('short_description')->nullable()->after('thumbnail_image');

            // SEO URL
            $table->string('canonical_url')->nullable()->after('short_description');
            $table->string('focus_keyword')->nullable()->after('canonical_url');

            // Timestamps for tracking
            $table->timestamp('last_viewed_at')->nullable()->after('focus_keyword');
            $table->timestamp('last_updated_at')->nullable()->after('last_viewed_at');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description',
                'meta_keywords',
                'og_image',
                'schema_markup',
                'view_count',
                'product_count',
                'order_count',
                'total_revenue',
                'avg_price',
                'is_featured',
                'is_popular',
                'is_trending',
                'banner_image',
                'thumbnail_image',
                'short_description',
                'canonical_url',
                'focus_keyword',
                'last_viewed_at',
                'last_updated_at'
            ]);
        });
    }
};
