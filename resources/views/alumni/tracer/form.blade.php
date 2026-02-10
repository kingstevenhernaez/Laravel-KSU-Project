@extends('layouts.alumni')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="text-center mb-4">
                <h3 class="fw-bold text-success"><i class="fas fa-poll me-2"></i> Graduate Tracer Study</h3>
                <p class="text-muted">Please answer truthfully. Your data helps KSU improve.</p>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    
                    <form action="{{ route('alumni.tracer.store', $survey->id ?? 1) }}" method="POST">
                        @csrf

                        <h5 class="fw-bold text-success mb-3">I. Employment Status</h5>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Are you currently employed?</label>
                            <div class="d-flex gap-3">
                                <div class="form-check card p-3 border w-50">
                                    <input class="form-check-input" type="radio" name="employment_status" id="employed" value="Employed" onchange="toggleJobSection(true)" required>
                                    <label class="form-check-label stretched-link fw-bold" for="employed">
                                        Yes, I am Employed
                                    </label>
                                </div>
                                <div class="form-check card p-3 border w-50">
                                    <input class="form-check-input" type="radio" name="employment_status" id="unemployed" value="Unemployed" onchange="toggleJobSection(false)">
                                    <label class="form-check-label stretched-link fw-bold" for="unemployed">
                                        No, I am Unemployed
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="jobDetailsSection" style="display:none;">
                            <hr class="my-4">
                            <h5 class="fw-bold text-success mb-3">II. Job Details</h5>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Current Job Position / Title</label>
                                    <input type="text" name="job_title" class="form-control" placeholder="e.g. Software Engineer">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Company / Employer Name</label>
                                    <input type="text" name="company_name" class="form-control" placeholder="e.g. Google Philippines">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Monthly Income Range</label>
                                    <select name="salary_range" class="form-select">
                                        <option value="">Select Range</option>
                                        <option value="Below 10k">Below ₱10,000</option>
                                        <option value="10k-20k">₱10,000 - ₱20,000</option>
                                        <option value="20k-50k">₱20,000 - ₱50,000</option>
                                        <option value="50k+">Above ₱50,000</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Is this related to your course?</label>
                                    <select name="is_related" class="form-select">
                                        <option value="Yes">Yes, Related</option>
                                        <option value="No">No, Not Related</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-success btn-lg fw-bold shadow">
                                Submit Response <i class="fas fa-paper-plane ms-2"></i>
                            </button>
                            <a href="{{ route('alumni.dashboard') }}" class="btn btn-light text-muted">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function toggleJobSection(isEmployed) {
        var section = document.getElementById('jobDetailsSection');
        if (isEmployed) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    }
</script>
@endsection