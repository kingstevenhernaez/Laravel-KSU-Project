@extends('auth.layouts.app')

@push('title')
    {{ __('Login') }}
@endpush

@push('style')
<style>
    /* Split Screen Layout (Matches Register Page) */
    .login-split-screen {
        min-height: 100vh;
        display: flex;
        background-color: #f8f9fa;
    }

    /* Left Side - Image */
    .login-image-side {
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
    .login-image-side::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 77, 0, 0.85), rgba(0, 77, 0, 0.6)); /* KSU Green */
    }

    .login-image-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: #ffffff;
        padding: 40px;
    }

    /* Right Side - Form */
    .login-form-side {
        width: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 50px 80px;
        background-color: #ffffff;
    }

    .ksu-logo-login {
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

    .btn-ksu-login {
        background-color: #004d00; /* KSU Green */
        color: #ffffff;
        padding: 14px;
        border-radius: 50px;
        font-weight: 600;
        width: 100%;
        border: 2px solid #004d00;
        transition: all 0.3s;
    }

    .btn-ksu-login:hover {
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

    /* Responsive */
    @media (max-width: 991px) {
        .login-image-side { display: none; }
        .login-form-side { width: 100%; padding: 40px 20px; }
    }
</style>
@endpush

@section('content')
<div class="login-split-screen">
    
    <div class="login-image-side">
        <div class="login-image-content">
            <h1 class="display-4 fw-bold mb-3">Welcome Back!</h1>
            <p class="lead">Sign in to access the KSU Alumni Portal.</p>
        </div>
    </div>

    <div class="login-form-side">
        <div class="text-center text-lg-start">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/branding/ksu-logo.png') }}" alt="KSU Logo" class="ksu-logo-login">
            </a>
            <h2 class="form-title">Member Login</h2>
            <p class="form-subtitle">Enter your credentials to continue.</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="fw-bold small mb-1 text-muted">Email Address</label>
                <input type="email" name="email" class="form-control form-control-custom" placeholder="yourname@example.com" value="{{ old('email') }}" required autofocus>
                @error('email')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="fw-bold small mb-1 text-muted">Password</label>
                <input type="password" name="password" class="form-control form-control-custom" placeholder="••••••••" required>
                @error('password')<span class="text-danger small">{{ $message }}</span>@enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label small text-muted" for="remember">Remember Me</label>
                </div>
                @if (Route::has('password.request'))
                    <a class="small login-link" href="{{ route('password.request') }}">Forgot Password?</a>
                @endif
            </div>

            <button type="submit" class="btn btn-ksu-login">
                Sign In
            </button>

            <div class="text-center mt-4">
                <p class="small text-muted">Don't have an account? 
                    <a href="{{ route('register') }}" class="login-link">Create Account</a>
                </p>
                <p class="small text-muted mt-2">
                  <a href="#" class="text-secondary text-decoration-underline">Claim Existing Alumni Record</a>
            </div>
        </form>
    </div>
</div>
@endsection