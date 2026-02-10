<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Change the 'image' column from Integer to String (Text)
            // We add ->nullable() so it can be empty if the user has no photo
            $table->string('image', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert back to integer (only used if we undo this migration)
            $table->integer('image')->nullable()->change();
        });
    }
};