<?php
// database/migrations/2026_04_14_000001_create_attribute_groups_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            
            // Display Settings
            $table->boolean('is_collapsible')->default(true);
            $table->boolean('is_open_by_default')->default(true);
            $table->string('icon')->nullable();
            $table->string('position')->default('top'); // top, sidebar, bottom
            
            // Status
            $table->boolean('status')->default(true);
            
            // Vendor Request & Approval System
            $table->enum('approval_status', ['approved', 'pending', 'rejected'])->default('approved');
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->text('request_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('requested_at')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('requested_by')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');
            
            // Indexes
            $table->index(['approval_status', 'status']);
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_groups');
    }
};