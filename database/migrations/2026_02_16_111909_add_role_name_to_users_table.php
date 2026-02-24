<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
    {
        // 🟢 FIX: Only add the column if it doesn't already exist!
        if (!Schema::hasColumn('users', 'role_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role_name')->default('alumni')->after('role');
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
