<?php
// database/migrations/2026_04_13_000001_create_colors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique(); // Hex color code e.g., #FF0000
            $table->string('rgb')->nullable(); // RGB value e.g., rgb(255,0,0)
            $table->string('hsl')->nullable(); // HSL value e.g., hsl(0,100%,50%)

            // Color Details
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // Color swatch image
            $table->string('image_alt')->nullable();

            // Status & Visibility
            $table->boolean('status')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->integer('order')->default(0);

            // Vendor Request & Approval System
            $table->enum('approval_status', ['approved', 'pending', 'rejected'])->default('approved');
            $table->unsignedBigInteger('requested_by')->nullable(); // vendor_id who requested
            $table->text('request_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable(); // admin_id who approved
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('requested_at')->nullable();

            // SEO Fields (for color pages if needed)
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->string('canonical_url')->nullable();

            // Social Media / Open Graph
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();

            // NO ANALYTICS FIELDS HERE - They belong in color_analytics table

            // Usage Tracking (Basic counters that can be updated in real-time)
            $table->integer('usage_count')->default(0); // How many products use this color
            $table->timestamp('last_used_at')->nullable();

            // Timestamps
            $table->timestamps();

            // Foreign Keys
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
            $table->index('code');
            $table->index('is_featured');
            $table->index('is_popular');
            $table->index('order');
            $table->index('usage_count');
        });

        // Create color_requests table for tracking vendor requests
        Schema::create('color_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('requested_name');
            $table->string('requested_slug')->nullable();
            $table->string('requested_code');
            $table->string('requested_rgb')->nullable();
            $table->string('requested_hsl')->nullable();
            $table->text('description')->nullable();
            $table->text('reason')->nullable();
            $table->string('image')->nullable(); // Sample image
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('created_color_id')->nullable(); // After approval
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('created_color_id')->references('id')->on('colors')->onDelete('set null');

            $table->index('status');
            $table->index('vendor_id');
            $table->index('requested_code');
        });

        // Create color_analytics table for ALL analytics data (daily tracking)
        Schema::create('color_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('color_id');

            // Analytics Fields
            $table->integer('view_count')->default(0);
            $table->integer('product_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('avg_price', 10, 2)->default(0);

            // Date for daily/weekly/monthly aggregation
            $table->date('date');
            $table->timestamps();

            $table->foreign('color_id')
                ->references('id')
                ->on('colors')
                ->onDelete('cascade');

            $table->index(['color_id', 'date']);
            $table->index('date');
        });

        // Create color_product pivot table
        Schema::create('color_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('color_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('color_image')->nullable(); // Product specific color image
            $table->integer('stock_quantity')->default(0);
            $table->decimal('price_adjustment', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');

            $table->unique(['color_id', 'product_id']);
            $table->index('vendor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('color_product');
        Schema::dropIfExists('color_analytics');
        Schema::dropIfExists('color_requests');
        Schema::dropIfExists('colors');
    }
};
