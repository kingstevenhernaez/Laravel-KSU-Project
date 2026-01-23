<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ksu_alumni_records', function (Blueprint $table) {
            if (!Schema::hasColumn('ksu_alumni_records', 'claimed_user_id')) {
                $table->unsignedBigInteger('claimed_user_id')->nullable()->after('payload')->index();
            }
            if (!Schema::hasColumn('ksu_alumni_records', 'claimed_at')) {
                $table->timestamp('claimed_at')->nullable()->after('claimed_user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ksu_alumni_records', function (Blueprint $table) {
            if (Schema::hasColumn('ksu_alumni_records', 'claimed_at')) {
                $table->dropColumn('claimed_at');
            }
            if (Schema::hasColumn('ksu_alumni_records', 'claimed_user_id')) {
                $table->dropColumn('claimed_user_id');
            }
        });
    }
};
