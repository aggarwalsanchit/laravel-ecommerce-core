<?php
// database/migrations/2026_04_14_000009_create_attribute_value_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_value_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('attribute_id');
            $table->string('requested_value');
            $table->string('requested_label')->nullable();
            $table->string('requested_color_code')->nullable();
            $table->string('requested_image')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('created_value_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('created_value_id')->references('id')->on('attribute_values')->onDelete('set null');
            
            $table->index('status');
            $table->index('vendor_id');
            $table->index('attribute_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_value_requests');
    }
};