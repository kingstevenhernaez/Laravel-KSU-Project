<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

{{-- Use the WORKING admin theme header (loads correct admin CSS) --}}
@include('super_admin.layouts.header')

<body class="{{ optional(selectedLanguage())->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }}">

<div class="overflow-x-hidden">

    @if (getOption('app_preloader_status', 0) == STATUS_ACTIVE)
        <div id="preloader">
            <div id="preloader_status">
                <img src="{{ getSettingImage('app_preloader') }}" alt="{{ getOption('app_name') }}"/>
            </div>
        </div>
    @endif

    <div class="d-flex">

        {{-- Sidebar (WORKING admin sidebar) --}}
        @include('super_admin.layouts.sidebar')

        <div class="flex-grow-1">
            {{-- Top nav/header (WORKING admin nav) --}}
            @include('super_admin.layouts.nav')

            {{-- Main content --}}
            @yield('content')
        </div>
    </div>
</div>

@if (!empty(getOption('cookie_status')) && getOption('cookie_status') == STATUS_ACTIVE)
    <div class="cookie-consent-wrap shadow-lg">
        @include('cookie-consent::index')
    </div>
@endif

{{-- WORKING admin scripts (loads correct admin JS) --}}
@include('super_admin.layouts.script')

</body>
</html>
