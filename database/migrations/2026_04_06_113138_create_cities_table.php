<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('name', 191);
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
            $table->string('state_code', 191);
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->char('country_code', 2);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamp('created_at')->default('2014-01-01 01:01:01');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->boolean('flag')->default(true);
            $table->string('wikiDataId')->nullable()->comment('Rapid API GeoDB Cities');
            
            // Add indexes for better performance
            $table->index('name');
            $table->index('state_id');
            $table->index('country_id');
            $table->index('country_code');
            $table->index('state_code');
            $table->index('flag');
            
            // Composite indexes for common queries
            $table->index(['country_id', 'state_id']);
            $table->index(['country_code', 'state_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};