<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_product_analytics_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_analytics_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->enum('event_type', ['view', 'click', 'add_to_cart', 'remove_from_cart', 'order']);
            $table->integer('quantity')->default(1);
            $table->decimal('price_at_time', 10, 2)->nullable();
            $table->timestamps();
            
            $table->index(['product_id', 'event_type']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_analytics_logs');
    }
};