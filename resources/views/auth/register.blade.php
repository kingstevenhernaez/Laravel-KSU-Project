@extends('auth.layouts.app')

@push('title')
    {{ __('Register') }}
@endpush

@push('style')
<style>
    /* Custom Registration Page Styling */
    .register-split-screen {
        min-height: 100vh;
        display: flex;
        background-color: #f8f9fa;
    }

    /* Left Side - Image */
    .register-image-side {
        width: 50%;
        /* Using the uploaded alumni center image */
        background-image: url('{{ asset("assets/images/branding/alumni-center2.png") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Green Overlay */
    .register-image-side::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 77, 0, 0.8), rgba(0, 77, 0, 0.5)); /* KSU Green Tint */
    }

    .register-image-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: #ffffff;
        padding: 40px;
    }

    /* Right Side - Form */
    .register-form-side {
        width: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 50px 80px;
        background-color: #ffffff;
        overflow-y: auto;
    }

    .ksu-logo-register {
        width: 100px;
        margin-bottom: 20px;
    }

    .form-title {
        color: #004d00; /* KSU Green */
        font-weight: 700;
        font-size: 32px;
        margin-bottom: 10px;
    }

    .form-subtitle {
        color: #6c757d;
        margin-bottom: 30px;
    }

    .form-control-custom {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        background-color: #f8f9fa;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .form-control-custom:focus {
        border-color: #FFD700; /* KSU Gold */
        box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
        background-color: #ffffff;
    }

    .btn-ksu-register {
        background-color: #004d00; /* KSU Green */
        color: #ffffff;
        padding: 14px;
        border-radius: 50px;
        font-weight: 600;
        width: 100%;
        border: 2px solid #004d00;
        transition: all 0.3s;
    }

    .btn-ksu-register:hover {
        background-color: #ffffff;
        color: #004d00;
    }

    .login-link {
        color: #004d00;
        font-weight: 600;
        text-decoration: none;
    }

    .login-link:hover {
        color: #FFD700;
        text-decoration: underline;
    }

    /* Responsive Adjustments */
    @media (max-width: 991px) {
        .register-image-side {
            display: none; /* Hide image on mobile/tablet */
        }
        .register-form-side {
            width: 100%;
            padding: 40px 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="register-split-screen">
    
    <div class="register-image-side">
        <div class="register-image-content">
            <h1 class="display-4 fw-bold mb-3">Welcome to the KSU Community</h1>
            <p class="lead">Connect, Engage, and Grow with your Alma Mater.</p>
        </div>
    </div>

    <div class="register-form-side">
        <div class="text-center text-lg-start">
            <a href="{{ route('index') }}">
                <img src="{{ asset('assets/images/branding/ksu-logo.png') }}" alt="KSU Logo" class="ksu-logo-register">
            </a>
            <h2 class="form-title">Create Account</h2>
            <p class="form-subtitle">Enter your details to join the official alumni portal.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="fw-bold small mb-1 text-muted">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control form-control-custom" placeholder="First Name" value="{{ old('first_name') }}" required autofocus>
                        @error('first_name')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="fw-bold small mb-1 text-muted">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control form-control-custom" placeholder="Last Name" value="{{ old('last_name') }}" required>
                        @error('last_name')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="fw-bold small mb-1 text-muted">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control form-control-custom" placeholder="yourname@example.com" value="{{ old('email') }}" required>
                @error('email')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="fw-bold small mb-1 text-muted">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control form-control-custom" placeholder="Create a password" required>
                @error('password')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="fw-bold small mb-1 text-muted">Confirm Password <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" class="form-control form-control-custom" placeholder="Confirm password" required>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                <label class="form-check-label small text-muted" for="terms">
                    I agree to the <a href="#" class="login-link">Terms of Service</a> and <a href="#" class="login-link">Privacy Policy</a>.
                </label>
            </div>

            <button type="submit" class="btn btn-ksu-register">
                Sign Up
            </button>

            <div class="text-center mt-4">
                <p class="small text-muted">Already have an account? 
                    <a href="{{ route('login') }}" class="login-link">Sign In here</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection