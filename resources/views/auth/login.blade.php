@extends('auth.layouts.app')

@push('title')
    {{ __('Login') }}
@endpush

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ---------------------------------------------------------
       1. CORE LAYOUT
    --------------------------------------------------------- */
    body {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .login-container {
        min-height: 100vh;
        display: flex;
        background-color: #ffffff;
    }

    /* 🟢 LEFT SIDE: Visual Brand Experience (UPDATED) */
    .login-visual {
        flex: 1;
        position: relative;
        /* Ensure this path is correct */
        background-image: url('{{ asset("assets/images/branding/alumni-center2.png") }}');
        background-size: cover;
        background-position: center;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Green Gradient Overlay */
    .login-visual::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(0, 77, 0, 0.92) 0%, rgba(20, 108, 67, 0.85) 100%);
    }

    .visual-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: white;
        padding: 60px;
        max-width: 650px; /* 🟢 Wider container */
    }

    /* 🟢 NEW STYLES FOR LEFT TEXT */
    .welcome-headline {
        font-size: 4rem; /* 🟢 Much Bigger */
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -1px;
        margin-bottom: 20px;
        text-shadow: 0 4px 15px rgba(0,0,0,0.3); /* Pop effect */
    }

    .welcome-subtext {
        font-size: 1.4rem; /* 🟢 Bigger Subtitle */
        font-weight: 400;
        opacity: 0.95;
        line-height: 1.6;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    /* Gold Accent Bar */
    .accent-bar {
        width: 80px;
        height: 6px;
        background-color: #FFD700; /* KSU Gold */
        margin: 0 auto 30px auto;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    /* ---------------------------------------------------------
       2. RIGHT SIDE: Form Wrapper
    --------------------------------------------------------- */
    .login-form-wrapper {
        width: 550px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px;
        background-color: #ffffff;
        position: relative;
    }

    /* ---------------------------------------------------------
       3. BIGGER INPUTS & ICONS
    --------------------------------------------------------- */
    .input-group-custom {
        position: relative;
        margin-bottom: 25px;
    }

    .input-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 1.4rem;
        z-index: 10;
        transition: color 0.3s ease;
    }

    .form-control-modern {
        width: 100%;
        height: 60px;
        padding-left: 60px;
        padding-right: 20px;
        font-size: 1.25rem; /* ~20px text */
        font-weight: 500;
        border: 2px solid #f1f3f5;
        background-color: #f8f9fa;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #212529;
    }

    .form-control-modern:focus {
        background-color: #ffffff;
        border-color: #198754;
        box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.1);
        outline: none;
    }

    .form-control-modern:focus + .input-icon,
    .input-group-custom:focus-within .input-icon {
        color: #198754;
    }

    .password-toggle {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 1.3rem;
        cursor: pointer;
        z-index: 10;
        background: none;
        border: none;
        padding: 0;
    }

    .password-toggle:hover {
        color: #198754;
    }

    /* ---------------------------------------------------------
       4. BUTTONS & ACTIONS
    --------------------------------------------------------- */
    .brand-logo {
        width: 90px;
        margin-bottom: 25px;
        transition: transform 0.3s;
    }
    
    .brand-logo:hover {
        transform: scale(1.05);
    }

    .btn-premium {
        width: 100%;
        height: 60px;
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.25rem;
        font-weight: 700;
        box-shadow: 0 10px 20px -10px rgba(25, 135, 84, 0.5);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px -10px rgba(25, 135, 84, 0.6);
        color: white;
    }

    /* Claim Account Button */
    .btn-claim {
        display: block;
        width: 100%;
        padding: 15px;
        border: 2px dashed #198754;
        color: #198754;
        text-align: center;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.15rem;
        background-color: rgba(25, 135, 84, 0.05);
        transition: all 0.2s;
    }

    .btn-claim:hover {
        background-color: #198754;
        color: white;
        border-style: solid;
    }

    /* ---------------------------------------------------------
       5. MOBILE RESPONSIVENESS
    --------------------------------------------------------- */
    @media (max-width: 991px) {
        .login-visual { display: none; }
        .login-form-wrapper { width: 100%; padding: 40px 25px; }
    }
</style>
@endpush

@section('content')
<div class="login-container">
    
    {{-- 🟢 LEFT SIDE: Visual Brand Experience (UPDATED) --}}
    <div class="login-visual">
        <div class="visual-content">
            {{-- Added Gold Bar for Style --}}
            <div class="accent-bar"></div>
            
            <h1 class="welcome-headline">Welcome Home!</h1>
            
            <p class="welcome-subtext">
                Your academic records are already synced.<br>
                Simply claim your account to get started.
            </p>
        </div>
    </div>

    {{-- RIGHT SIDE: Login Form --}}
    <div class="login-form-wrapper">
        <div class="text-center text-lg-start mb-4">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/branding/ksu-logo.png') }}" 
                     alt="KSU Logo" 
                     class="brand-logo"
                     onerror="this.style.display='none'">
            </a>
            <h2 class="fw-bold text-dark mb-1">Member Login</h2>
            <p class="text-muted" style="font-size: 1.1rem;">Enter your verified email to continue.</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email Input --}}
            <div class="input-group-custom">
                <i class="fas fa-envelope input-icon"></i>
                <input id="email" type="email" name="email" 
                       class="form-control-modern @error('email') is-invalid @enderror" 
                       placeholder="Email Address" value="{{ old('email') }}" required autofocus inputmode="email">
                @error('email')
                    <span class="text-danger small mt-1 d-block fw-bold"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</span>
                @enderror
            </div>

            {{-- Password Input --}}
            <div class="input-group-custom">
                <i class="fas fa-lock input-icon"></i>
                <input id="password" type="password" name="password" 
                       class="form-control-modern @error('password') is-invalid @enderror" 
                       placeholder="Password" required>
                <button type="button" class="password-toggle" onclick="togglePassword()">
                    <i class="far fa-eye" id="toggleIcon"></i>
                </button>
                @error('password')
                    <span class="text-danger small mt-1 d-block fw-bold"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</span>
                @enderror
            </div>

            {{-- Options --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="transform: scale(1.2);">
                    <label class="form-check-label text-muted ms-1" for="remember" style="font-size: 1.05rem;">Remember me</label>
                </div>
                @if (Route::has('password.request'))
                    <a class="text-success fw-bold text-decoration-none" style="font-size: 1.05rem;" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-premium mb-4">Sign In</button>

            {{-- Claim Account Section --}}
            <div class="text-center position-relative">
                <p class="text-muted small mb-3 position-relative bg-white d-inline-block px-2" style="z-index: 2; font-size: 1rem;">First time here?</p>
                <div class="position-absolute w-100 border-top" style="top: 50%; left: 0; z-index: 1;"></div>
                
                <a href="#" class="btn-claim">
                    <i class="fas fa-search me-2"></i> Find & Claim Your Account
                </a>
                <p class="text-muted small mt-2 fst-italic">
                    *Records are synced from the Registrar. No need to register manually.
                </p>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection