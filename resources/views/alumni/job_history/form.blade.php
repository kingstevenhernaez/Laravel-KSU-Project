@extends('layouts.app')

@push('title')
    {{ $mode === 'create' ? __('Add Job') : __('Edit Job') }}
@endpush

@section('content')
    <div class="p-30">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-20">
            <h4 class="fs-20 fw-600">{{ $mode === 'create' ? __('Add Job') : __('Edit Job') }}</h4>
            <a href="{{ route('job_history.index') }}" class="btn btn-outline-secondary">{{ __('Back') }}</a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ $mode === 'create' ? route('job_history.store') : route('job_history.update', $job->id) }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Company') }}</label>
                            <input name="company" value="{{ old('company', $job->company) }}" class="form-control @error('company') is-invalid @enderror" required>
                            @error('company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Position') }}</label>
                            <input name="position" value="{{ old('position', $job->position) }}" class="form-control @error('position') is-invalid @enderror" required>
                            @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Location') }}</label>
                            <input name="location" value="{{ old('location', $job->location) }}" class="form-control @error('location') is-invalid @enderror">
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">{{ __('Start Date') }}</label>
                            <input type="date" name="start_date" value="{{ old('start_date', $job->start_date) }}" class="form-control @error('start_date') is-invalid @enderror">
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">{{ __('End Date') }}</label>
                            <input type="date" name="end_date" value="{{ old('end_date', $job->end_date) }}" class="form-control @error('end_date') is-invalid @enderror">
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_current" value="1" id="isCurrent" {{ old('is_current', $job->is_current) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isCurrent">{{ __('This is my current job') }}</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $job->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
