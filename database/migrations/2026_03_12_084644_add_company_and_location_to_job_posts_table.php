<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_posts', function (Blueprint $table) {
            // ONLY add company, location already exists!
            $table->string('company')->nullable()->after('title');
        });
    }

    public function down()
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropColumn('company');
        });
    }
};