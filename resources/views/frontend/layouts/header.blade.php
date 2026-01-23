<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>{{ getOption('app_name') }} - @stack('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    @hasSection('meta')
        @stack('meta')
    @else
        @php
            $metaData = getMeta('home');
        @endphp

        <meta name="description" content="{{ __($metaData['meta_description']) ?? getOption('app_name') }}">
        <meta name="keywords" content="{{ __($metaData['meta_keyword']) }}">

        <meta property="og:type" content="{{ __('Alumni') }}">
        <meta property="og:title" content="{{ __($metaData['meta_title']) ?? getOption('app_name') }}">
        <meta property="og:description" content="{{ __($metaData['meta_description']) ?? getOption('app_name') }}">
        <meta property="og:image" content="{{ __($metaData['og_image']) ?? getSettingImage('app_logo') }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="{{ __(getOption('app_name')) }}">

        <meta name="twitter:card" content="{{ __('Alumni') }}">
        <meta name="twitter:title" content="{{ __($metaData['meta_title']) ?? getOption('app_name') }}">
        <meta name="twitter:description" content="{{ __($metaData['meta_description']) ?? getOption('app_name') }}">
        <meta name="twitter:image" content="{{ __($metaData['og_image']) ?? getSettingImage('app_logo') }}">
    @endif

    {{-- favicon --}}
    <link rel="icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/x-icon">

    {{-- PWA --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#004225">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">

    {{-- fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@100;200;300;400;500;600;700;800;900&family=Nunito:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet"/>

    {{-- IMPORTANT: Use the REAL existing css paths in /public/frontend/... --}}
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/css/plugins.css') }}?ver={{ env('VERSION', 0) }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/scss/style.css') }}?ver={{ env('VERSION', 0) }}"/>

    {{-- dynamic color loader --}}
    @if (isAddonInstalled('ALUSAAS') && isCentralDomain())
        @include('super_admin.layouts.dynamic-color')
    @else
        @include('frontend.layouts.dynamic-color')
    @endif

    @stack('style')

    {{-- Custom CSS from Admin > Settings > Color & Custom Code --}}
    @php
        // In ALUSAAS central domain, the setting key is sa_custom_css.
        // Otherwise, it's custom_css.
        $rawCustomCss = (isAddonInstalled('ALUSAAS') && isCentralDomain())
            ? (string) (getOption('sa_custom_css') ?? '')
            : (string) (getOption('custom_css') ?? '');
    @endphp

    @if(!empty(trim($rawCustomCss)))
        <style id="tenant-custom-css">
            {!! $rawCustomCss !!}
        </style>
    @endif

    @if (getOption('google_analytics_status', 0))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ getOption('google_analytics_tracking_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', "{{ getOption('google_analytics_tracking_id') }}");
        </script>
    @endif
</head>
