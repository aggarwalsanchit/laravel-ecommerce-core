<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_vendor_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');

            $table->enum('document_type', [
                'pan_card',
                'gst_certificate',
                'cancelled_cheque',
                'bank_statement',
                'business_registration',
                'business_license',
                'address_proof',
                'identity_proof',
                'trade_license',
                'other'
            ]);

            $table->string('document_name');
            $table->string('document_path');
            $table->string('document_number')->nullable();
            $table->text('notes')->nullable();

            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->text('verification_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_documents');
    }
};
