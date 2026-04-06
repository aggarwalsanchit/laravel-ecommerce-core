<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            
            // Activity details
            $table->string('action'); // login, logout, create, update, delete, approve, suspend, etc.
            $table->string('module')->nullable(); // vendor, product, category, user, etc.
            $table->string('entity_type')->nullable(); // Vendor, Product, Category, etc.
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('entity_name')->nullable();
            
            // Changes tracking
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('description')->nullable();
            
            // Request details
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            
            $table->timestamps();
            
            $table->index(['admin_id', 'created_at']);
            $table->index(['action', 'module']);
            $table->index('entity_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};