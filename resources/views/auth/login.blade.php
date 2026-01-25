@extends('auth.layouts.app')

@push('title')
    {{ __('Login') }}
@endpush

@section('content')
<style>
    /* 1. Global Page Background */
    body {
        background-color: #f4f7f6 !important;
    }

    /* 2. Left Side Branding (KSU Green) */
    .register-left {
        background-color: #006400 !important; /* KSU Green */
        position: relative;
    }

    /* Overlay to make text readable if there is a background image */
    .register-left::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 100, 0, 0.85); /* Green Tint */
        z-index: 1;
    }

    .register-left-wrap {
        position: relative;
        z-index: 2;
    }

    /* 3. Primary Button (KSU Gold) */
    .primary-btn {
        background-color: #FFD700 !important; /* KSU Gold */
        border: 1px solid #FFD700 !important;
        color: #006400 !important; /* Green Text */
        font-weight: 700 !important;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    .primary-btn:hover {
        background-color: #004d00 !important; /* Darker Green */
        border-color: #004d00 !important;
        color: #FFD700 !important; /* Gold Text */
    }

    /* 4. Outline/Claim Account Button */
    .outline-btn {
        background-color: transparent !important;
        border: 2px solid #006400 !important;
        color: #006400 !important;
        font-weight: 600;
    }

    .outline-btn:hover {
        background-color: #006400 !important;
        color: #ffffff !important;
    }

    /* 5. Input Focus Colors */
    .form-control:focus {
        border-color: #006400 !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 100, 0, 0.15) !important;
    }

    /* 6. Typography */
    .primary-form h4 {
        color: #006400; /* Green Heading */
        font-weight: 700;
    }

    .text-primary {
        color: #006400 !important; /* Links in Green */
    }
</style>

<div class="register-area">
    <div class="register-wrap">
        <div class="register-left section-bg-img"
             style="background-image: url({{ getSettingImage('login_left_image') }})">
            <div class="register-left-wrap">
                <a class="d-inline-block mb-26" href="{{ route('index') }}">
                    <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" style="max-height: 80px;" />
                </a>
                <h2 class="text-white fw-500 mb-15">{{ __('Kalinga State University') }}</h2>
                <p class="text-white">{{ __('Dedicated to Service, Character, and Excellence.') }}</p>
            </div>
        </div>

        <div class="register-right">
            <div class="primary-form">
                <div class="title mb-30">
                    <h4>{{ __('Alumni Portal Login') }}</h4>
                    <p class="mt-2 text-muted">{{ __('Sign in to access your records and connect with fellow KSU alumni.') }}</p>
                </div>

                @if (getOption('google_login_status') == 1 || getOption('facebook_login_status') == 1)
                    <div class="social-login mb-20">
                        @if (getOption('google_login_status') == 1)
                            <a href="{{ route('login.google') }}" class="social-btn google-btn mb-10">
                                <img src="{{ asset('assets/images/google.svg') }}" alt="Google" />
                                <span>{{ __('Login with Google') }}</span>
                            </a>
                        @endif
                        @if (getOption('facebook_login_status') == 1)
                            <a href="{{ route('login.facebook') }}" class="social-btn facebook-btn">
                                <img src="{{ asset('assets/images/facebook.svg') }}" alt="Facebook" />
                                <span>{{ __('Login with Facebook') }}</span>
                            </a>
                        @endif
                    </div>
                    <div class="divider mb-20"><span>{{ __('Or login with email') }}</span></div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group mb-15">
                        <label class="form-label text-dark">{{ __('Email Address') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="example@ksu.edu.ph" required autofocus>
                        @error('email')
                            <span class="text-danger small mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-15">
                        <label class="form-label text-dark">{{ __('Password') }}</label>
                        <input type="password" name="password" class="form-control" placeholder="********" required>
                        @error('password')
                            <span class="text-danger small mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="remember">{{ __('Remember Me') }}</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small text-primary fw-bold">{{ __('Forgot Password?') }}</a>
                        @endif
                    </div>

                    <div class="form-group mb-20">
                        <button type="submit" class="primary-btn w-100 py-12">{{ __('Sign In') }}</button>
                    </div>
                </form>

                <div class="text-center mt-30 pt-20 border-top">
                    <p class="mb-10 fw-500">{{ __("First time here?") }}</p>
                    <a href="{{ route('claim.show') }}" class="primary-btn outline-btn w-100">
                        <i class="fa fa-user-check me-2"></i> {{ __('Claim KSU Alumni Account') }}
                    </a>
                    <p class="small text-muted mt-10">
                        {{ __('Verified KSU graduates can activate their portal access here.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection