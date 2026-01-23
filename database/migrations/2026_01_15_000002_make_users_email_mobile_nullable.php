<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Additive safe migration: make email/mobile nullable to support "Claim My Alumni Account"
        // without public signup. Uses driver-specific SQL to avoid doctrine/dbal.
        if (!Schema::hasTable('users')) return;

        $driver = DB::getDriverName();

        try {
            if ($driver === 'mysql') {
                if (Schema::hasColumn('users', 'email')) {
                    DB::statement("ALTER TABLE `users` MODIFY `email` VARCHAR(191) NULL");
                }
                if (Schema::hasColumn('users', 'mobile')) {
                    DB::statement("ALTER TABLE `users` MODIFY `mobile` VARCHAR(50) NULL");
                }
            } elseif ($driver === 'pgsql') {
                if (Schema::hasColumn('users', 'email')) {
                    DB::statement('ALTER TABLE users ALTER COLUMN email DROP NOT NULL');
                }
                if (Schema::hasColumn('users', 'mobile')) {
                    DB::statement('ALTER TABLE users ALTER COLUMN mobile DROP NOT NULL');
                }
            } else {
                // sqlite / others: ignore (nullable is not strictly enforced or requires rebuild)
            }
        } catch (\Throwable $e) {
            // Do not fail deployment if the column definition differs.
        }
    }

    public function down(): void
    {
        // Intentionally no-op to avoid breaking existing data.
    }
};
