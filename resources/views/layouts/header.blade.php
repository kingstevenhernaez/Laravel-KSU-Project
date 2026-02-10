<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>KSU Alumni Management System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="description" content="Kalinga State University Alumni Portal">
    <meta name="keywords" content="KSU, Alumni, Portal">

    <meta property="og:type" content="website">
    <meta property="og:title" content="KSU Alumni">
    <meta property="og:description" content="Connect with KSU Alumni">
    <meta property="og:image" content="{{ asset('assets/images/branding/ksu-logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="KSU Alumni">

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    
    <link rel="stylesheet" href="{{ asset('assets/css/ksu-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @stack('style')
</head>