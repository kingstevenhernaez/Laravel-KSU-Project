@extends('auth.layouts.app')

@section('content')
<div class="login-container d-flex justify-content-center align-items-center py-5" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="card shadow-lg border-0" style="width: 100%; max-width: 500px; border-radius: 12px;">
        <div class="card-body p-5">
            
            <div class="text-center mb-4">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 60px; height: 60px;">
                    <i class="fas fa-unlock-alt fa-2x"></i>
                </div>
                <h2 class="fw-bold text-dark">Create New Password</h2>
                <p class="text-muted">Please enter your new password below. Make sure it is at least 8 characters long.</p>
            </div>

            {{-- THE FIX: Uses standard Laravel password.update route --}}
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                {{-- Hidden Password Reset Token (Required by Laravel) --}}
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email Field (Read-only for security) --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg bg-light @error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" required readonly>
                    @error('email') 
                        <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span> 
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">New Password</label>
                    <input type="password" name="password" class="form-control form-control-lg bg-light @error('password') is-invalid @enderror" placeholder="Minimum 8 characters" required autofocus>
                    @error('password') 
                        <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span> 
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-lg bg-light" placeholder="Type password again" required>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">
                    Save New Password <i class="fas fa-check-circle ms-2"></i>
                </button>
            </form>

        </div>
    </div>
</div>
@endsection