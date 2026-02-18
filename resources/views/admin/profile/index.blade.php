@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="fas fa-user-circle text-success me-2"></i> My Profile</h3>
            <p class="text-muted small mb-0">Manage your account details and security settings.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Profile Information Card --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body text-center p-4">
                    <div class="avatar-circle mx-auto bg-success text-white fw-bold rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    
                    <hr class="opacity-10">
                    
                    <div class="text-start mt-3">
                        <p class="text-muted fw-bold mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">ASSIGNED ROLES:</p>
                        @php
                            $raw = $user->role_name ?? '[]';
                            $decoded = json_decode($raw, true);
                            $userRoles = is_array($decoded) ? $decoded : [$raw];
                        @endphp

                        @if($user->role == 1 || in_array('super_admin', $userRoles))
                            <span class="badge bg-danger px-3 py-2 rounded-pill text-uppercase fw-bold"><i class="fas fa-star me-1"></i> Super Admin</span>
                        @else
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($userRoles as $role)
                                    @if(!empty($role) && $role !== '[]')
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2 fw-bold">
                                        {{ ucwords(str_replace('_', ' ', $role)) }}
                                    </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Change Password Card --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-dark"><i class="fas fa-shield-alt text-warning me-2"></i> Security Settings</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Current Password</label>
                            <input type="password" name="current_password" class="form-control" placeholder="Enter your current password" required>
                            <small class="text-muted">You must verify your current password to make changes.</small>
                        </div>
                        
                        <hr class="opacity-10 mb-4">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="At least 8 characters" required minlength="8">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Type password again" required minlength="8">
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-warning fw-bold px-4 text-dark shadow-sm rounded-pill">
                                <i class="fas fa-key me-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection