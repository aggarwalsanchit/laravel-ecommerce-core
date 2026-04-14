<?php
// database/migrations/2026_04_14_000002_create_attributes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', [
                'text', 'textarea', 'number', 'decimal', 'select', 'multiselect', 
                'checkbox', 'radio', 'date', 'datetime', 'color', 'image', 'file', 'url', 'email', 'phone'
            ])->default('text');
            $table->string('unit')->nullable(); // e.g., cm, kg, inches, $
            $table->integer('order')->default(0);
            
            // Validation Rules
            $table->boolean('is_required')->default(false);
            $table->boolean('is_unique')->default(false);
            $table->boolean('is_filterable')->default(false);
            $table->boolean('is_searchable')->default(false);
            $table->boolean('is_comparable')->default(false);
            $table->boolean('show_on_product_page')->default(true);
            $table->boolean('show_on_product_list')->default(false);
            
            // Validation Constraints
            $table->string('min_value')->nullable();
            $table->string('max_value')->nullable();
            $table->integer('max_length')->nullable();
            $table->string('regex_pattern')->nullable();
            $table->text('default_value')->nullable();
            $table->text('placeholder')->nullable();
            $table->text('help_text')->nullable();
            
            // Display Settings
            $table->string('icon')->nullable();
            $table->string('input_class')->nullable();
            $table->string('wrapper_class')->nullable();
            
            // Status
            $table->boolean('status')->default(true);
            $table->boolean('is_featured')->default(false);
            
            // Vendor Request & Approval System
            $table->enum('approval_status', ['approved', 'pending', 'rejected'])->default('approved');
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->text('request_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('requested_at')->nullable();
            
            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('requested_by')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');
            
            // Indexes - FIXED: Use correct column names
            $table->index(['approval_status', 'status'], 'attr_approval_status');
            $table->index('type');
            $table->index('is_filterable');
            $table->index('order');
            $table->index('is_required');
            $table->index(['status', 'show_on_product_page'], 'attr_status_visible'); // Fixed: using show_on_product_page
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};