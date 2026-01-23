<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ksu_alumni_records', function (Blueprint $table) {
            
            // 1. Add 'birthdate' if missing
            if (!Schema::hasColumn('ksu_alumni_records', 'birthdate')) {
                $table->date('birthdate')->nullable()->after('email');
            }

            // 2. Add 'department_code' if missing
            if (!Schema::hasColumn('ksu_alumni_records', 'department_code')) {
                $table->string('department_code')->nullable()->after('birthdate');
            }

            // 3. Add 'graduation_year' if missing
            if (!Schema::hasColumn('ksu_alumni_records', 'graduation_year')) {
                $table->year('graduation_year')->nullable()->after('department_code');
            }

            // 4. Add 'program_code' if missing
            if (!Schema::hasColumn('ksu_alumni_records', 'program_code')) {
                $table->string('program_code')->nullable()->after('graduation_year');
            }
        });
    }

    public function down()
    {
        Schema::table('ksu_alumni_records', function (Blueprint $table) {
            $table->dropColumn(['birthdate', 'department_code', 'graduation_year', 'program_code']);
        });
    }
};