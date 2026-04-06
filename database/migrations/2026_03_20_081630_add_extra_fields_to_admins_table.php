<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->text('address')->nullable()->after('phone');
            $table->int('city_id')->nullable()->after('address');
            $table->int('state_id')->nullable()->after('city');
            $table->int('country_id')->nullable()->after('state');
            $table->string('postal_code')->nullable()->after('country');
            $table->date('birth_date')->nullable()->after('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'city',
                'country',
                'postal_code',
                'birth_date'
            ]);
        });
    }
};
