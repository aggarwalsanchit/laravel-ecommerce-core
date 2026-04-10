// database/migrations/2024_01_01_000002_create_vendor_users_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');

            // Personal Information
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();

            // Role & Permissions
            $table->string('vendor_role');
            $table->json('custom_permissions')->nullable();

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_owner')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('postal_code')->nullable();
            $table->date('birth_date')->nullable();

            // Optional: Link to main user account
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index(['shop_id', 'role']);
            $table->index('email');
            $table->index('is_active');
            $table->unique(['shop_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor');
    }
};
