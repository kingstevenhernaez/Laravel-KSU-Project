@extends('auth.layouts.app')

@section('content')
<div class="login-container d-flex justify-content-center align-items-center py-5" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="card shadow-lg border-0" style="width: 100%; max-width: 600px; border-radius: 12px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 60px; height: 60px;">
                    <i class="fas fa-check fa-2x"></i>
                </div>
                <h3 class="fw-bold text-dark">Record Found!</h3>
                <p class="text-muted mb-0">Welcome back, <strong>{{ $record->first_name }} {{ $record->last_name }}</strong></p>
                <small class="text-secondary">{{ $record->course }} • Class of {{ $record->year_graduated }}</small>
            </div>

            <form action="{{ route('claim.register') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="record_id" value="{{ $record->id }}">
                
                {{-- 🟢 THE FIX: Section 1 - Alumni Details & Single ID Upload --}}
                <div class="bg-light p-4 rounded-3 border mb-4">
                    <h6 class="fw-bold text-success mb-3"><i class="fas fa-id-card me-2"></i> Section 1: Alumni Verification</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small">Active Mobile Number</label>
                        <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" placeholder="e.g. 09123456789" value="{{ old('mobile') }}" required>
                        @error('mobile') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold text-secondary small">Upload Valid ID <span class="text-danger">*</span></label>
                        {{-- NO "multiple" attribute here guarantees exactly one file! --}}
                        <input type="file" name="valid_id" class="form-control @error('valid_id') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf" required>
                        <small class="text-muted" style="font-size: 11px;">Max 2MB. Accepts JPG, PNG, or PDF only. (Single file only)</small>
                        @error('valid_id') <span class="text-danger small fw-bold d-block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- 🟢 THE FIX: Section 2 - Account Security & Email Verification --}}
                <div class="bg-light p-4 rounded-3 border mb-4">
                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-lock me-2"></i> Section 2: Portal Security</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small">Set Portal Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter an active email for verification" value="{{ old('email') }}" required>
                        <small class="text-primary" style="font-size: 11px;"><i class="fas fa-info-circle"></i> A verification link will be sent to this email.</small>
                        @error('email') <span class="text-danger small fw-bold d-block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-secondary small">Set Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 characters" required>
                            @error('password') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-secondary small">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Type again" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">
                    Register & Send Verification Email <i class="fas fa-paper-plane ms-2"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection