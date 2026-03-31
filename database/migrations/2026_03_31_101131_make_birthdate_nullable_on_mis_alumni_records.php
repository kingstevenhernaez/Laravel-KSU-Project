<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mis_alumni_records', function (Blueprint $table) {
            // This tells MySQL: "It's okay if the birthdate is blank!"
            $table->date('birthdate')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('mis_alumni_records', function (Blueprint $table) {
            $table->date('birthdate')->nullable(false)->change();
        });
    }
};