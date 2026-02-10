<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // We check if the column exists first to avoid "Duplicate column" errors
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'batch')) {
                $table->string('batch')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('batch');
            }
            if (!Schema::hasColumn('users', 'image')) {
                $table->string('image')->nullable()->after('department_id');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->integer('role')->default(2)->after('password'); // 1=Admin, 2=Alumni
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->boolean('status')->default(1)->after('role'); // 1=Active
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'batch', 'department_id', 'image', 'role', 'status']);
        });
    }
};