<?php
// database/migrations/2026_04_15_000003_create_brand_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('requested_name');
            $table->string('requested_slug')->unique();
            $table->string('requested_code')->nullable();
            $table->text('description')->nullable();
            $table->text('reason')->nullable();
            $table->json('requested_category_ids')->nullable();  // JSON field for categories
            $table->string('logo')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('created_brand_id')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('created_brand_id')->references('id')->on('brands')->onDelete('set null');

            $table->index('status');
            $table->index('vendor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_requests');
    }
};
