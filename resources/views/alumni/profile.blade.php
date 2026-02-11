@extends('layouts.alumni')

@section('content')
<div class="container py-4">
    <div class="row">
        
        {{-- LEFT COLUMN: Profile Card --}}
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="mb-3 position-relative d-inline-block">
                    {{-- Profile Image Preview --}}
                    @if($user->image)
                        {{-- 🟢 FIX: Added 'storage/' prefix so images load correctly --}}
                        <img src="{{ asset('storage/' . $user->image) }}" class="rounded-circle border border-3 border-warning" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mx-auto border border-3 border-warning" 
                             style="width: 150px; height: 150px; font-size: 50px;">
                            {{ substr($user->first_name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <h5 class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h5>
                <p class="text-muted small mb-1">{{ $user->email }}</p>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                    Batch {{ $user->batch ?? 'N/A' }}
                </span>
                
                <hr class="my-4">
                
                <div class="text-start">
                    <small class="text-uppercase text-muted fw-bold" style="font-size: 11px;">Verification Status</small>
                    <div class="d-flex align-items-center mt-2">
                        @if($user->status == 1)
                            <i class="fas fa-check-circle text-success me-2 fa-lg"></i>
                            <span class="fw-bold text-dark">Verified Alumni</span>
                        @else
                            <i class="fas fa-clock text-warning me-2 fa-lg"></i>
                            <span class="fw-bold text-dark">Pending Approval</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Forms --}}
        <div class="col-md-8">
            
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- UPDATE INFO FORM --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2 text-primary"></i> Update Profile Information</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('alumni.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            {{-- Image Upload --}}
                            <div class="col-12 mb-2">
                                <label class="form-label small text-muted fw-bold">Upload New Profile Picture</label>
                                <input type="file" name="image" class="form-control">
                                <small class="text-muted">Recommended: Square JPG/PNG, max 2MB.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" value="{{ $user->mobile }}">
                            </div>
                            
                            {{-- 🟢 FIX: Added 'name="email"' so the controller receives the value --}}
                            <div class="col-md-6">
                                <label class="form-label">Email Address (Read Only)</label>
                                <input type="email" name="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CHANGE PASSWORD FORM --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-lock me-2 text-warning"></i> Change Password</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('alumni.profile.password') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-warning text-dark fw-bold px-4">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection