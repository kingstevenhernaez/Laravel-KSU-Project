<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal - KSU</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #f0f2f5; }
        .alumni-nav { background-color: #0B3D2E; } /* KSU Green */
        /* 🟢 FIXED: Now only targets the top navigation bar! */
        .navbar .nav-link { color: rgba(255,255,255,0.9) !important; }
        .navbar .nav-link:hover, .navbar .nav-link.active { color: #FFC72C !important; font-weight: bold; }
        
        .badge-notification {
            font-size: 0.6rem;
            transform: translate(-5px, -10px);
        }
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            transition: 0.3s all;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark alumni-nav shadow-sm sticky-top">
        <div class="container">
            {{-- 🟢 DYNAMIC LOGO LINK --}}
            <a class="navbar-brand fw-bold" href="{{ Auth::user()->role == 3 ? route('registrar.dashboard') : route('alumni.dashboard') }}">
                <i class="fas fa-graduation-cap me-2"></i> KSU {{ Auth::user()->role == 3 ? 'Registrar' : 'Alumni' }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    
                    {{-- 🟢 DYNAMIC DASHBOARD LINK --}}
                    <li class="nav-item me-3">
                        <a class="nav-link {{ request()->routeIs('alumni.dashboard') || request()->routeIs('registrar.dashboard') ? 'active' : '' }}" href="{{ Auth::user()->role == 3 ? route('registrar.dashboard') : route('alumni.dashboard') }}">
                            Dashboard
                        </a>
                    </li>
                    
                    {{-- 🟢 ALUMNI ONLY: Request Documents --}}
                    @if(Auth::user()->role == 2)
                    <li class="nav-item me-4 border-end border-light pe-4">
                        <a class="nav-link {{ request()->routeIs('alumni.documents.*') ? 'text-info fw-bold' : '' }}" href="{{ route('alumni.documents.index') }}">
                            <i class="fas fa-file-signature me-1"></i> Request Documents
                        </a>
                    </li>
                    @endif

                    {{-- 🟢 REGISTRAR ONLY: Reports Shortcut --}}
                    @if(Auth::user()->role == 3)
                    <li class="nav-item me-4 border-end border-light pe-4">
                        <a class="nav-link {{ request()->routeIs('registrar.reports') ? 'text-warning fw-bold' : '' }}" href="{{ route('registrar.reports') }}">
                            <i class="fas fa-chart-pie me-1"></i> Analytics & Reports
                        </a>
                    </li>
                    @endif

                    {{-- Notifications --}}
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="#">
                            <i class="fas fa-envelope fa-lg"></i>
                        </a>
                    </li>

                    {{-- User Dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="rounded-circle bg-warning text-dark d-flex justify-content-center align-items-center fw-bold me-2" style="width: 32px; height: 32px; font-size: 14px;">
                                {{ substr(Auth::user()->first_name ?? 'U', 0, 1) }}
                            </div>
                            <span class="d-none d-lg-inline">{{ Auth::user()->first_name ?? 'User' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><h6 class="dropdown-header text-muted">Manage Account</h6></li>
                            
                            {{-- 🟢 ALUMNI ONLY: Profile & ID --}}
                            @if(Auth::user()->role == 2)
                            <li><a class="dropdown-item" href="{{ route('alumni.profile') }}"><i class="fas fa-user-circle me-2 text-success"></i> My Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('alumni.id_card') }}"><i class="fas fa-id-card me-2 text-primary"></i> Digital ID</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @endif

                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger fw-bold"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>