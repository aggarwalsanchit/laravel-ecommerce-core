<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_attribute_groups_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable(); // For group color coding
            $table->integer('display_order')->default(0);
            $table->boolean('is_collapsible')->default(true);
            $table->boolean('is_collapsed_by_default')->default(false);
            $table->boolean('show_in_sidebar')->default(true);
            $table->boolean('show_in_compare')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_groups');
    }
};