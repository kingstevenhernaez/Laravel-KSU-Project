@extends('auth.layouts.app')

@push('title')
    {{ __('Claim My Alumni Account') }}
@endpush

@section('content')
    <div class="register-area">
        <div class="register-wrap">
            <div class="register-left section-bg-img"
                 style="background-image: url({{ getSettingImage('login_left_image') }})">
                <div class="register-left-wrap">
                    <a class="d-inline-block mb-26 max-w-150" href="{{ route('index') }}">
                        <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" />
                    </a>
                    <h2 class="fs-36 fw-600 lh-34 text-white pb-8">{{ getOption('sign_up_left_text_title') }}</h2>
                    <p class="fs-16 fw-400 lh-24 text-white">{{ getOption('sign_up_left_text_subtitle') }}</p>
                </div>
            </div>
            <div class="register-right">
                <div class="primary-form">
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/images/branding/ksu-alumni-logo.png') }}" alt="KSU" style="max-height:70px">
                            <h4 class="mt-3 mb-1">{{ __('Claim My Alumni Account') }}</h4>
                            <div class="text-muted small">{{ __('Enter your Student Number to activate your account.') }}</div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('claim.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">{{ __('Student Number') }}</label>
                                <input type="text" name="student_number" value="{{ old('student_number') }}" class="form-control @error('student_number') is-invalid @enderror" required>
                                @error('student_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Set Password') }}</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Confirm Password') }}</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">{{ __('Activate Account') }}</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}">{{ __('Back to Login') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
