// database/migrations/xxxx_xx_xx_xxxxxx_create_vendor_staff_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_staff', function (Blueprint $table) {
            $table->id();

            // Vendor relation
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');

            // Staff personal information
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();

            // Staff role and permissions
            $table->enum('role', ['admin', 'manager', 'inventory', 'fulfillment', 'support'])->default('support');
            $table->json('custom_permissions')->nullable();

            // Account status
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();

            // Optional relation to main user table
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index(['vendor_id', 'role']);
            $table->index('email');
            $table->index('is_active');
            $table->unique(['vendor_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_staff');
    }
};
