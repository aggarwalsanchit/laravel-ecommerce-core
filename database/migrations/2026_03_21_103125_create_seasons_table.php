<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_seasons_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('is_current')->default(false);
            $table->integer('product_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
