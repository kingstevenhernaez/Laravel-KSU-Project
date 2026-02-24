@extends('layouts.alumni')

@section('content')
<div class="container py-4">
    <div class="row">
        
        {{-- LEFT COLUMN: Profile Card --}}
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="mb-3 position-relative d-inline-block">
                    @if($user->image)
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
                
                {{-- 🟢 FIXED: Display actual Course and Year Graduated from DB --}}
                <p class="text-success small fw-bold mb-2">{{ $user->course ?? 'Course Not Specified' }}</p>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                    Class of {{ $user->year_graduated ?? 'N/A' }}
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
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                            
                            {{-- MIS Integrity Notification --}}
                            <div class="col-12 mb-2">
                                <div class="alert alert-info py-2 small mb-0 border-info border-opacity-25 bg-info bg-opacity-10">
                                    <i class="fas fa-info-circle me-1 text-info"></i> <strong>Note:</strong> Your First Name and Last Name are securely synchronized with the KSU MIS. To update these official records, please contact the Registrar's Office.
                                </div>
                            </div>

                            <div class="col-12 mb-2 mt-3">
                                <label class="form-label small text-muted fw-bold">Upload New Profile Picture</label>
                                <input type="file" name="image" class="form-control">
                                <small class="text-muted">Recommended: Square JPG/PNG, max 2MB.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted">First Name <i class="fas fa-lock small ms-1 text-secondary"></i></label>
                                <input type="text" class="form-control bg-light text-muted border-secondary border-opacity-25" value="{{ $user->first_name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Last Name <i class="fas fa-lock small ms-1 text-secondary"></i></label>
                                <input type="text" class="form-control bg-light text-muted border-secondary border-opacity-25" value="{{ $user->last_name }}" readonly>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control border-primary border-opacity-50" value="{{ $user->email }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control border-primary border-opacity-50" value="{{ $user->mobile }}">
                            </div>

                            {{-- 🟢 NEW: Sensitive Career Section --}}
                            <div class="col-12 mt-4">
                                <hr>
                                <h6 class="fw-bold mb-3"><i class="fas fa-briefcase me-2 text-primary"></i> Career Information</h6>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Current Status</label>
                                <select name="employment_status" id="employmentStatus" class="form-select border-primary border-opacity-50" onchange="toggleJobFields()">
                                    <option value="">-- Select Status --</option>
                                    <option value="Employed" {{ $user->employment_status == 'Employed' ? 'selected' : '' }}>Employed / Working</option>
                                    <option value="Self-Employed" {{ $user->employment_status == 'Self-Employed' ? 'selected' : '' }}>Self-Employed / Freelance</option>
                                    <option value="Continuing Education" {{ $user->employment_status == 'Continuing Education' ? 'selected' : '' }}>Continuing Education / Studying</option>
                                    <option value="Exploring Opportunities" {{ $user->employment_status == 'Exploring Opportunities' ? 'selected' : '' }}>Currently Exploring Opportunities</option>
                                </select>
                            </div>

                            {{-- Gentle supportive note for those seeking jobs --}}
                            <div class="col-12" id="exploringNote" style="display: none;">
                                <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 py-2 small mb-0">
                                    <i class="fas fa-lightbulb text-success me-1"></i> <strong>Keep going!</strong> Don't forget to check our <a href="{{ route('alumni.jobs.index') }}" class="fw-bold text-success text-decoration-underline">Career Ops board</a> for exclusive job openings.
                                </div>
                            </div>

                            <div class="col-md-6 job-details-field">
                                <label class="form-label fw-bold">Job Title / Role</label>
                                <input type="text" name="job_title" id="jobTitle" class="form-control" value="{{ $user->job_title }}" placeholder="e.g. Software Engineer">
                            </div>
                            <div class="col-md-6 job-details-field">
                                <label class="form-label fw-bold">Company / Organization</label>
                                <input type="text" name="company" id="companyName" class="form-control" value="{{ $user->company }}" placeholder="e.g. Tech Corp Inc.">
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

<script>
    function toggleJobFields() {
        const status = document.getElementById('employmentStatus').value;
        const jobFields = document.querySelectorAll('.job-details-field');
        const exploringNote = document.getElementById('exploringNote');
        
        if (status === 'Employed' || status === 'Self-Employed') {
            jobFields.forEach(el => el.style.display = 'block');
            exploringNote.style.display = 'none';
        } else if (status === 'Exploring Opportunities') {
            jobFields.forEach(el => el.style.display = 'none');
            exploringNote.style.display = 'block';
        } else {
            jobFields.forEach(el => el.style.display = 'none');
            exploringNote.style.display = 'none';
        }
    }
    
    // Run on page load to set correct visibility
    window.onload = function() {
        toggleJobFields();
    };
</script>
@endsection