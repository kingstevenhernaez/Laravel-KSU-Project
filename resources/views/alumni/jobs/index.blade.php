@extends('layouts.alumni') 

@section('content')
<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark">Career Opportunities</h2>
            <p class="text-muted">Explore job openings curated for KSU Alumni.</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($jobs as $job)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $job->type }}</span>
                        <small class="text-muted">{{ $job->created_at->diffForHumans() }}</small>
                    </div>
                    <h5 class="fw-bold">{{ $job->title }}</h5>
                    <p class="text-muted small">{{ $job->company }} • {{ $job->location }}</p>
                    <p class="card-text text-muted small">{{ Str::limit($job->description, 100) }}</p>
                    <hr>
                    @if($job->link)
                        <a href="{{ $job->link }}" target="_blank" class="btn btn-primary w-100">Apply Now</a>
                    @else
                        <a href="mailto:{{ $job->contact_email }}" class="btn btn-outline-primary w-100">Email HR</a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">No active job postings found.</h4>
        </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $jobs->links() }}
    </div>
</div>
@endsection