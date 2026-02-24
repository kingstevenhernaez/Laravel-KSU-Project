<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. The Main Survey Table
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_ched_template')->default(false);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

        // 2. The Questions Table
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id');
            $table->string('question_text');
            $table->string('answer_type'); // e.g., 'text', 'textarea', 'radio', 'checkbox', 'dropdown'
            $table->json('options')->nullable(); // Stores dropdown choices as JSON array
            $table->integer('order_num')->default(0);
            $table->boolean('is_required')->default(true);
            $table->timestamps();

            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
        });

        // 3. The Answers Table
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('user_id'); // The Alumni who answered
            $table->text('answer_text'); // Stores the actual answer
            $table->timestamps();

            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('survey_questions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('surveys');
    }
};