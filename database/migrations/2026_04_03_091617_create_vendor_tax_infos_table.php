<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_vendor_tax_infos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_tax_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');

            // GST Information (India)
            $table->string('gst_number')->nullable()->unique();
            $table->enum('gst_type', ['regular', 'composition', 'casual', 'unregistered'])->nullable();
            $table->date('gst_registration_date')->nullable();
            $table->string('gst_certificate')->nullable();

            // PAN Information (India)
            $table->string('pan_number')->nullable()->unique();
            $table->string('pan_card_document')->nullable();
            $table->string('pan_holder_name')->nullable();

            // International Tax
            $table->string('vat_number')->nullable()->unique();
            $table->string('ein_number')->nullable()->unique();
            $table->string('tax_id')->nullable()->unique();

            // Business Registration
            $table->string('business_registration_number')->nullable();
            $table->string('business_license_number')->nullable();
            $table->date('business_registration_date')->nullable();
            $table->string('business_registration_certificate')->nullable();

            // Verification Status
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->text('verification_notes')->nullable();

            $table->timestamps();

            $table->index('gst_number');
            $table->index('pan_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_tax_infos');
    }
};
