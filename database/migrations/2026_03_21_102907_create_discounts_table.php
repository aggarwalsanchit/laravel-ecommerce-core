<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_discounts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();

            // Discount Type
            $table->enum('discount_type', [
                'percentage',      // Percentage off (e.g., 20% off)
                'fixed_amount',    // Fixed amount off (e.g., $50 off)
                'buy_x_get_y',     // Buy X get Y free
                'free_shipping'    // Free shipping
            ])->default('percentage');

            // Discount Value
            $table->decimal('discount_value', 10, 2)->nullable(); // For percentage or fixed amount
            $table->integer('buy_quantity')->nullable(); // For buy X get Y
            $table->integer('get_quantity')->nullable(); // For buy X get Y
            $table->boolean('free_shipping_only')->default(false); // For free shipping

            // Target Type - What the discount applies to
            $table->enum('target_type', [
                'all_products',           // Entire website
                'categories',             // Specific categories
                'subcategories',          // Specific subcategories
                'products',               // Specific products
                'colors',                 // Specific colors
                'sizes',                  // Specific sizes
                'user_groups',            // Specific user groups
                'min_purchase',           // Minimum purchase amount
                'first_purchase',         // First time customers
                'holiday_special',        // Holiday special offers
                'clearance'               // Clearance items
            ])->default('all_products');

            // Target IDs (JSON for multiple IDs)
            $table->json('target_ids')->nullable();

            // Conditions
            $table->decimal('min_purchase_amount', 10, 2)->nullable();
            $table->integer('max_usage_per_user')->nullable();
            $table->integer('total_usage_limit')->nullable();
            $table->integer('used_count')->default(0);

            // Dates
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            // Status
            $table->boolean('status')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('stackable')->default(false); // Can combine with other discounts?

            // User Groups
            $table->json('user_groups')->nullable(); // e.g., ['new', 'vip', 'premium']

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
