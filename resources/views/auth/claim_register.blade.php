@extends('auth.layouts.app')

@section('content')
<div class="login-container d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="card shadow-lg border-0" style="width: 100%; max-width: 550px; border-radius: 12px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 60px; height: 60px;">
                    <i class="fas fa-check fa-2x"></i>
                </div>
                <h3 class="fw-bold text-dark">Record Found!</h3>
                <p class="text-muted mb-0">Welcome back, <strong>{{ $record->first_name }} {{ $record->last_name }}</strong></p>
                <small class="text-secondary">{{ $record->course }} • Class of {{ $record->year_graduated }}</small>
            </div>

            <hr class="mb-4 opacity-25">

          <form action="{{ route('claim.register') }}" method="POST">
                @csrf
                <input type="hidden" name="record_id" value="{{ $record->id }}">
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Set Portal Email</label>
                    <input type="email" name="email" class="form-control form-control-lg bg-light @error('email') is-invalid @enderror" placeholder="Enter your active email" value="{{ old('email') }}" required>
                    @error('email') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                </div>

                {{-- 🟢 NEW MOBILE NUMBER FIELD --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Active Mobile Number</label>
                    <input type="text" name="mobile" class="form-control form-control-lg bg-light @error('mobile') is-invalid @enderror" placeholder="e.g. 09123456789" value="{{ old('mobile') }}" required>
                    @error('mobile') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Set Password</label>
                    <input type="password" name="password" class="form-control form-control-lg bg-light @error('password') is-invalid @enderror" placeholder="Minimum 8 characters" required>
                    @error('password') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-lg bg-light" placeholder="Type password again" required>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">
                    Claim Account & Login <i class="fas fa-sign-in-alt ms-2"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection