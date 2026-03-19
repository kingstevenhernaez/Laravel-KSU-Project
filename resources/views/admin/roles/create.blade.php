@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-user-plus text-success me-2"></i> Create Staff Account</h3>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to List</a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    
                    {{-- 🟢 NEW: Account Type Selector --}}
                    <div class="col-md-12 mb-2">
                        <label class="form-label fw-bold text-primary">Account Type</label>
                        <select name="role_type" id="role_type" class="form-select form-select-lg fw-bold bg-light" required>
                            <option value="1">System Administrator (Custom Privileges)</option>
                            <option value="3">University Registrar (Document Center Access)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    {{-- 🟢 GRANULAR PERMISSIONS (Wrapped in a div to hide when Registrar is selected) --}}
                    <div class="col-md-12 my-3" id="permissions_section">
                        <label class="form-label fw-bold text-success border-bottom pb-2 mb-3 d-block">
                            <i class="fas fa-key me-2"></i>Assign Specific Access Privileges
                        </label>
                        
                        {{-- Super Admin Override --}}
                        <div class="form-check p-3 border border-danger rounded-3 bg-danger bg-opacity-10 mb-4">
                            <input class="form-check-input ms-0 me-2 border-danger" type="checkbox" name="role_name[]" value="super_admin" id="role_super">
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
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="manage_staff" id="p_staff"><label class="form-check-label" for="p_staff">Staff Management</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="mis_sync" id="p_mis"><label class="form-check-label" for="p_mis">MIS Synchronization</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="alumni_list" id="p_alumni"><label class="form-check-label" for="p_alumni">Master Alumni List</label></div>
                                        <div class="form-check"><input class="form-check-input" type="checkbox" name="role_name[]" value="pending_claims" id="p_pending"><label class="form-check-label" for="p_pending">Pending Claims</label></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Campus Engagement --}}
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm rounded-4">
                                    <div class="card-header bg-success text-white fw-bold" style="font-size: 0.8rem; letter-spacing: 1px;">CAMPUS ENGAGEMENT</div>
                                    <div class="card-body bg-light">
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="manage_events" id="p_events"><label class="form-check-label" for="p_events">University Events</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="manage_news" id="p_news"><label class="form-check-label" for="p_news">News & Announcements</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="manage_jobs" id="p_jobs"><label class="form-check-label" for="p_jobs">Job Postings</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="tracer_analytics" id="p_tracer"><label class="form-check-label" for="p_tracer">Tracer Analytics</label></div>
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="generate_reports" id="role_reports"><label class="form-check-label" for="role_reports">Reports & Printing</label></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Communication --}}
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm rounded-4">
                                    <div class="card-header bg-success text-white fw-bold" style="font-size: 0.8rem; letter-spacing: 1px;">COMMUNICATION</div>
                                    <div class="card-body bg-light">
                                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="role_name[]" value="email_blast" id="p_email"><label class="form-check-label" for="p_email">New Email Blast</label></div>
                                        <div class="form-check"><input class="form-check-input" type="checkbox" name="role_name[]" value="email_history" id="p_history"><label class="form-check-label" for="p_history">History Log</label></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-4">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <label class="form-label fw-bold">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success fw-bold px-4"><i class="fas fa-save me-2"></i> Create Staff Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 🟢 NEW: JavaScript to hide checkboxes if Registrar is selected --}}
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