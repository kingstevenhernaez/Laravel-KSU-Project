<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add Targeting to Surveys
        Schema::table('surveys', function (Blueprint $table) {
            $table->string('target_course')->nullable()->after('is_active');
            $table->string('target_batch')->nullable()->after('target_course');
        });

        // Add ID Upload to Users
        Schema::table('users', function (Blueprint $table) {
            $table->string('valid_id_path')->nullable()->after('image');
        });
    }

    public function down()
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn(['target_course', 'target_batch']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('valid_id_path');
        });
    }
};