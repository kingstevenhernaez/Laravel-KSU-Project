<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            // 1. Identification
            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->unique()->nullable()->after('id');
            }

            // 2. Names (Check each one)
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('student_id');
            }
            if (!Schema::hasColumn('users', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('middle_name');
            }
            if (!Schema::hasColumn('users', 'suffix_name')) {
                $table->string('suffix_name')->nullable()->after('last_name');
            }

            // 3. Personal Details
            if (!Schema::hasColumn('users', 'birthdate')) {
                $table->date('birthdate')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('mobile');
            }

            // 4. Academic Details
            if (!Schema::hasColumn('users', 'course')) {
                $table->string('course')->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('course');
            }
            if (!Schema::hasColumn('users', 'year_graduated')) {
                $table->year('year_graduated')->nullable()->after('department');
            }

            // 5. System Flags
            if (!Schema::hasColumn('users', 'is_alumni')) {
                $table->boolean('is_alumni')->default(false)->after('status');
            }
            if (!Schema::hasColumn('users', 'force_password_change')) {
                $table->boolean('force_password_change')->default(false)->after('password');
            }
        });
    }

    public function down()
    {
        // We strictly drop only what we added
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'student_id', 'middle_name', 'suffix_name', 
                'birthdate', 'address', 'course', 'department', 
                'year_graduated', 'is_alumni', 'force_password_change'
            ];
            
            // Only drop if they exist
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Note: We usually DON'T drop first_name/last_name in down() 
            // because your system might rely on them now.
        });
    }
};