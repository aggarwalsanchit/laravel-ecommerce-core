<?php
// database/migrations/2026_03_21_054103_create_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable(); // Moved here for better organization
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('level')->default(0);
            $table->string('path')->nullable(); // e.g., "1/5/12"
            $table->integer('order')->default(0);

            // Images
            $table->string('image')->nullable(); // Main category image
            $table->string('image_alt')->nullable(); // Alt text for main image
            $table->string('banner_image')->nullable(); // Banner for category page
            $table->string('banner_alt')->nullable(); // Alt text for banner
            $table->string('thumbnail_image')->nullable(); // Thumbnail for listings
            $table->string('thumbnail_alt')->nullable(); // Alt text for thumbnail
            $table->string('icon')->nullable(); // Icon for menu

            // Status & Visibility
            $table->boolean('status')->default(true);
            $table->boolean('show_in_menu')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_trending')->default(false);

            // Vendor Request & Approval System
            $table->enum('approval_status', ['approved', 'pending', 'rejected'])->default('approved');
            $table->unsignedBigInteger('requested_by')->nullable(); // vendor_id
            $table->text('request_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable(); // admin_id
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('requested_at')->nullable();

            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->string('canonical_url')->nullable();

            // Social Media / Open Graph
            $table->string('og_title')->nullable(); // Added
            $table->text('og_description')->nullable(); // Added
            $table->string('og_image')->nullable();

            // Schema Markup (JSON-LD)
            $table->json('schema_markup')->nullable(); // Changed to JSON for better storage

            // Tracking
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('parent_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            $table->foreign('requested_by')
                ->references('id')
                ->on('vendors')
                ->onDelete('set null');

            $table->foreign('approved_by')
                ->references('id')
                ->on('admins')
                ->onDelete('set null');

            // Indexes for performance
            $table->index(['approval_status', 'status']);
            $table->index('parent_id');
            $table->index('level');
            $table->index('path');
            $table->index('is_featured');
            $table->index('is_popular');
            $table->index('order');
        });

        // Create category_requests table for tracking vendor requests
        Schema::create('category_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('requested_name');
            $table->string('requested_slug')->nullable();
            $table->unsignedBigInteger('requested_parent_id')->nullable();
            $table->text('description')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('created_category_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('requested_parent_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('created_category_id')->references('id')->on('categories')->onDelete('set null');

            $table->index('status');
            $table->index('vendor_id');
        });

        Schema::create('category_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->integer('view_count')->default(0);
            $table->integer('product_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('avg_price', 10, 2)->default(0);
            $table->date('date');
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            $table->index(['category_id', 'date']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_requests');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('category_analytics');
    }
};
