@extends('auth.layouts.app')

@push('title')
    {{ __('Claim My KSU Alumni Account') }}
@endpush

@section('content')
    <div class="register-area">
        <div class="register-wrap">
            <div class="register-left section-bg-img"
                 style="background-image: url({{ getSettingImage('login_left_image') }})">
                <div class="register-left-wrap">
                    <a class="d-inline-block mb-26" href="{{ route('index') }}"><img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" /></a>
                    <h2 class="text-white fw-500 mb-15">{{ getOption('login_left_text_title') }}</h2>
                    <p class="text-white">{{ getOption('login_left_text_description') }}</p>
                </div>
            </div>
            <div class="register-right">
                <div class="primary-form">
                    <div class="title mb-30">
                        <h4>{{ __('Claim Your Account') }}</h4>
                        <p class="mt-2 text-muted">
                            {{ __('Enter your Student ID to activate your KSU Alumni portal access.') }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('claim.post') }}">
                        @csrf

                        <div class="form-group mb-20">
                            <label class="form-label">{{ __('KSU Student ID Number') }}</label>
                            <input type="text" name="student_number" value="{{ old('student_number') }}" 
                                   class="form-control" placeholder="e.g. 2023-0001" required autofocus>
                            @error('student_number')
                                <span class="text-danger mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-info border-0 small mb-25">
                            <i class="fa fa-info-circle me-1"></i>
                            {{ __('Note: After clicking claim, your default password will be your Birthdate (YYYYMMDD) followed by your Student ID. You can change this anytime in your profile settings.') }}
                        </div>

                        <div class="form-group">
                            <button type="submit" class="primary-btn w-100">{{ __('Claim Account') }}</button>
                        </div>
                    </form>

                    <div class="mt-20 text-center">
                        <p>{{ __('Already have an active account?') }} <a href="{{ route('login') }}" class="text-primary fw-500">{{ __('Login here') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection