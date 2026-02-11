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
                        {{-- Fixed: Used 'job_type' to match your database --}}
                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $job->job_type ?? 'Full Time' }}</span>
                        <small class="text-muted">{{ $job->created_at->diffForHumans() }}</small>
                    </div>
                    
                    <h5 class="fw-bold">{{ $job->title }}</h5>
                    {{-- Fixed: Used 'company_name' to match your database --}}
                    <p class="text-muted small">{{ $job->company_name }} • {{ $job->location }}</p>
                    
                    <p class="card-text text-muted small">{{ Str::limit($job->description, 100) }}</p>
                    
                    <hr>

                    {{-- 🟢 START: INTEGRATED APPLICATION LOGIC --}}
                    @php
                        // Check if the currently logged-in user has already applied
                        $alreadyApplied = \App\Models\JobApplication::where('user_id', auth()->id())
                                            ->where('job_post_id', $job->id)
                                            ->exists();
                    @endphp

                    @if($alreadyApplied)
                        <button class="btn btn-secondary w-100" disabled>
                            <i class="fas fa-check-circle me-1"></i> Applied
                        </button>
                    @else
                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#applyModal{{ $job->id }}">
                            Apply Now
                        </button>

                        <div class="modal fade" id="applyModal{{ $job->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                                        @csrf
                                        
                                        <div class="modal-header">
                                            <h5 class="modal-title">Apply for {{ $job->title }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Cover Letter (Optional)</label>
                                                <textarea name="cover_letter" class="form-control" rows="4" placeholder="Why are you a good fit?"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Submit Application</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- 🔴 END: INTEGRATED APPLICATION LOGIC --}}
                    
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">No active job postings found.</h4>
            <p>Check back later for new opportunities!</p>
        </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $jobs->links() }}
    </div>
</div>
@endsection