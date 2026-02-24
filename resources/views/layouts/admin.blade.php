<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSU Admin Dashboard</title>
    
    {{-- 🟢 Premium App Icons & Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    {{-- 🟢 Mobile Browser Theme Color (Matches KSU Green) --}}
    <meta name="theme-color" content="#198754">
    
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            overflow-x: hidden;
        }
    </style>
</head>
<body>

    <div class="d-flex">
        @include('layouts.admin_sidebar')

        <div class="flex-grow-1 bg-light">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3">
                <div class="container-fluid">
                    <span class="navbar-text fw-bold text-success">
                        Kalinga State University Alumni System
                    </span>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
    </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>