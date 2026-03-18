@extends('layouts.alumni')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-success"><i class="fas fa-calendar-alt me-2"></i> Upcoming Events</h2>
            <p class="text-muted">Stay connected with KSU! Join us for these exciting upcoming events.</p>
        </div>
    </div>

    <div class="row">
        @forelse($events as $event)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 border-start border-4 border-success">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                        <p class="text-success mb-2">
                            <i class="far fa-clock me-1"></i> 
                            {{ \Carbon\Carbon::parse($event->date)->format('F j, Y - g:i A') }}
                        </p>
                        <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($event->description, 150) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <button class="btn btn-outline-success btn-sm w-100">Event Details</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No upcoming events right now.</h4>
                <p>Check back later for exciting news and gatherings!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection