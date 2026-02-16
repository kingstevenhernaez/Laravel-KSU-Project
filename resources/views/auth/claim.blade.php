@extends('auth.layouts.app')

@section('content')
<div class="login-container d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="card shadow-lg border-0" style="width: 100%; max-width: 500px; border-radius: 12px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-success">Find Your Record</h2>
                <p class="text-muted">Enter your details exactly as they appear in the University Registrar.</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger bg-danger text-white border-0 small fw-bold">
                    <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
                </div>
            @endif

          {{-- 🟢 THE FIX: Changed to GET request and route('claim.search') --}}
            <form action="{{ route('claim.search') }}" method="GET">
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Student ID Number</label>
                    <input type="text" name="student_id" class="form-control form-control-lg bg-light" placeholder="e.g. 2020-0001" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Date of Birth</label>
                    <input type="date" name="birthdate" class="form-control form-control-lg bg-light" required>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">
                    <i class="fas fa-search me-2"></i> Search Records
                </button>
            </form>
            
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Back to Login</a>
            </div>
        </div>
    </div>
</div>
@endsection