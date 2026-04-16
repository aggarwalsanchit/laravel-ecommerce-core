<?php
// database/migrations/2026_04_15_000014_create_product_daily_analytics_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_daily_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->date('date');
            
            // Core metrics
            $table->integer('views')->default(0);
            $table->integer('cart_adds')->default(0);
            $table->integer('cart_removes')->default(0);
            $table->integer('orders')->default(0);
            $table->integer('quantity_sold')->default(0);
            $table->decimal('revenue', 12, 2)->default(0);
            $table->decimal('avg_price_sold', 10, 2)->default(0);
            
            // Engagement
            $table->integer('wishlist_adds')->default(0);
            $table->integer('wishlist_removes')->default(0);
            $table->integer('share_count')->default(0);
            $table->integer('click_count')->default(0);
            
            // Ratings
            $table->integer('new_ratings')->default(0);
            $table->decimal('avg_rating_daily', 3, 2)->default(0);
            
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique(['product_id', 'date']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_daily_analytics');
    }
};