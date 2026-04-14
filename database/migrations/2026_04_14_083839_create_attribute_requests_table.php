<?php
// database/migrations/2026_04_14_000008_create_attribute_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('requested_name');
            $table->string('requested_slug')->nullable();
            $table->enum('requested_type', [
                'text', 'textarea', 'number', 'decimal', 'select', 'multiselect', 
                'checkbox', 'radio', 'date', 'datetime', 'color', 'image', 'file', 'url', 'email', 'phone'
            ])->default('text');
            $table->text('description')->nullable();
            $table->text('reason')->nullable();
            $table->json('requested_values')->nullable(); // For select/multiselect options
            $table->json('requested_category_ids')->nullable(); // Categories to attach to
            $table->unsignedBigInteger('requested_group_id')->nullable(); // Attribute group
            $table->boolean('is_required')->default(false);
            $table->boolean('is_filterable')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('created_attribute_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('created_attribute_id')->references('id')->on('attributes')->onDelete('set null');
            $table->foreign('requested_group_id')->references('id')->on('attribute_groups')->onDelete('set null');
            
            $table->index('status');
            $table->index('vendor_id');
            $table->index('requested_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_requests');
    }
};