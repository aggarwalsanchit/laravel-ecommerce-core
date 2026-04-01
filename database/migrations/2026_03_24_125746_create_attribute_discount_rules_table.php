<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_attribute_discount_rules_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_discount_rules', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('attribute_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('attribute_value_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');

            // Rule Configuration
            $table->enum('rule_type', [
                'percentage',      // Percentage off
                'fixed_amount',    // Fixed amount off
                'buy_x_get_y',     // Buy X get Y free
                'free_shipping',   // Free shipping
                'bundle_discount'  // Bundle discount
            ]);

            $table->decimal('discount_value', 10, 2)->nullable();
            $table->integer('buy_quantity')->nullable();
            $table->integer('get_quantity')->nullable();

            // Conditions
            $table->boolean('requires_min_quantity')->default(false);
            $table->integer('min_quantity')->nullable();
            $table->boolean('requires_min_purchase')->default(false);
            $table->decimal('min_purchase_amount', 10, 2)->nullable();

            // Stacking
            $table->boolean('stackable')->default(false);
            $table->boolean('exclusive')->default(false);

            // Validity
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);

            $table->boolean('status')->default(true);
            $table->timestamps();

            // Indexes
            $table->index(['attribute_id', 'status']);
            $table->index(['attribute_value_id', 'status']);
            $table->index('discount_id');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_discount_rules');
    }
};
