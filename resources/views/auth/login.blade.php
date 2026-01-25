@extends('auth.layouts.app')

@push('title')
    {{ __('Login') }}
@endpush

@section('content')
<style>
    /* =========================================
       KSU OFFICIAL LOGIN THEME (FINAL FIX)
       ========================================= */
    
    body {
        background-color: #f0f2f5 !important;
    }
    .register-area {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 40px 20px;
    }
    .register-wrap {
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        max-width: 1000px;
        width: 100%;
        display: flex;
        flex-wrap: wrap;
    }

    /* --- LEFT SIDE: THE BUILDING BACKGROUND --- */
    .register-left {
        /* 1. We FORCE the background image here */
        background-image: url('{{ asset("frontend/images/gallery/ksu-alumni-building.png") }}') !important;
        background-size: cover !important;
        background-position: center center !important;
        background-repeat: no-repeat !important;
        
        position: relative;
        width: 45%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
        text-align: center;
        min-height: 550px;
    }
    
    /* 2. The Green Tint Overlay (Essential for reading text over the photo) */
    .register-left::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        /* Semi-transparent KSU Green */
        background: rgba(11, 61, 46, 0.85); 
        z-index: 1;
    }
    
    .register-left-wrap {
        position: relative;
        z-index: 2;
        width: 100%;
    }

    /* --- RIGHT SIDE: THE FORM --- */
    .register-right {
        width: 55%;
        padding: 50px;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .primary-btn {
        background-color: #FFC72C !important;
        border: 2px solid #FFC72C !important;
        color: #0B3D2E !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-radius: 8px;
        height: 50px;
        width: 100%;
        transition: 0.3s;
    }
    .primary-btn:hover {
        background-color: #fff !important;
        color: #0B3D2E !important;
    }

    .outline-btn {
        background-color: transparent !important;
        border: 2px solid #0B3D2E !important;
        color: #0B3D2E !important;
    }
    .outline-btn:hover {
        background-color: #0B3D2E !important;
        color: #ffffff !important;
    }

    .form-control {
        height: 50px;
        border-radius: 8px;
        border: 1px solid #e1e1e1;
    }
    .form-control:focus {
        border-color: #FFC72C !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 199, 44, 0.2) !important;
    }

    @media (max-width: 991px) {
        .register-left, .register-right { width: 100%; }
        .register-left { min-height: 250px; }
    }
</style>

<div class="register-area">
    <div class="register-wrap">
        
        <div class="register-left">
            <div class="register-left-wrap">
                <a class="d-inline-block mb-4" href="{{ route('index') }}">
                    <img src="{{ asset('images/ksu-logo.png') }}" 
                         alt="KSU Logo" 
                         style="max-height: 120px; width: auto; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));" />
                </a>

                <h3 class="text-white fw-bold mb-3 text-uppercase" style="letter-spacing: 1px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                    KSU ALUMNI CENTER
                </h3>
                <p class="text-white-50 fs-16" style="max-width: 80%; margin: 0 auto;">
                    {{ __('Dedicated to Service, Character, and Excellence.') }}
                </p>
            </div>
        </div>

        <div class="register-right">
            <div class="primary-form">
                <div class="title mb-4">
                    <h4 style="color:#0B3D2E; font-weight:800;">{{ __('Welcome Back!') }}</h4>
                    <p class="text-muted">{{ __('Sign in to access your alumni dashboard.') }}</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold text-dark">{{ __('Email Address') }}</label>
                        <input type="email" name="email" class="form-control" placeholder="example@ksu.edu.ph" required autofocus>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold text-dark">{{ __('Password') }}</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small text-muted" for="remember">{{ __('Remember Me') }}</label>
                        </div>
                    </div>

                    <button type="submit" class="primary-btn">{{ __('Sign In') }}</button>
                </form>

                <div class="text-center mt-5 pt-4 border-top">
                    <p class="mb-3 fw-600 text-muted">{{ __("Don't have an account yet?") }}</p>
                    <a href="{{ route('claim.show') }}" class="primary-btn outline-btn d-flex align-items-center justify-content-center">
                        {{ __('Claim KSU Alumni Account') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection