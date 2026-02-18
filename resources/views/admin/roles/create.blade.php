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

                    {{-- 🟢 CHECKBOXES FOR MULTIPLE ROLES --}}
                    <div class="col-md-12 my-2">
                        <label class="form-label fw-bold text-success border-bottom pb-2 mb-3 d-block">
                            <i class="fas fa-key me-2"></i>Assign Access Privileges (Select multiple)
                        </label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check p-3 border rounded-3 bg-light h-100">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="role_name[]" value="super_admin" id="role1">
                                    <label class="form-check-label fw-bold w-100" for="role1">Super Admin<br><small class="text-muted fw-normal">Full access to everything.</small></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check p-3 border rounded-3 h-100">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="role_name[]" value="registrar" id="role2">
                                    <label class="form-check-label fw-bold w-100" for="role2">Registrar<br><small class="text-muted fw-normal">MIS Sync & Alumni List.</small></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check p-3 border rounded-3 h-100">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="role_name[]" value="news_editor" id="role3">
                                    <label class="form-check-label fw-bold w-100" for="role3">News Editor<br><small class="text-muted fw-normal">Events & News Management.</small></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check p-3 border rounded-3 h-100">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="role_name[]" value="career_coordinator" id="role4">
                                    <label class="form-check-label fw-bold w-100" for="role4">Career Coordinator<br><small class="text-muted fw-normal">Jobs & Tracer Analytics.</small></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check p-3 border rounded-3 h-100">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="role_name[]" value="communications" id="role5">
                                    <label class="form-check-label fw-bold w-100" for="role5">Communications<br><small class="text-muted fw-normal">Email Blast & History.</small></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
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
@endsection