@extends('auth.layouts.app')

@push('title')
    {{ __('Claim Account') }}
@endpush

@section('content')
<style>
    /* =========================================
       KSU OFFICIAL BRANDING (MATCHING LOGIN)
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
    
    /* 2. The Green Tint Overlay */
    .register-left::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(11, 61, 46, 0.85); /* Deep KSU Green */
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
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .primary-btn:hover {
        background-color: #fff !important;
        color: #0B3D2E !important;
        box-shadow: 0 5px 15px rgba(255, 199, 44, 0.3);
    }

    .form-control {
        height: 50px;
        border-radius: 8px;
        border: 1px solid #e1e1e1;
        padding-left: 15px;
    }
    .form-control:focus {
        border-color: #FFC72C !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 199, 44, 0.2) !important;
    }

    /* Info Box Style */
    .ksu-alert {
        background-color: #f0fdf4;
        border: 1px solid #0B3D2E;
        color: #0B3D2E;
        padding: 15px;
        border-radius: 8px;
        font-size: 13px;
        line-height: 1.6;
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
                    Verify Your Identity
                </h3>
                <p class="text-white-50 fs-16" style="max-width: 90%; margin: 0 auto;">
                    {{ __('Only verified graduates of Kalinga State University can activate their alumni portal access.') }}
                </p>
            </div>
        </div>

        <div class="register-right">
            <div class="primary-form">
                <div class="title mb-4">
                    <h4 style="color:#0B3D2E; font-weight:800;">{{ __('Claim Account') }}</h4>
                    <p class="text-muted">{{ __('Please enter your student details below.') }}</p>
                </div>
                
                <form method="POST" action="{{ route('claim.post') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold text-dark">{{ __('KSU Student ID Number') }}</label>
                        <input type="text" name="student_number" value="{{ old('student_number') }}" 
                               class="form-control" placeholder="e.g. 2023-0001" required autofocus>
                        @error('student_number')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="ksu-alert mb-4">
                        <div class="d-flex gap-2">
                            <i class="fa fa-info-circle mt-1"></i>
                            <div>
                                <strong>Default Password Note:</strong><br>
                                After claiming, your password will be: <br>
                                <u>Your Birthdate (YYYYMMDD) + Your Student ID</u>
                                <br><span class="text-muted" style="font-size:11px;">(Example: 1998122520230001)</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <button type="submit" class="primary-btn">
                            {{ __('Activate Access') }}
                        </button>
                    </div>
                </form>

                <div class="mt-3 text-center border-top pt-4">
                    <p class="mb-0 fw-600 text-muted">{{ __('Already have an account?') }} 
                        <a href="{{ route('login') }}" style="color:#0B3D2E; font-weight:800; text-decoration:underline;">
                            {{ __('Login here') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection