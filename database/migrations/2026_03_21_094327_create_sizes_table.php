<?php
// database/migrations/2026_04_13_000003_create_sizes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique(); // e.g., S, M, L, XL, XXL
            $table->string('gender')->nullable(); // e.g., Men, Women, Kids, Unisex

            // Size Measurements (in cm/inches)
            $table->decimal('chest', 8, 2)->nullable();
            $table->decimal('waist', 8, 2)->nullable();
            $table->decimal('hip', 8, 2)->nullable();
            $table->decimal('inseam', 8, 2)->nullable();
            $table->decimal('shoulder', 8, 2)->nullable();
            $table->decimal('sleeve', 8, 2)->nullable();
            $table->decimal('neck', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();

            // International Conversions
            $table->string('us_size')->nullable();
            $table->string('uk_size')->nullable();
            $table->string('eu_size')->nullable();
            $table->string('au_size')->nullable();
            $table->string('jp_size')->nullable();
            $table->string('cn_size')->nullable();
            $table->string('int_size')->nullable();

            // Additional Info
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('icon')->nullable();

            // Status & Visibility
            $table->boolean('status')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->integer('order')->default(0);

            // Vendor Request & Approval System
            $table->enum('approval_status', ['approved', 'pending', 'rejected'])->default('approved');
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->text('request_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('requested_at')->nullable();

            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->string('canonical_url')->nullable();

            // Social Media / Open Graph
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();

            // Usage Tracking
            $table->integer('usage_count')->default(0);
            $table->timestamp('last_used_at')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('requested_by')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');

            // Indexes
            $table->index(['approval_status', 'status']);
            $table->index('code');
            $table->index('gender');
            $table->index('is_featured');
            $table->index('is_popular');
            $table->index('order');
            $table->index('usage_count');
        });

        // Pivot table for size-category relationship (many-to-many)
        Schema::create('category_size', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('size_id');
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->unique(['category_id', 'size_id']);
            $table->index('category_id');
            $table->index('size_id');
        });

        // Create size_requests table
        Schema::create('size_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('requested_name');
            $table->string('requested_slug')->nullable();
            $table->string('requested_code');
            $table->string('requested_gender')->nullable();
            $table->json('requested_category_ids')->nullable(); // Store category IDs as JSON
            $table->decimal('requested_chest', 8, 2)->nullable();
            $table->decimal('requested_waist', 8, 2)->nullable();
            $table->decimal('requested_hip', 8, 2)->nullable();
            $table->decimal('requested_inseam', 8, 2)->nullable();
            $table->decimal('requested_shoulder', 8, 2)->nullable();
            $table->decimal('requested_sleeve', 8, 2)->nullable();
            $table->decimal('requested_neck', 8, 2)->nullable();
            $table->decimal('requested_height', 8, 2)->nullable();
            $table->decimal('requested_weight', 8, 2)->nullable();
            $table->string('requested_us_size')->nullable();
            $table->string('requested_uk_size')->nullable();
            $table->string('requested_eu_size')->nullable();
            $table->string('requested_au_size')->nullable();
            $table->string('requested_jp_size')->nullable();
            $table->string('requested_cn_size')->nullable();
            $table->string('requested_int_size')->nullable();
            $table->text('description')->nullable();
            $table->text('reason')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('created_size_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('created_size_id')->references('id')->on('sizes')->onDelete('set null');

            $table->index('status');
            $table->index('vendor_id');
            $table->index('requested_code');
        });

        // Create size_analytics table
        Schema::create('size_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('size_id');
            $table->integer('view_count')->default(0);
            $table->integer('product_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('avg_price', 10, 2)->default(0);
            $table->date('date');
            $table->timestamps();

            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->index(['size_id', 'date']);
            $table->index('date');
        });

        // Create product_size pivot table
        Schema::create('product_size', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('size_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->decimal('price_adjustment', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');

            $table->unique(['size_id', 'product_id']);
            $table->index('vendor_id');
            $table->index('stock_quantity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_size');
        Schema::dropIfExists('size_analytics');
        Schema::dropIfExists('size_requests');
        Schema::dropIfExists('category_size');
        Schema::dropIfExists('sizes');
    }
};
