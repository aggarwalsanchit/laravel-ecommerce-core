<?php
// database/migrations/2026_04_15_000012_create_product_questions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->text('question');
            $table->boolean('is_answered')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('product_id');
            $table->index('is_answered');
        });
        
        Schema::create('product_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('user_id')->nullable(); // customer or admin
            $table->string('guest_name')->nullable();
            $table->text('answer');
            $table->unsignedBigInteger('answered_by')->nullable(); // admin id
            $table->timestamps();
            
            $table->foreign('question_id')->references('id')->on('product_questions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('answered_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_answers');
        Schema::dropIfExists('product_questions');
    }
};