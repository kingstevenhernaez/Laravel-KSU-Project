<?php

namespace App\Providers;

use App\Models\Language;
use App\Models\Setting;
use Illuminate\Database\Schema\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Database\Models\Domain;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191);
        Paginator::defaultView('frontend.pagination.custom');

        try {
            // Ensure DB is reachable before running tenant/option logic
            $connection = DB::connection()->getPdo();
            if (!$connection) {
                return;
            }

            $allOptions = [];
            $superAdminOptions = ['settings' => []];

            // Resolve domain/tenant safely
            $domain = null;
            if (function_exists('getAddonCodeBuildVersion') && getAddonCodeBuildVersion('ALUSAAS')) {
                $host = request()->getHost();
                $domain = Domain::where('domain', $host)->first();
                if (function_exists('superAdminSetting')) {
                    $superAdminOptions['settings'] = Setting::whereNull('tenant_id')
                        ->whereIn('option_key', superAdminSetting())
                        ->get()
                        ->pluck('option_value', 'option_key')
                        ->toArray();
                }
            } else {
                $domain = Domain::first();
                $superAdminOptions['settings'] = Setting::whereNull('tenant_id')
                    ->whereIn('option_key', [
                        'build_version',
                        'ALUSAAS_build_version',
                        'ALUSAAS_current_version',
                        'ALUDONATION_build_version',
                        'ALUDONATION_current_version',
                        'ALUCOMMITTEE_build_version',
                        'ALUCOMMITTEE_current_version',
                        'current_version',
                    ])->get()
                    ->pluck('option_value', 'option_key')
                    ->toArray();
            }

            $tenantId = $domain?->tenant_id;

            // Tenant settings (safe even if $tenantId is null)
            $allOptions['settings'] = Setting::where('tenant_id', $tenantId)
                ->get()
                ->pluck('option_value', 'option_key')
                ->toArray();

            // Merge tenant + superadmin settings
            $allOptions['settings'] = array_merge($allOptions['settings'], $superAdminOptions['settings']);
            config($allOptions);

            /**
             * -------------------------
             * SAFE LANGUAGE FALLBACK
             * -------------------------
             * Fixes: "Attempt to read property rtl on null"
             */
            $sessionIso = session()->get('local');
            $language = null;

            if (!empty($sessionIso)) {
                $language = Language::where('iso_code', $sessionIso)->first();
            }

            if (!$language) {
                // Prefer default language; otherwise use first available; otherwise fallback to ID 1
                $language = Language::where('default', ACTIVE)->first()
                    ?: Language::first()
                    ?: Language::find(1);
            }

            if ($language) {
                session(['local' => $language->iso_code]);
                App::setLocale($language->iso_code);
            }

            // Helper-based configs (guarded)
            if (function_exists('getDefaultLanguage')) {
                config(['app.defaultLanguage' => getDefaultLanguage()]);
            }

            if (function_exists('getCurrencySymbol')) {
                config(['app.currencySymbol' => getCurrencySymbol($tenantId)]);
            }
            if (function_exists('getIsoCode')) {
                config(['app.isoCode' => getIsoCode($tenantId)]);
            }
            if (function_exists('getCurrencyPlacement')) {
                config(['app.currencyPlacement' => getCurrencyPlacement($tenantId)]);
            }

            if (function_exists('getOption')) {
                config(['app.debug' => getOption('app_debug', true)]);
                config(['app.timezone' => getOption('app_timezone', 'UTC')]);

                config(['services.google.client_id' => getOption('google_client_id')]);
                config(['services.google.client_secret' => getOption('google_client_secret')]);
                config(['services.google.redirect' => url('auth/google/callback')]);

                config(['services.facebook.client_id' => getOption('facebook_client_id')]);
                config(['services.facebook.client_secret' => getOption('facebook_client_secret')]);
                config(['services.facebook.redirect' => url('auth/facebook/callback')]);

                if (!empty(getOption('google_recaptcha_status')) && (int) getOption('google_recaptcha_status') === 1) {
                    config(['recaptchav3.sitekey' => getOption('google_recaptcha_site_key')]);
                    config(['recaptchav3.secret' => getOption('google_recaptcha_secret_key')]);
                }

                if (getOption('pusher_status', 0)) {
                    config(['broadcasting.connections.pusher.key' => getOption('pusher_app_key', 'null')]);
                    config(['broadcasting.connections.pusher.secret' => getOption('pusher_app_secret', 'null')]);
                    config(['broadcasting.connections.pusher.app_id' => getOption('pusher_app_id', 'null')]);
                    config(['broadcasting.connections.pusher.options.cluster' => getOption('pusher_cluster', 'null')]);
                    config(['broadcasting.default' => 'pusher']);
                } else {
                    config(['broadcasting.default' => 'null']);
                }

                date_default_timezone_set(getOption('app_timezone', 'UTC'));

                /**
                 * IMPORTANT:
                 * Do NOT force https in local/dev, it can cause redirect loops on 127.0.0.1
                 */
                if ((int) getOption('force_ssl', 0) === 1 && app()->environment('production')) {
                    URL::forceScheme('https');
                }
            }

            Gate::before(function ($user, $ability) {
                return $user->is_alumni ? true : null;
            });
        } catch (\Throwable $e) {
            Log::info('Service Provider - ' . $e->getMessage());
        }
    }
}
