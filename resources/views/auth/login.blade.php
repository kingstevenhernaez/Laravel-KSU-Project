@extends('auth.layouts.app')

@push('title')
    {{ __('Login') }}
@endpush

@section('content')
    <div class="register-area">
        <div class="register-wrap">
            <div class="register-left section-bg-img"
                 style="background-image: url({{ getSettingImage('login_left_image') }})">
                <div class="register-left-wrap">
                    <a class="d-inline-block mb-26" href="{{ route('index') }}">
                        <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" />
                    </a>
                    <h2 class="text-white fw-500 mb-15">{{ getOption('login_left_text_title') }}</h2>
                    <p class="text-white">{{ getOption('login_left_text_description') }}</p>
                </div>
            </div>
            <div class="register-right">
                <div class="primary-form">
                    <div class="title mb-30">
                        <h4>{{ __('KSU Alumni Login') }}</h4>
                        <p class="mt-2 text-muted">{{ __('Sign in to access your records and connect with fellow alumni.') }}</p>
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
                            <label class="form-label">{{ __('Email Address') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="example@ksu.edu.ph" required autofocus>
                            @error('email')
                                <span class="text-danger small mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-15">
                            <label class="form-label">{{ __('Password') }}</label>
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
                                <a href="{{ route('password.request') }}" class="small text-primary">{{ __('Forgot Password?') }}</a>
                            @endif
                        </div>

                        <div class="form-group mb-20">
                            <button type="submit" class="primary-btn w-100">{{ __('Login to Portal') }}</button>
                        </div>
                    </form>

                    <div class="text-center mt-30 pt-20 border-top">
                        <p class="mb-10">{{ __("First time here? Activate your access.") }}</p>
                        <a href="{{ route('claim.show') }}" class="primary-btn outline-btn w-100">
                            <i class="fa fa-user-check me-2"></i> {{ __('Claim KSU Alumni Account') }}
                        </a>
                        <p class="small text-muted mt-10">
                            {{ __('Note: You must be a verified graduate in the KSU Enrollment System to claim an account.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection