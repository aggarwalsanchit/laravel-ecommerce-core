<?php
// database/migrations/2026_04_14_000007_create_attribute_analytics_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('attribute_value_id')->nullable();
            $table->integer('usage_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->integer('product_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('avg_price', 10, 2)->default(0);
            $table->date('date');
            $table->timestamps();
            
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->onDelete('cascade');
            
            $table->index(['attribute_id', 'date']);
            $table->index(['attribute_id', 'attribute_value_id', 'date']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_analytics');
    }
};