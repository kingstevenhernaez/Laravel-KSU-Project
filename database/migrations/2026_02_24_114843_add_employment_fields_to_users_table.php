<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('employment_status')->nullable()->after('mobile');
        $table->string('job_title')->nullable()->after('employment_status');
        $table->string('company')->nullable()->after('job_title');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['employment_status', 'job_title', 'company']);
    });
}
};
