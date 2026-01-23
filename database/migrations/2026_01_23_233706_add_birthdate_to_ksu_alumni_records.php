<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ksu_alumni_records', function (Blueprint $table) {
            // Add the missing birthdate column
            if (!Schema::hasColumn('ksu_alumni_records', 'birthdate')) {
                $table->date('birthdate')->nullable()->after('email');
            }
        });
    }

    public function down()
    {
        Schema::table('ksu_alumni_records', function (Blueprint $table) {
            $table->dropColumn('birthdate');
        });
    }
};