@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Edit Job Posting</h2>
        <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.jobs.update', $job->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Job Title</label>
                        <input type="text" name="title" value="{{ $job->title }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Company Name</label>
                        <input type="text" name="company" value="{{ $job->company }}" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Location</label>
                        <input type="text" name="location" value="{{ $job->location }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Job Type</label>
                        <select name="type" class="form-select" required>
                            @foreach(['Full-time', 'Part-time', 'Contract', 'Remote'] as $type)
                                <option value="{{ $type }}" {{ $job->type == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Salary Range</label>
                        <input type="text" name="salary" value="{{ $job->salary }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Application Deadline</label>
                        <input type="date" name="deadline" value="{{ $job->deadline ? $job->deadline->format('Y-m-d') : '' }}" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Job Description</label>
                        <textarea name="description" rows="5" class="form-control" required>{{ $job->description }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Application Link</label>
                        <input type="text" name="link" value="{{ $job->link }}" class="form-control">
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="activeCheck" {{ $job->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="activeCheck">Active (Visible to Alumni)</label>
                        </div>
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-5 fw-bold">Update Job</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection