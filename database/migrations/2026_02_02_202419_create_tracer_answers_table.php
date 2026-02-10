<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    if (!Schema::hasTable('tracer_answers')) {
        Schema::create('tracer_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tracer_survey_id')->nullable();
            $table->string('employment_status');
            $table->string('job_title')->nullable();
            $table->string('company_name')->nullable();
            $table->string('salary_range')->nullable();
            $table->string('is_related')->nullable();
            $table->timestamps();
        });
    }
}

public function down(): void
    {
        // 1. Disable safety checks (Foreign Keys)
        Schema::disableForeignKeyConstraints();

        // 2. Drop the tables (Order doesn't matter now because checks are off)
        Schema::dropIfExists('tracer_answers');
        Schema::dropIfExists('tracer_survey_questions');
        Schema::dropIfExists('tracer_surveys');

        // 3. Turn safety checks back on
        Schema::enableForeignKeyConstraints();
    }
};
