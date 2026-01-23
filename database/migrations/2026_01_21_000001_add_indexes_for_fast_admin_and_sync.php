<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Non-destructive performance indexes
        if (Schema::hasTable('ksu_alumni_records')) {
            Schema::table('ksu_alumni_records', function (Blueprint $table) {
                // Helpful for search and joins; unique(tenant_id, student_number) already exists.
                $table->index('student_number', 'ksu_alumni_records_student_number_idx');
                $table->index('college_name', 'ksu_alumni_records_college_name_idx');
                $table->index('program_name', 'ksu_alumni_records_program_name_idx');
            });
        }

        if (Schema::hasTable('domains')) {
            Schema::table('domains', function (Blueprint $table) {
                // Ensure domain lookup is indexed (safe if not already present).
                try {
                    $table->index('domain', 'domains_domain_idx');
                } catch (\Throwable $e) {
                    // Ignore if index already exists (some tenancy installs already have it)
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ksu_alumni_records')) {
            Schema::table('ksu_alumni_records', function (Blueprint $table) {
                $table->dropIndex('ksu_alumni_records_student_number_idx');
                $table->dropIndex('ksu_alumni_records_college_name_idx');
                $table->dropIndex('ksu_alumni_records_program_name_idx');
            });
        }

        if (Schema::hasTable('domains')) {
            Schema::table('domains', function (Blueprint $table) {
                try {
                    $table->dropIndex('domains_domain_idx');
                } catch (\Throwable $e) {
                }
            });
        }
    }
};
