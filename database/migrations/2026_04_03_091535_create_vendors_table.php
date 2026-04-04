<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_vendors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // Personal Information
            $table->string('name');                    // Vendor owner name
            $table->string('email')->unique();         // Vendor email
            $table->string('password');                // Login password
            $table->string('phone')->nullable();       // Personal phone
            $table->string('avatar')->nullable();      // Profile picture

            // Basic Business Information
            $table->string('shop_name');
            $table->string('shop_slug')->unique();
            $table->text('shop_description')->nullable();
            $table->string('shop_logo')->nullable();
            $table->string('shop_banner')->nullable();

            // Contact Information
            $table->string('shop_email');
            $table->string('shop_phone');
            $table->string('shop_whatsapp')->nullable();
            $table->string('shop_website')->nullable();
            $table->text('shop_address');
            $table->string('shop_city');
            $table->string('shop_state');
            $table->string('shop_country');
            $table->string('shop_postal_code');

            // Vendor Type - IMPORTANT!
            $table->enum('vendor_type', ['own_store', 'third_party'])->default('third_party');

            // Business Type
            $table->enum('business_type', [
                'sole_proprietorship',
                'partnership',
                'llc',
                'private_limited',
                'public_limited',
                'trust',
                'other'
            ])->nullable();

            // Account Status
            $table->enum('account_status', ['pending', 'active', 'suspended', 'banned'])->default('pending');
            $table->text('suspension_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();

            // Verification Status
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');

            // Business Performance
            $table->integer('total_products')->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->decimal('total_commission', 10, 2)->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);

            // Settings
            $table->boolean('accepts_cod')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('commission_rate')->default(10); // Commission for marketplace owner

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->index('account_status');
            $table->string('profile_completed')->default('0')->comment('0=0%, 1=25%, 2=50%, 3=75%, 4=100%');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('admins');
            $table->index('verification_status');
            $table->index('vendor_type');
            $table->index('shop_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
