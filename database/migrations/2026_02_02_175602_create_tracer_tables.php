<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Table for the SURVEYS (The questions/titles)
        Schema::create('tracer_surveys', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable(); // <--- This was likely missing
            $table->boolean('status')->default(1);   // <--- This was likely missing
            $table->timestamps();
        });

        // 2. Table for the ANSWERS (The alumni replies)
        Schema::create('tracer_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tracer_survey_id')->nullable(); 
            
            // Employment Details
            $table->string('employment_status'); 
            $table->string('job_title')->nullable();
            $table->string('company_name')->nullable();
            $table->string('salary_range')->nullable();
            $table->string('is_related')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Force delete in case of foreign key errors
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tracer_answers');
        Schema::dropIfExists('tracer_surveys');
        Schema::enableForeignKeyConstraints();
    }
};