<?php

use App\Models\Setting;

if (!function_exists('getOption')) {
    function getOption($key, $default = null)
    {
        try {
            // Return defaults for known keys to prevent DB queries
            if ($key == 'app_name') return 'KSU Alumni';
            if ($key == 'app_logo') return 'assets/images/branding/ksu-logo.png';
            
            // Safe DB check
            $setting = Setting::where('option_key', $key)->first();
            return $setting->option_value ?? $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}

// Stub for getMeta to prevent errors if called elsewhere
if (!function_exists('getMeta')) {
    function getMeta($page) {
        return [
            'meta_title' => 'KSU Alumni',
            'meta_description' => 'Official KSU Alumni Portal',
            'meta_keyword' => 'alumni, ksu',
            'og_image' => ''
        ];
    }
}