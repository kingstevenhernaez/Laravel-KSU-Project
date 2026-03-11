@extends('auth.layouts.app')

@section('content')
<div class="login-container d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="card shadow-lg border-0" style="width: 100%; max-width: 500px; border-radius: 12px;">
        <div class="card-body p-5">
            
            <div class="text-center mb-4">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 60px; height: 60px;">
                    <i class="fas fa-key fa-2x"></i>
                </div>
                <h2 class="fw-bold text-dark">Reset Password</h2>
                <p class="text-muted">Enter your email address below and we'll send you a link to reset your password.</p>
            </div>

            {{-- Success Message --}}
            @if (session('status'))
                <div class="alert alert-success bg-success bg-opacity-10 text-success border-success border-opacity-25 small fw-bold mb-4">
                    <i class="fas fa-check-circle me-1"></i> {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg bg-light @error('email') is-invalid @enderror" placeholder="Enter your registered email" value="{{ old('email') }}" required autofocus>
                    @error('email') 
                        <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span> 
                    @enderror
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">
                    Send Password Reset Link <i class="fas fa-paper-plane ms-2"></i>
                </button>
            </form>
            
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-secondary fw-bold text-decoration-none hover-success">
                    <i class="fas fa-arrow-left me-1"></i> Back to Login
                </a>
            </div>

        </div>
    </div>
</div>

<style>
    .hover-success:hover { color: #198754 !important; transition: 0.2s; }
</style>
@endsection