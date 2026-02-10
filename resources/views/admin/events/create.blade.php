@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">Create New Event</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.events.store') }}" method="POST">
                @csrf
                
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Event Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Annual Grand Reunion 2026" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Event Date & Time</label>
                        <input type="datetime-local" name="date" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Location / Venue</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. KSU Gymnasium or Zoom Link" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Enter event details..." required></textarea>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-primary px-5 fw-bold">
                            <i class="fas fa-save me-2"></i> Save Event
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection