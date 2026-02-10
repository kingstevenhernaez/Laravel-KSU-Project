@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <a href="{{ route('admin.alumni.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="mb-3">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; font-size: 2rem;">
                        {{ substr($user->first_name, 0, 1) }}
                    </div>
                </div>
                <h4 class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                
                <hr>

                <div class="mb-3">
                    Status: 
                    @if($user->status == 1)
                        <span class="badge bg-success">Active / Verified</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending Approval</span>
                    @endif
                </div>

                <form action="{{ route('admin.alumni.status', $user->id) }}" method="POST">
                    @csrf
                    @if($user->status == 0)
                        <button type="submit" class="btn btn-success w-100 fw-bold">
                            <i class="fas fa-check-circle me-2"></i> Approve Alumni
                        </button>
                    @else
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-ban me-2"></i> Deactivate Account
                        </button>
                    @endif
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold text-success py-3">
                    Alumni Information
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-muted">Phone Number</label>
                            <p class="fw-bold">{{ $user->mobile ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">City / Address</label>
                            <p class="fw-bold">{{ $user->city ?? 'N/A' }}</p>
                        </div>
                        
                        <hr>

                        <div class="col-md-6">
                            <label class="small text-muted">Department</label>
                            <p class="fw-bold">{{ $user->department ?? 'Not Set' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Graduation Batch</label>
                            <p class="fw-bold">{{ $user->batch ?? 'Not Set' }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-muted">Student ID Number</label>
                            <p class="fw-bold">{{ $user->id_number ?? 'Not Set' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection