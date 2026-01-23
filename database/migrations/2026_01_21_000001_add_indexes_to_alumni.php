<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('alumnus')) {
            return;
        }

        Schema::table('alumnus', function (Blueprint $table) {
            // ✅ High-impact indexes based on actual alumnus columns
            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('passing_year_id');
            $table->index('batch_id');
            $table->index('department_id');

            // Optional commonly searched fields (safe + useful)
            $table->index('city');
            $table->index('gender');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('alumnus')) {
            return;
        }

        Schema::table('alumnus', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['passing_year_id']);
            $table->dropIndex(['batch_id']);
            $table->dropIndex(['department_id']);
            $table->dropIndex(['city']);
            $table->dropIndex(['gender']);
        });
    }
};
