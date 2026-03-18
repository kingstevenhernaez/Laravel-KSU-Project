@extends('layouts.alumni')

@section('content')
<div class="container py-5">
    
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ url('/portal/events') }}" class="btn btn-light shadow-sm text-success fw-bold">
                <i class="fas fa-arrow-left me-2"></i> Back to Events
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                
                <div class="card-header bg-success text-white py-4 text-center">
                    <i class="fas fa-calendar-check fa-3x mb-3 text-white-50"></i>
                    <h2 class="fw-bold mb-0 text-white">{{ $event->title }}</h2>
                </div>

                <div class="card-body p-5">
                    
                    <div class="d-flex align-items-center bg-light p-3 rounded mb-4 border-start border-4 border-success">
                        <i class="far fa-clock fa-2x text-success me-3"></i>
                        <div>
                            <h6 class="text-muted mb-0 fw-bold text-uppercase">Date & Time</h6>
                            <h5 class="text-dark mb-0">
                                {{ \Carbon\Carbon::parse($event->date)->format('l, F j, Y') }} <br>
                                <span class="text-success">{{ \Carbon\Carbon::parse($event->date)->format('g:i A') }}</span>
                            </h5>
                        </div>
                    </div>

                    <h5 class="fw-bold border-bottom pb-2 mb-3">About This Event</h5>
                    <div class="text-dark" style="line-height: 1.8; font-size: 1.1rem; white-space: pre-line;">
                        {{ $event->description }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection