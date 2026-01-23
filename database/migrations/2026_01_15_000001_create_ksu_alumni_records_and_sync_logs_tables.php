<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ksu_alumni_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable()->index();
            $table->string('student_number', 50);
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();

            $table->string('program_code')->nullable();
            $table->string('program_name')->nullable();
            $table->string('college_name')->nullable();
            $table->unsignedInteger('graduation_year')->nullable();

            $table->json('payload')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'student_number'], 'ksu_records_tenant_student_unique');
            $table->index(['tenant_id', 'graduation_year']);
        });

        Schema::create('ksu_enrollment_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable()->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('inserted')->default(0);
            $table->unsignedInteger('updated')->default(0);
            $table->unsignedInteger('failed')->default(0);
            $table->text('error_summary')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ksu_enrollment_sync_logs');
        Schema::dropIfExists('ksu_alumni_records');
    }
};
