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
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->integer('buy_quantity')->nullable();
            $table->integer('get_quantity')->nullable();
            $table->boolean('free_shipping_only')->default(false);

            // Target Type - Including Custom Attributes
            $table->enum('target_type', [
                'all_products',
                'products',
                'categories',
                'subcategories',
                'colors',
                'sizes',
                'custom_attributes',
                'user_groups',
                'min_purchase',
                'first_purchase',
                'holiday_special',
                'clearance'
            ])->default('all_products');

            // Target IDs (JSON for multiple IDs)
            // For custom_attributes, store: {attribute_id: 1, attribute_value_ids: [1,2,3]}
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
            $table->boolean('stackable')->default(false);

            // User Groups
            $table->json('user_groups')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
