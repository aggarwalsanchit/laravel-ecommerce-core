// database/migrations/xxxx_xx_xx_xxxxxx_create_vendor_activity_logs_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');

            // Activity details
            $table->string('action'); // create, update, delete, login, logout, upload, etc.
            $table->string('entity_type')->nullable(); // product, order, profile, document, etc.
            $table->unsignedBigInteger('entity_id')->nullable(); // ID of the entity
            $table->string('entity_name')->nullable(); // Name/title of the entity

            // Changes tracking
            $table->json('old_values')->nullable(); // Previous values
            $table->json('new_values')->nullable(); // New values
            $table->text('description')->nullable(); // Human readable description

            // Additional info
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device')->nullable(); // mobile, desktop, tablet

            $table->timestamps();

            $table->index(['vendor_id', 'created_at']);
            $table->index(['entity_type', 'entity_id']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_activity_logs');
    }
};
