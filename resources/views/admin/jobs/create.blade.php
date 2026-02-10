@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Post a New Job</h2>
        <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.jobs.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Job Title</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. Software Engineer">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Company Name</label>
                        <input type="text" name="company" class="form-control" required placeholder="e.g. Yapakuzi Networks">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Location</label>
                        <input type="text" name="location" class="form-control" required placeholder="e.g. Tabuk City / Remote">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Job Type</label>
                        <select name="type" class="form-select" required>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract">Contract</option>
                            <option value="Remote">Remote</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Salary Range (Optional)</label>
                        <input type="text" name="salary" class="form-control" placeholder="e.g. ₱25,000 - ₱35,000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Application Deadline</label>
                        <input type="date" name="deadline" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Job Description</label>
                        <textarea name="description" rows="5" class="form-control" required placeholder="Enter job details, requirements, and qualifications..."></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Application Link or Email</label>
                        <input type="text" name="link" class="form-control" placeholder="https://company.com/apply or hr@company.com">
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-5 fw-bold">Post Job</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection