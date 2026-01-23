<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tracer_surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable()->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_published')->default(false);

            // targeting rules (years, department_ids, college names)
            $table->json('target_rules')->nullable();

            $table->timestamps();
        });

        Schema::create('tracer_survey_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id')->index();
            $table->string('question_text');
            $table->string('question_type')->default('text'); // text|radio|checkbox|select|textarea
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('survey_id')->references('id')->on('tracer_surveys')->onDelete('cascade');
        });

        Schema::create('tracer_survey_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id')->index();
            $table->string('option_text');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('tracer_survey_questions')->onDelete('cascade');
        });

        Schema::create('tracer_survey_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable()->index();
            $table->unsignedBigInteger('survey_id')->index();
            $table->unsignedBigInteger('alumni_id')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['survey_id', 'alumni_id'], 'tracer_survey_submit_once_unique');

            $table->foreign('survey_id')->references('id')->on('tracer_surveys')->onDelete('cascade');
            $table->foreign('alumni_id')->references('id')->on('alumnus')->onDelete('cascade');
        });

        Schema::create('tracer_survey_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_id')->index();
            $table->unsignedBigInteger('question_id')->index();
            $table->longText('answer')->nullable(); // text or json-encoded array
            $table->timestamps();

            $table->foreign('response_id')->references('id')->on('tracer_survey_responses')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('tracer_survey_questions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_survey_answers');
        Schema::dropIfExists('tracer_survey_responses');
        Schema::dropIfExists('tracer_survey_options');
        Schema::dropIfExists('tracer_survey_questions');
        Schema::dropIfExists('tracer_surveys');
    }
};
