@extends('layouts.alumni')

@section('content')
<div class="container">
    
    {{-- Alerts Section --}}
    <div class="row mb-3">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2 fa-lg"></i>
                        <div><strong>Success!</strong> {{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    {{-- Welcome Header --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-success">Welcome back, {{ Auth::user()->first_name ?? 'Alumni' }}! 👋</h2>
            <p class="text-muted">Here is your KSU Alumni overview for today.</p>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-4 mb-4 align-items-stretch">
        {{-- Card 1: Alumni ID --}}
        <div class="col-md-4">
            <div class="card bg-success text-white h-100 shadow-sm border-0 position-relative overflow-hidden">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="card-title fw-bold">Alumni ID</h5>
                            <p class="card-text opacity-75 small">Your official digital identification.</p>
                        </div>
                        <i class="fas fa-id-card fa-3x opacity-50"></i>
                    </div>
                    <div>
                     <a href="{{ route('alumni.id_card') }}" class="btn btn-light text-success fw-bold btn-sm w-100 stretched-link">View ID Card</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Career Ops Count --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 position-relative hover-shadow">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                            <i class="fas fa-briefcase fa-2x"></i>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ count($jobs) ?? 0 }} New</span>
                    </div>
                    <div>
                        <h5 class="fw-bold">Career Ops</h5>
                        <p class="text-muted small mb-0">Job openings exclusive to KSU grads.</p>
                    </div>
                    <a href="{{ route('alumni.jobs.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        {{-- Card 3: Tracer Status --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 position-relative hover-shadow">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                            <i class="fas fa-poll fa-2x"></i>
                        </div>
                        @if(($pendingSurveys ?? 0) > 0)
                            <span class="badge bg-danger rounded-pill">Pending</span>
                        @else
                            <span class="badge bg-success rounded-pill">Updated</span>
                        @endif
                    </div>
                    <div>
                        <h5 class="fw-bold">Tracer Study</h5>
                        <p class="text-muted small mb-0">Help us improve by updating your status.</p>
                    </div>
                    <a href="{{ isset($activeSurveyId) ? route('alumni.tracer.show', $activeSurveyId) : '#' }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Row --}}
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-calendar-alt me-2 text-success"></i> Upcoming Events
                    </h5>
                    <a href="{{ route('alumni.events') }}" class="btn btn-link text-success text-decoration-none small">View All</a>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        @forelse($events as $event)
                            <div class="col-md-6 mb-3">
                                <div class="p-3 rounded border-start border-success border-4 bg-light h-100 shadow-sm">
                                    <h6 class="fw-bold text-dark mb-1">{{ $event->title }}</h6>
                                    <p class="small text-muted mb-2">
                                       <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($event->date)->format('M d, Y | h:i A') }}
                                    </p>
                                    <p class="small text-secondary mb-0">
                                        <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ \Illuminate\Support\Str::limit($event->location, 40) }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i>
                                <p>No upcoming events at the moment. Stay tuned!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Profile Summary Card --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center py-5 d-flex flex-column justify-content-center align-items-center">
                    <div class="rounded-circle bg-light border d-inline-flex justify-content-center align-items-center mb-3 shadow-sm" style="width: 90px; height: 90px;">
                        @if(Auth::user()->image)
                            <img src="{{ asset(Auth::user()->image) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        @else
                            <i class="fas fa-user-graduate fa-3x text-secondary"></i>
                        @endif
                    </div>
                    <h5 class="fw-bold mb-1">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
                    <p class="text-muted small mb-4">
                        <span class="d-block">Batch {{ Auth::user()->batch ?? 'N/A' }}</span>
                        <span class="fw-bold text-success">{{ Auth::user()->department->name ?? 'Alumni Member' }}</span>
                    </p>
                    <a href="{{ route('alumni.profile') }}" class="btn btn-outline-success btn-sm w-75 rounded-pill fw-bold">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection