<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * SUPER ADMIN / ADMIN BYPASS
         *
         * IMPORTANT (based on your verified DB):
         * - The "admin@ksu.edu.ph" account has role = 1.
         * - Your DB currently only contains role value 1 (no separate super-admin role).
         *
         * Many admin sidebar/menu items are wrapped in @can(...) checks.
         * If role=1 has no explicit permissions assigned, those menu items will not appear
         * and access can be denied.
         *
         * This Gate::before rule guarantees that role=1 can access all abilities,
         * restoring the full admin navigation and preventing false "missing buttons".
         *
         * No DB schema changes, no migrations.
         */
        Gate::before(function ($user, $ability) {
            $role = (int) ($user->role ?? 0);

            // role = 1 is Admin/Super Admin in this system
            if ($role === 1) {
                return true;
            }

            return null; // fall back to normal Gate/permission checks
        });
    }
}
