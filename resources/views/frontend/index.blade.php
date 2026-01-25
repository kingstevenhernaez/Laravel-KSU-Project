@extends('frontend.layouts.app')

@push('title')
    {{ __('Home') }}
@endpush

@section('content')

<style>
    /* 1. Header & Nav */
    header, .main-menu-area, .bg-header-color { background-color: #004d00 !important; }
    .main-menu ul li a, .contact-us-link { color: #ffffff !important; font-weight: 600; }
    .main-menu ul li a:hover { color: #FFD700 !important; }

    /* 2. Newsletter */
    .subscribe-section, .bg-cdef84 { background-color: #FFD700 !important; }
    .subscribe-section h2, .subscribe-section p { color: #004d00 !important; font-weight: 700; }

    /* 3. Footer */
    footer, .footer-area { background-color: #004d00 !important; border-top: 4px solid #FFD700 !important; }
    footer h4, footer a, footer p { color: #ffffff !important; }
    footer a:hover { color: #FFD700 !important; }
    .footer-social-link a { background-color: #FFD700 !important; color: #004d00 !important; }
</style>

<section class="hero-area position-relative d-flex align-items-center justify-content-center" 
         style="background: linear-gradient(rgba(11, 61, 46, 0.85), rgba(11, 61, 46, 0.7)), url('{{ asset('frontend/images/ksu-campus-bg.jpg') }}'); 
                background-size: cover; background-position: center; height: 90vh; min-height: 700px; margin-bottom: 0;">

    <div class="container position-relative z-index-1 text-center">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <span class="d-inline-block py-2 px-4 mb-4 rounded-pill fw-bold text-uppercase" 
                      style="background-color: #FFC72C; color: #0B3D2E; font-size: 14px; letter-spacing: 2px;">
                    Official Alumni Portal
                </span>
                
                <h1 class="display-3 fw-800 text-white mb-4 text-uppercase" style="letter-spacing: 1px; text-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                    Welcome Home, <br><span style="color: #FFC72C;">KSU Graduates</span>
                </h1>
                
                <p class="lead text-white-50 mb-5 mx-auto" style="max-width: 700px; font-size: 1.25rem;">
                    Reconnect with your alma mater, expand your professional network, and give back to the community that built you.
                </p>
                
                <div class="d-flex justify-content-center gap-3">
                    @auth
                        <a href="{{ route('profile') }}" class="btn btn-lg fw-bold px-5 py-3" 
                           style="background-color: #FFC72C; color: #0B3D2E; border-radius: 50px; font-weight:800;">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('claim.show') }}" class="btn btn-lg fw-bold px-5 py-3" 
                           style="background-color: #FFC72C; color: #0B3D2E; border-radius: 50px; border: 2px solid #FFC72C; font-weight:800;">
                            Join the Community
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-lg fw-bold px-5 py-3 text-white" 
                           style="border-radius: 50px; border: 2px solid #ffffff;">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

<section class="joinCommunity-section pb-110 pt-100 mt-5" style="background-color: #f9f9f9;">
    <div class="container">
        <div class="text-center mb-50">
            <span class="d-inline-block py-2 px-4 bd-ra-12 fs-16 fw-600 lh-18 mb-3" 
                  style="background-color: #e0e0e0; color: #555; text-transform:uppercase; letter-spacing:1px;">
                Membership Benefits
            </span>
            <h4 class="fs-36 fw-800 lh-36 text-black pb-42">Why join the KSU Alumni Association?</h4>
        </div>
        
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="bd-one bd-c-black-10 bd-ra-14 px-26 pt-51 pb-35 bg-white text-center hover-scale-1-1 h-100 shadow-sm">
                    <div style="font-size:40px; color:#FFC72C; margin-bottom:20px;"><i class="fa-solid fa-users"></i></div>
                    <h4 class="fs-24 fw-700 lh-28 text-black-color pb-9">Networking</h4>
                    <p class="fs-18 fw-400 lh-28 text-para-color">Reconnect with classmates and expand your professional circle.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bd-one bd-c-black-10 bd-ra-14 px-26 pt-51 pb-35 bg-white text-center hover-scale-1-1 h-100 shadow-sm">
                    <div style="font-size:40px; color:#FFC72C; margin-bottom:20px;"><i class="fa-solid fa-briefcase"></i></div>
                    <h4 class="fs-24 fw-700 lh-28 text-black-color pb-9">Career Support</h4>
                    <p class="fs-18 fw-400 lh-28 text-para-color">Access exclusive job postings and career development tools.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bd-one bd-c-black-10 bd-ra-14 px-26 pt-51 pb-35 bg-white text-center hover-scale-1-1 h-100 shadow-sm">
                    <div style="font-size:40px; color:#FFC72C; margin-bottom:20px;"><i class="fa-solid fa-calendar-days"></i></div>
                    <h4 class="fs-24 fw-700 lh-28 text-black-color pb-9">University Events</h4>
                    <p class="fs-18 fw-400 lh-28 text-para-color">Get priority invitations to homecoming and special events.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pt-110 pb-110 position-relative">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="d-inline-block py-2 px-4 bd-ra-12 fs-16 fw-600 lh-18 mb-3" 
                      style="background-color: #FFD700; color: #004d00; text-transform:uppercase; letter-spacing:1px;">
                    Success Stories
                </span>
                <h4 class="fs-50 fw-800 lh-60 text-black-color pb-38">KSU Alumni Journeys</h4>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ route('login') }}" class="btn btn-lg fw-bold text-white px-5 py-3" style="background-color: #004d00; border-radius: 12px;">
                    Read Stories <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection