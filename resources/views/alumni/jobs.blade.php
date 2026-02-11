@extends('layouts.alumni')

@section('content')
<div class="container py-5">
    
    {{-- 🟢 1. ADDED ALERT MESSAGES (This makes the success box appear) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- 🔴 END ALERT MESSAGES --}}

    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-primary"><i class="fas fa-briefcase me-2"></i> Career Opportunities</h2>
            <p class="text-muted">Explore job openings exclusively for KSU Alumni.</p>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill">
                {{ $jobs->total() }} Active Jobs
            </span>
        </div>
    </div>

    {{-- Job List --}}
    <div class="row">
        @foreach($jobs as $job)
    @php
        // Check if user already applied and get the status
        $myApplication = \App\Models\JobApplication::where('user_id', Auth::id())
                            ->where('job_post_id', $job->id)
                            ->first();
    @endphp

    <div class="card border-0 shadow-sm mb-3 hover-shadow transition">
        <div class="card-body p-4">
            <div class="row align-items-center">
                
                {{-- Job Icon --}}
                <div class="col-md-1 text-center d-none d-md-block">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                        <i class="fas fa-building text-secondary fa-lg"></i>
                    </div>
                </div>

                {{-- Job Details --}}
                <div class="col-md-8">
                    <h5 class="fw-bold text-dark mb-1">{{ $job->title }}</h5>
                    <p class="text-muted mb-2 small">
                        <i class="fas fa-building me-1"></i> {{ $job->company ?? 'KSU Partner' }} &nbsp;|&nbsp; 
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $job->location ?? 'Remote' }}
                    </p>
                    <div>
                        <span class="badge bg-info text-dark me-1">{{ $job->type ?? 'Full Time' }}</span>
                        <span class="badge bg-light text-muted border">Posted {{ $job->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="col-md-3 text-end mt-3 mt-md-0">
                    
                    @if($myApplication)
                        {{-- 🟢 CASE 1: ALREADY APPLIED (Show Status) --}}
                        <div class="d-grid">
                            @if($myApplication->status == 'pending')
                                <button class="btn btn-warning text-dark fw-bold disabled" style="opacity: 1;">
                                    <i class="fas fa-clock me-2"></i> Pending Review
                                </button>
                            @elseif($myApplication->status == 'interview')
                                <button class="btn btn-info text-white fw-bold disabled" style="opacity: 1;">
                                    <i class="fas fa-user-tie me-2"></i> For Interview
                                </button>
                            @elseif($myApplication->status == 'hired')
                                <button class="btn btn-success text-white fw-bold disabled" style="opacity: 1;">
                                    <i class="fas fa-check-circle me-2"></i> Hired
                                </button>
                            @elseif($myApplication->status == 'rejected')
                                <button class="btn btn-secondary text-white fw-bold disabled" style="opacity: 1;">
                                    <i class="fas fa-times-circle me-2"></i> Declined
                                </button>
                            @endif
                            <small class="text-muted mt-2">Applied on {{ $myApplication->created_at->format('M d') }}</small>
                        </div>

                    @else
                        {{-- 🟢 CASE 2: NOT APPLIED YET (Show Apply Button) --}}
                        <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary px-4 fw-bold w-100">
                                Apply Now <i class="fas fa-paper-plane ms-1"></i>
                            </button>
                        </form>
                        <small class="d-block text-muted mt-2" style="font-size: 11px;">
                            Deadline: {{ $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('M d, Y') : 'Open' }}
                        </small>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endforeach
    </div>
</div>

<style>
    .hover-shadow:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; transition: all 0.3s ease; }
</style>
@endsection