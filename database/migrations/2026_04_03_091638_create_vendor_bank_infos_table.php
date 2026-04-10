<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_vendor_bank_infos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_bank_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');

            // Bank Account Details
            $table->string('account_holder_name');
            $table->string('account_number');
            $table->string('bank_name');
            $table->string('bank_branch');
            $table->string('ifsc_code')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('iban_number')->nullable();
            $table->string('bank_address')->nullable();

            // Digital Payments
            $table->string('upi_id')->nullable();
            $table->string('paypal_email')->nullable();
            $table->string('stripe_account_id')->nullable();
            $table->string('razorpay_account_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_bank_infos');
    }
};
