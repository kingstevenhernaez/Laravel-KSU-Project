<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSU Alumni Center - Official Portal</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --ksu-green: #0B3D2E;
            --ksu-gold: #FFC72C;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* --- NAVBAR --- */
        .navbar {
            background-color: var(--ksu-green);
            border-bottom: 4px solid var(--ksu-gold);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        .navbar-brand img {
            height: 50px;
        }
        .brand-text-main {
            color: var(--ksu-gold);
            font-weight: 800;
            font-size: 20px;
            text-transform: uppercase;
            line-height: 1;
        }
        .brand-text-sub {
            color: white;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 1px;
            display: block;
            margin-top: 2px;
        }
        .nav-link {
            color: white !important;
            font-weight: 600;
            font-size: 14px;
            margin: 0 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--ksu-gold) !important;
            opacity: 1;
        }
        .btn-login-nav {
            background-color: var(--ksu-gold);
            color: var(--ksu-green);
            font-weight: 800;
            border-radius: 4px;
            padding: 8px 25px;
            text-transform: uppercase;
            font-size: 13px;
            border: none;
        }
        .btn-login-nav:hover {
            background-color: white;
            color: var(--ksu-green);
        }

        /* --- HERO SECTION --- */
        .hero-section {
            position: relative;
            height: 85vh;
            min-height: 600px;
            background: url('{{ asset("assets/images/ksu-gate-bg.jpg") }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Fallback Overlay */
        .hero-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(11, 61, 46, 0.85), rgba(11, 61, 46, 0.7));
            z-index: 1;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            max-width: 900px;
            padding: 20px;
            width: 100%;
        }

        /* --- BADGE --- */
        .badge-pill {
            background-color: var(--ksu-gold);
            color: var(--ksu-green);
            font-weight: 800;
            padding: 10px 25px;
            border-radius: 50px;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            display: inline-block;
            margin-bottom: 25px;
        }

        /* --- HEADLINES --- */
        .hero-title {
            font-weight: 900;
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 20px;
            text-transform: uppercase;
            text-shadow: 0px 4px 10px rgba(0,0,0,0.3);
        }
        .hero-title span {
            color: var(--ksu-gold);
        }
        .hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 400;
            margin-bottom: 40px;
            line-height: 1.6;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* --- SEARCH BAR --- */
        .hero-search-container {
            max-width: 600px;
            margin: 0 auto 40px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 50px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .hero-search-input {
            border: none;
            height: 55px;
            border-radius: 50px 0 0 50px;
            padding-left: 30px;
            font-size: 16px;
        }
        .hero-search-input:focus {
            box-shadow: none;
        }
        .hero-search-btn {
            background-color: var(--ksu-gold);
            color: var(--ksu-green);
            font-weight: 800;
            border-radius: 0 50px 50px 0;
            padding: 0 30px;
            border: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .hero-search-btn:hover {
            background-color: white;
            color: var(--ksu-green);
        }

        /* --- BENEFITS SECTION --- */
        .benefits-section {
            padding: 80px 0;
            background-color: #f8f9fa;
        }
        .benefit-card {
            background: white;
            border-radius: 12px;
            padding: 40px 30px;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.1);
        }
        .benefit-icon {
            font-size: 40px;
            color: var(--ksu-gold);
            margin-bottom: 20px;
        }
        .section-badge {
            background-color: #e9ecef;
            color: #495057;
            font-weight: 700;
            font-size: 11px;
            padding: 8px 16px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: 800;
            color: black;
            margin-bottom: 60px;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .hero-subtitle { font-size: 1rem; }
            .hero-search-container { width: 100%; }
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            {{-- Logo --}}
            <a class="navbar-brand d-flex align-items-center gap-3" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/branding/ksu-logo.png') }}" alt="KSU Logo" 
                     onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/Kalinga_State_University_logo.png/600px-Kalinga_State_University_logo.png'">
                <div>
                    <span class="brand-text-main">KSU ALUMNI CENTER</span>
                    <span class="brand-text-sub">KALINGA STATE UNIVERSITY</span>
                </div>
            </a>

            {{-- Mobile Toggle --}}
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>

            {{-- Links --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/') }}">Home</a></li>
                    
                    {{-- Note: Directory link removed from here because we have the big search bar below --}}
                    
                    <li class="nav-item"><a class="nav-link" href="#">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Community</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                    
                    {{-- Login/Dashboard Button --}}
                    <li class="nav-item ms-lg-3">
                        @if (Route::has('login'))
                            @auth
                                @if(Auth::user()->role == 1)
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-login-nav">Dashboard</a>
                                @else
                                    <a href="{{ route('alumni.dashboard') }}" class="btn btn-login-nav">My Portal</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-login-nav">LOGIN</a>
                            @endauth
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section class="hero-section">
        <div class="hero-overlay"></div>
        
        <div class="hero-content">
            <span class="badge-pill">OFFICIAL ALUMNI PORTAL</span>
            
            <h1 class="hero-title">
                WELCOME HOME,<br>
                <span>KSU GRADUATES</span>
            </h1>
            
            <p class="hero-subtitle">
                Reconnect with your alma mater, expand your professional network, 
                and give back to the community that built you.
            </p>

            {{-- BIG SEARCH BAR --}}
            <div class="hero-search-container shadow-lg">
                <form action="{{ route('public.directory') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control hero-search-input" 
                           placeholder="Search Alumni by Name, Batch, or Course..." 
                           required>
                    <button class="btn hero-search-btn" type="submit">
                        <i class="fas fa-search me-2"></i> SEARCH
                    </button>
                </form>
            </div>

            <div class="d-flex justify-content-center flex-wrap mt-4">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" style="color:white; text-decoration:underline; font-weight:600; font-size:14px; margin:0 15px;">Join the Community</a>
                @endif
                <a href="{{ route('login') }}" style="color:white; text-decoration:underline; font-weight:600; font-size:14px; margin:0 15px;">Member Login</a>
            </div>
        </div>
    </section>

    {{-- MEMBERSHIP BENEFITS SECTION --}}
    <section class="benefits-section">
        <div class="container text-center">
            
            <span class="section-badge">MEMBERSHIP BENEFITS</span>
            <h2 class="section-title">Why join the KSU Alumni Association?</h2>

            <div class="row g-4">
                {{-- Card 1: Networking --}}
                <div class="col-md-4">
                    <div class="benefit-card shadow-sm">
                        <i class="fas fa-users benefit-icon"></i>
                        <h4 class="fw-bold mb-3">Networking</h4>
                        <p class="text-muted">
                            Reconnect with classmates and expand your professional circle through our exclusive directory.
                        </p>
                    </div>
                </div>

                {{-- Card 2: Career Support --}}
                <div class="col-md-4">
                    <div class="benefit-card shadow-sm">
                        <i class="fas fa-briefcase benefit-icon"></i>
                        <h4 class="fw-bold mb-3">Career Support</h4>
                        <p class="text-muted">
                            Access exclusive job postings, career development tools, and mentorship opportunities.
                        </p>
                    </div>
                </div>

                {{-- Card 3: University Events --}}
                <div class="col-md-4">
                    <div class="benefit-card shadow-sm">
                        <i class="fas fa-calendar-alt benefit-icon"></i>
                        <h4 class="fw-bold mb-3">University Events</h4>
                        <p class="text-muted">
                            Get priority invitations to homecoming, reunions, webinars, and special campus events.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

   {{-- 🟢 PROFESSIONAL FOOTER --}}
    <footer style="background-color: #0B3D2E; color: white; border-top: 4px solid #FFC72C; padding-top: 60px;">
        <div class="container">
            <div class="row">
                
                {{-- Left Column: Logo & Info --}}
                <div class="col-md-5 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('images/ksu-logo.png') }}" 
                             alt="KSU Logo" 
                             width="50" 
                             class="me-3"
                             onerror="this.src='{{ asset('assets/images/branding/ksu-logo.png') }}'">
                        
                        <div>
                            <h6 class="fw-bold mb-0 text-uppercase" style="font-size: 14px; letter-spacing: 1px;">Kalinga State University</h6>
                            <small class="text-white-50" style="font-size: 12px;">Alumni Association</small>
                        </div>
                    </div>
                    <p class="small text-white-50" style="max-width: 300px; line-height: 1.6;">
                        Connecting graduates, fostering excellence, and building a legacy for the future of KSU.
                    </p>
                </div>

                {{-- Middle Column: Quick Links --}}
                <div class="col-md-3 mb-4">
                    <h6 class="fw-bold text-warning mb-3" style="color: #FFC72C !important;">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none small">Home</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-white-50 text-decoration-none small">Login</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}" class="text-white-50 text-decoration-none small">Register</a></li>
                    </ul>
                </div>

                {{-- Right Column: Contact --}}
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold text-warning mb-3" style="color: #FFC72C !important;">Contact Us</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2" style="color: #FFC72C;"></i> Purok 6, Bulanao, Tabuk City, Kalinga</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2" style="color: #FFC72C;"></i> alumni@ksu.edu.ph</li>
                        <li class="mb-2"><i class="fas fa-phone me-2" style="color: #FFC72C;"></i> +63 912 345 6789</li>
                    </ul>
                </div>
            </div>

            <hr style="border-color: rgba(255,255,255,0.1); margin-top: 20px; margin-bottom: 20px;">
            
            <div class="text-center pb-4">
                <small class="text-white-50">&copy; {{ date('Y') }} Kalinga State University. All Rights Reserved.</small>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>