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

                {{-- Manual "Remove Photo" Button --}}
                @if($user->image)
                    <form action="{{ route('alumni.profile.remove_photo') }}" method="POST" class="mb-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm" onclick="return confirm('Permanently delete your profile picture?')">
                            <i class="fas fa-trash-alt me-1"></i> Remove Photo
                        </button>
                    </form>
                @endif

                <h5 class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h5>
                <p class="text-muted small mb-1">{{ $user->email }}</p>
                
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

        {{-- RIGHT COLUMN: Forms & Timeline --}}
        <div class="col-md-8">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- 1. UPDATE BASIC INFO FORM --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2 text-primary"></i> Basic Profile Information</h6>
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
                                <input type="file" name="image" class="form-control" accept="image/jpeg, image/png, image/jpg">
                                <small class="text-muted d-block mt-1">Recommended: Square JPG/PNG, Max 2MB.</small>
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
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 2. NEW CAREER TIMELINE CARD (Separated safely from the profile form) --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-briefcase me-2 text-primary"></i> Career Timeline</h6>
                    <button type="button" class="btn btn-sm btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addJobModal">
                        <i class="fas fa-plus me-1"></i> Add Experience
                    </button>
                </div>
                <div class="card-body p-4">
                    <div class="timeline-container border-start border-2 border-primary ms-2 ps-4 position-relative">
                        @forelse($user->employmentHistories as $job)
                            <div class="mb-4 position-relative">
                                <span class="position-absolute bg-primary rounded-circle border border-white border-3" style="width: 16px; height: 16px; left: -33px; top: 4px;"></span>
                                
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-bold text-dark mb-0">{{ $job->job_title }}</h6>
                                    
                                    {{-- Delete Form (Now safely isolated) --}}
                                    <form action="{{ route('alumni.profile.employment.destroy', $job->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 ms-3" onclick="return confirm('Remove this experience?')" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                <p class="text-primary fw-bold mb-1 small">{{ $job->company_name }} <span class="text-muted fw-normal ms-2 border-start ps-2">{{ $job->employment_type }}</span></p>
                                
                                <p class="text-muted small">
                                    <i class="far fa-calendar-alt me-1"></i> 
                                    {{ $job->start_date->format('M Y') }} – 
                                    @if($job->is_current)
                                        <span class="text-success fw-bold">Present</span>
                                    @else
                                        {{ $job->end_date->format('M Y') }}
                                    @endif
                                </p>
                            </div>
                        @empty
                            <div class="text-muted small py-3 fst-italic">No career history added yet. Build your timeline to improve your Tracer Study profile!</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- 3. CHANGE PASSWORD FORM --}}
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

{{-- ADD EXPERIENCE MODAL --}}
<div class="modal fade" id="addJobModal" tabindex="-1" aria-labelledby="addJobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="addJobModalLabel"><i class="fas fa-briefcase me-2"></i> Add Career Experience</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('alumni.profile.employment.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small">Job Title / Position</label>
                            <input type="text" name="job_title" class="form-control" placeholder="e.g. Software Engineer" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Company / Organization Name</label>
                            <input type="text" name="company_name" class="form-control" placeholder="e.g. Tech Corp Inc." required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Employment Type</label>
                            <select name="employment_type" class="form-select" required>
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-6" id="endDateContainer">
                            <label class="form-label fw-bold small">End Date</label>
                            <input type="date" name="end_date" class="form-control" id="endDateInput">
                        </div>
                        <div class="col-12 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_current" value="1" id="isCurrentJob" onchange="toggleEndDate()">
                                <label class="form-check-label fw-bold small text-primary" for="isCurrentJob">
                                    I am currently working in this role
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Save & Sync to MIS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Clean, isolated script just for the modal
    function toggleEndDate() {
        const isCurrent = document.getElementById('isCurrentJob').checked;
        const endDateContainer = document.getElementById('endDateContainer');
        const endDateInput = document.getElementById('endDateInput');
        
        if (isCurrent) {
            endDateContainer.style.opacity = '0.5';
            endDateInput.disabled = true;
            endDateInput.value = '';
        } else {
            endDateContainer.style.opacity = '1';
            endDateInput.disabled = false;
        }
    }
</script>
@endsection