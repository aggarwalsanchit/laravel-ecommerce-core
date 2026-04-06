<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('name', 191);
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->char('country_code', 2);
            $table->string('fips_code')->nullable();
            $table->string('iso2')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
            $table->boolean('flag')->default(true);
            $table->string('wikiDataId')->nullable()->comment('Rapid API GeoDB Cities');
            
            // Add indexes for better performance
            $table->index('name');
            $table->index('country_id');
            $table->index('country_code');
            $table->index('iso2');
            $table->index('flag');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};