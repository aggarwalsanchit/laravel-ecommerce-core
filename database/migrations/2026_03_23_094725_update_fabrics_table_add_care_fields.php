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
        Schema::table('fabrics', function (Blueprint $table) {
            // Care Instructions
            $table->string('washing')->nullable()->after('description');
            $table->string('bleaching')->nullable()->after('washing');
            $table->string('drying')->nullable()->after('bleaching');
            $table->string('ironing')->nullable()->after('drying');
            $table->text('care_tips')->nullable()->after('ironing');

            // Additional Analytics Fields
            $table->decimal('avg_rating', 3, 2)->default(0)->after('total_revenue');
            $table->integer('review_count')->default(0)->after('avg_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fabrics', function (Blueprint $table) {
            $table->dropColumn([
                'washing',
                'bleaching',
                'drying',
                'ironing',
                'care_tips',
                'avg_rating',
                'review_count'
            ]);
        });
    }
};
