<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_attribute_analytics_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_analytics_logs', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('attribute_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('attribute_value_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->nullable()->constrained();
            $table->foreignId('order_id')->nullable()->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('session_id')->nullable();

            // Event Type
            $table->enum('event_type', [
                'view',           // Attribute viewed
                'click',          // Attribute clicked
                'filter_use',     // Attribute used in filter
                'search',         // Attribute searched
                'add_to_cart',    // Product with attribute added to cart
                'order',          // Product with attribute ordered
                'discount_applied', // Discount applied on attribute
                'review'          // Review left on product with attribute
            ]);

            // Values
            $table->string('value')->nullable(); // The value selected/used
            $table->integer('quantity')->default(1);
            $table->decimal('price_at_time', 10, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->decimal('revenue', 10, 2)->default(0);

            // Context
            $table->string('page_url')->nullable();
            $table->string('referrer')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['attribute_id', 'event_type']);
            $table->index(['attribute_value_id', 'event_type']);
            $table->index('session_id');
            $table->index('created_at');
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_analytics_logs');
    }
};
