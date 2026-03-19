@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="fas fa-user-shield text-success me-2"></i> Staff Management</h3>
            <p class="text-muted small mb-0">Manage administrator and staff accounts for the alumni system.</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-success fw-bold shadow-sm rounded-pill px-4">
            <i class="fas fa-plus me-2"></i> Add New Staff
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Check if Logged In User is Super Admin --}}
    @php
        $loggedInRaw = Auth::user()->role_name ?? '[]';
        $loggedInRoles = is_array(json_decode($loggedInRaw, true)) ? json_decode($loggedInRaw, true) : [$loggedInRaw];
        $loggedInIsSuperAdmin = in_array('super_admin', $loggedInRoles);
    @endphp

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="px-4 py-3">Staff Profile</th>
                            <th class="py-3">Email Address</th>
                            <th class="py-3" style="max-width: 300px;">Access Privileges</th>
                            <th class="py-3 text-center">Date Added</th>
                            @if($loggedInIsSuperAdmin)
                                <th class="py-3 text-center">Actions</th>
                            @endif
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
                            
                          <td style="max-width: 300px;">
                                @php
                                    $raw = $user->role_name ?? '[]';
                                    $decoded = json_decode($raw, true);
                                    $userRoles = is_array($decoded) ? $decoded : [$raw];
                                @endphp

                                {{-- 🟢 NEW: Check if this is the Registrar --}}
                                @if($user->role == 3)
                                    <span class="badge bg-info text-dark px-3 py-2 rounded-pill text-uppercase fw-bold shadow-sm" style="font-size: 0.7rem;">
                                        <i class="fas fa-file-signature me-1"></i> University Registrar
                                    </span>

                                {{-- Super Admin Check --}}
                                @elseif(in_array('super_admin', $userRoles))
                                    <span class="badge bg-danger px-3 py-2 rounded-pill text-uppercase fw-bold shadow-sm" style="font-size: 0.7rem;">
                                        <i class="fas fa-star me-1"></i> Super Admin
                                    </span>

                                {{-- Standard Granular Admin Check --}}
                                @else
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($userRoles as $role)
                                            @if(!empty($role) && $role !== '[]')
                                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2 fw-bold border border-success border-opacity-25" style="font-size: 0.65rem;">
                                                {{ ucwords(str_replace('_', ' ', $role)) }}
                                            </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            
                            <td class="text-center text-muted fw-medium" style="font-size: 0.9rem;">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                            </td>

                            {{-- 🟢 UPDATED ACTIONS COLUMN: Inline Circular Buttons --}}
                            @if($loggedInIsSuperAdmin)
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Edit Button --}}
                                    <a href="{{ route('admin.roles.edit', $user->id) }}" class="btn btn-outline-primary action-btn rounded-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Staff Permissions">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- Password Button --}}
                                    <button type="button" class="btn btn-outline-warning action-btn rounded-circle" data-bs-toggle="modal" data-bs-target="#passwordModal{{ $user->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Change Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    
                                    {{-- Delete Button (Hidden for current logged in user to prevent self-deletion) --}}
                                    @if(auth()->id() != $user->id)
                                    <button type="button" class="btn btn-outline-danger action-btn rounded-circle" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Staff">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>

                            {{-- Modals specifically for this User Row --}}
                            
                            {{-- Change Password Modal --}}
                            <div class="modal fade" id="passwordModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold"><i class="fas fa-key text-warning me-2"></i> Change Password</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.roles.password', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <p class="text-muted small">Update password for <strong>{{ $user->name }}</strong>.</p>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">New Password</label>
                                                    <input type="password" name="password" class="form-control" required minlength="8">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Confirm New Password</label>
                                                    <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning fw-bold rounded-pill text-dark">Update Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Delete Confirmation Modal --}}
                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-body text-center p-4">
                                            <div class="text-danger mb-3">
                                                <i class="fas fa-exclamation-triangle fa-3x"></i>
                                            </div>
                                            <h5 class="fw-bold">Delete Staff Member?</h5>
                                            <p class="text-muted mb-4">Are you sure you want to permanently remove <strong>{{ $user->name }}</strong>? This action cannot be undone.</p>
                                            
                                            <form action="{{ route('admin.roles.destroy', $user->id) }}" method="POST" class="d-flex justify-content-center gap-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-light fw-bold rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger fw-bold rounded-pill px-4">Yes, Delete Staff</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
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
        
        @if($staff->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            {{ $staff->links() }}
        </div>
        @endif
    </div>
</div>

{{-- 🟢 NEW STYLES AND SCRIPTS FOR BUTTONS --}}
<style>
    /* Styling for the circular action buttons */
    .action-btn {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: all 0.2s ease-in-out;
    }
    .action-btn i {
        font-size: 0.9rem;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
</style>

<script>
    // Initialize Bootstrap Tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection