@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-user-edit text-success me-2"></i> Edit Staff Account</h3>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to List</a>
    </div>

    @php
        $raw = $user->role_name ?? '[]';
        $currentRoles = is_array(json_decode($raw, true)) ? json_decode($raw, true) : [$raw];
    @endphp

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.roles.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-4">

                    <div class="col-md-12 mb-2">
                        <label class="form-label fw-bold text-primary">Account Type</label>
                        <select name="role_type" id="role_type" class="form-select form-select-lg fw-bold bg-light" required>
                            <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>System Administrator (Custom Privileges)</option>
                            <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>University Registrar (Document Center Access)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <div class="col-md-12 my-3" id="permissions_section" style="{{ $user->role == 3 ? 'display:none;' : '' }}">
                        <label class="form-label fw-bold text-success border-bottom pb-2 mb-3 d-block">
                            <i class="fas fa-key me-2"></i>Update Access Privileges
                        </label>
                        
                        <div class="form-check p-3 border border-danger rounded-3 bg-danger bg-opacity-10 mb-4">
                            <input class="form-check-input ms-0 me-2 border-danger" type="checkbox" name="role_name[]" value="super_admin" id="role_super" {{ in_array('super_admin', $currentRoles) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-danger w-100" for="role_super">
                                SUPER ADMIN <small class="fw-normal text-dark ms-2">(Grants access to absolutely everything)</small>
                            </label>
                        </div>

                        <div class="row g-4">
                            {{-- Control Center --}}
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm rounded-4">
                                    <div class="card-header bg-success text-white fw-bold" style="font-size: 0.8rem; letter-spacing: 1px;">CONTROL CENTER</div>
                                    <div class="card-body bg-light">
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="manage_staff" id="p_staff" {{ in_array('manage_staff', $currentRoles) ? 'checked' : '' }}><label class="form-check-label" for="p_staff">Staff Management</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="mis_sync" id="p_mis" {{ in_array('mis_sync', $currentRoles) ? 'checked' : '' }}><label class="form-check-label" for="p_mis">MIS Synchronization</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="alumni_list" id="p_alumni" {{ in_array('alumni_list', $currentRoles) ? 'checked' : '' }}><label class="form-check-label" for="p_alumni">Master Alumni List</label></div>
                                        <div class="form-check"><input class="form-check-input" type="checkbox" name="role_name[]" value="pending_claims" id="p_pending" {{ in_array('pending_claims', $currentRoles) ? 'checked' : '' }}><label class="form-check-label" for="p_pending">Pending Claims</label></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Campus Engagement --}}
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm rounded-4">
                                    <div class="card-header bg-success text-white fw-bold" style="font-size: 0.8rem; letter-spacing: 1px;">CAMPUS ENGAGEMENT</div>
                                    <div class="card-body bg-light">
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="manage_events" id="p_events" {{ in_array('manage_events', $currentRoles) ? 'checked' : '' }}><label class="form-check-label" for="p_events">University Events</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="manage_news" id="p_news" {{ in_array('manage_news', $currentRoles) ? 'checked' : '' }}><label class="form-check-label" for="p_news">News & Announcements</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="manage_jobs" id="p_jobs" {{ in_array('manage_jobs', $currentRoles) ? 'checked' : '' }}><label class="form-check-label" for="p_jobs">Job Postings</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="tracer_analytics" id="p_tracer" {{ in_array('tracer_analytics', $currentRoles) ? 'checked' : '' }}><label class="form-check-label" for="p_tracer">Tracer Analytics</label></div>
                                        <div class="form-check"><input class="form-check-input" type="checkbox" name="role_name[]" value="generate_reports" id="p_reports" {{ in_array('generate_reports', $currentRoles) ? 'checked' : '' }}><label class="form-check-label" for="p_reports">Reports & Printing</label></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Communication --}}
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm rounded-4">
                                    <div class="card-header bg-success text-white fw-bold" style="font-size: 0.8rem; letter-spacing: 1px;">COMMUNICATION</div>
                                    <div class="card-body bg-light">
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="email_blast" id="p_email" {{ in_array('email_blast', $currentRoles) ? 'checked' : '' }}><label class="form-check-label" for="p_email">New Email Blast</label></div>
                                        <div class="form-check"><input class="form-check-input" type="checkbox" name="role_name[]" value="email_history" id="p_history" {{ in_array('email_history', $currentRoles) ? 'checked' : '' }}><label class="form-check-label" for="p_history">History Log</label></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success fw-bold px-4"><i class="fas fa-save me-2"></i> Update Staff Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role_type');
        const permSection = document.getElementById('permissions_section');

        roleSelect.addEventListener('change', function() {
            if (this.value == '3') {
                permSection.style.display = 'none';
            } else {
                permSection.style.display = 'block';
            }
        });
    });
</script>
@endsection