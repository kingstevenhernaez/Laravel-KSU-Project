<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable()->index();
            $table->unsignedBigInteger('alumni_id')->index();
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('employment_type')->nullable(); // Full-time, Part-time, Contract, etc.
            $table->string('industry')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('alumni_id')->references('id')->on('alumnus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_histories');
    }
};
