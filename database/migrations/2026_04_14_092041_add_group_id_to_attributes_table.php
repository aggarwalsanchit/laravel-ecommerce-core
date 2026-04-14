<?php
// database/migrations/2026_04_14_000010_add_group_id_to_attributes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            if (!Schema::hasColumn('attributes', 'group_id')) {
                $table->unsignedBigInteger('group_id')->nullable()->after('type');
                $table->foreign('group_id')->references('id')->on('attribute_groups')->onDelete('set null');
                $table->index('group_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');
        });
    }
};