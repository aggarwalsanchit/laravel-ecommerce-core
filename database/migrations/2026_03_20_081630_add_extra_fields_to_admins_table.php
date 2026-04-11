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
            $table->string('city')->nullable()->after('address');
            $table->integer('state_id')->nullable()->after('city_id');
            $table->integer('country_id')->nullable()->after('state_id');
            $table->string('postal_code')->nullable()->after('country_id');
            $table->date('birth_date')->nullable()->after('postal_code');
            $table->date('last_login_at')->nullable()->after('birth_date');
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
