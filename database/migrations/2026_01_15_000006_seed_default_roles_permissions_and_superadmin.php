<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    public function up(): void
    {
        // Ensure a usable default admin experience without manual tinker steps.
        // This migration is additive and only inserts records if missing.

        // 1) Permissions needed by the sidebar and the new KSU modules.
        $permissions = [
            'Manage Event',
            'Manage Job Post',
            'Manage Story',
            'Manage Alumni',
            'Manage Membership',
            'Manage Notice',
            'Manage News',
            'Manage Transaction',
            'Manage Donation',
            'Manage Committee',
            'Manage Vote',
            'Manage Moderator',
            'Manage Website Settings',
            'Manage Newsletter',
            'Manage Application Setting',
            'Manage Subscription',
            'Manage Custom Domain',
            'Manage Version Update',
        ];

        foreach ($permissions as $name) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $name, 'guard_name' => 'web'],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // 2) Create Super Admin role (Spatie) if missing.
        $role = DB::table('roles')->where('name', 'Super Admin')->where('guard_name', 'web')->first();
        if (!$role) {
            $roleId = DB::table('roles')->insertGetId([
                'tenant_id' => null,
                'display_name' => 'Super Admin',
                'name' => 'Super Admin',
                'guard_name' => 'web',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $roleId = $role->id;
        }

        // 3) Assign all permissions to Super Admin role.
        $permIds = DB::table('permissions')->whereIn('name', $permissions)->pluck('id');
        foreach ($permIds as $pid) {
            DB::table('role_has_permissions')->updateOrInsert(
                ['role_id' => $roleId, 'permission_id' => $pid],
                []
            );
        }

        // 4) Create a default superadmin user if missing.
        // Credentials:
        //   Email: admin@ksu.edu.ph
        //   Password: Admin@123
        $user = DB::table('users')->where('email', 'admin@ksu.edu.ph')->first();
        if (!$user) {
            $userId = DB::table('users')->insertGetId([
                'uuid' => (string) Str::uuid(),
                'name' => 'Super Admin',
                'nick_name' => null,
                'email' => 'admin@ksu.edu.ph',
                'mobile' => null,
                'email_verified_at' => now(),
                'password' => Hash::make('Admin@123'),
                'image' => null,
                'role' => 1, // USER_ROLE_ADMIN
                'email_verification_status' => 1,
                'phone_verification_status' => 0,
                'google_auth_status' => 0,
                'google2fa_secret' => null,
                'google_id' => null,
                'facebook_id' => null,
                'verify_token' => null,
                'otp' => null,
                'otp_expiry' => null,
                'show_email_in_public' => 0,
                'show_phone_in_public' => 0,
                'created_by' => null,
                'status' => 1,
                'remember_token' => null,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $userId = $user->id;
        }

        // 5) Attach the Super Admin role to the user.
        DB::table('model_has_roles')->updateOrInsert(
            ['role_id' => $roleId, 'model_type' => 'App\\Models\\User', 'model_id' => $userId],
            []
        );
    }

    public function down(): void
    {
        // No-op: avoid deleting real data.
    }
};
