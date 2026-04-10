// database/migrations/2024_01_01_000001_create_shops_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();

            // Basic Shop Information
            $table->string('shop_name');
            $table->string('shop_slug')->unique();
            $table->text('shop_description')->nullable();
            $table->string('shop_logo')->nullable();
            $table->string('shop_banner')->nullable();

            // Contact Information
            $table->string('shop_email')->nullable();
            $table->string('shop_phone_code')->nullable();
            $table->string('shop_phone')->nullable();
            $table->string('shop_whatsapp')->nullable();
            $table->string('shop_website')->nullable();
            $table->text('shop_address')->nullable();
            $table->string('shop_city')->nullable();
            $table->integer('shop_state')->nullable();
            $table->integer('shop_country')->nullable();
            $table->string('shop_postal_code')->nullable();

            // Business Details
            $table->enum('vendor_type', ['own_store', 'third_party'])->default('third_party');
            $table->enum('business_type', [
                'sole_proprietorship',
                'partnership',
                'llc',
                'private_limited',
                'public_limited',
                'trust',
                'other'
            ])->nullable();

            // Shop Status
            $table->string('profile_completed')->default('0')->comment('0=0%, 1=25%, 2=50%, 3=75%, 4=100%');
            $table->enum('account_status', ['pending', 'verified', 'suspended', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->text('suspension_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->boolean('ready_for_approve')->default(false);

            // Shop Performance
            $table->integer('total_products')->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->decimal('total_commission', 10, 2)->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);

            // Settings
            $table->boolean('accepts_cod')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('commission_rate')->default(10);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();


            // Indexes
            $table->index('account_status');
            $table->index('verification_status');
            $table->index('shop_slug');
            $table->index('shop_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
