@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="fas fa-user-shield text-success me-2"></i> Staff Management</h3>
            <p class="text-muted small mb-0">Manage administrator and staff accounts for the alumni system.</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-success fw-bold shadow-sm rounded-pill px-4">
            <i class="fas fa-plus me-2"></i> Add New Staff
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Staff Table --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="px-4 py-3">Staff Profile</th>
                            <th class="py-3">Email Address</th>
                            <th class="py-3">Access Privilege</th>
                            <th class="py-3 text-center">Date Added</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($staff as $user)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3 bg-success text-white fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                        {{ strtoupper(substr($user->first_name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $user->first_name }} {{ $user->last_name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $user->email }}</td>
                            
                            {{-- 🟢 ACCESS PRIVILEGE COLUMN --}}
                            <td>
                                @php
                                    $raw = $user->role_name ?? '[]';
                                    $decoded = json_decode($raw, true);
                                    $userRoles = is_array($decoded) ? $decoded : [$raw];
                                @endphp

                                {{-- If Role is 1 OR array contains super_admin, show the red badge --}}
                                @if($user->role == 1 || in_array('super_admin', $userRoles))
                                    <span class="badge bg-danger px-3 py-2 rounded-pill text-uppercase fw-bold" style="font-size: 0.7rem;">
                                        <i class="fas fa-star me-1"></i> Super Admin
                                    </span>
                                @else
                                    {{-- Otherwise, print a badge for every role they have --}}
                                    @foreach($userRoles as $role)
                                        @if(!empty($role) && $role !== '[]')
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2 text-uppercase fw-bold mb-1 d-inline-block" style="font-size: 0.65rem;">
                                            {{ str_replace('_', ' ', $role) }}
                                        </span>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            
                            <td class="text-center text-muted fw-medium" style="font-size: 0.9rem;">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                            </td>
                        </tr>
                        
                        {{-- 🔴 THIS @empty BLOCK WAS MISSING IN YOUR ERROR LOG --}}
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-users-slash fa-3x mb-3 text-light"></i>
                                <h5>No staff members found</h5>
                                <p>Click the "Add New Staff" button to create your first sub-admin.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination --}}
        @if($staff->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            {{ $staff->links() }}
        </div>
        @endif
    </div>
</div>
@endsection