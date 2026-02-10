@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">Edit Event: {{ $event->title }}</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Event Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $event->title }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Event Date & Time</label>
                        <input type="datetime-local" name="date" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($event->date)) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Location / Venue</label>
                        <input type="text" name="location" class="form-control" value="{{ $event->location }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="5" required>{{ $event->description }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ $event->status == 1 ? 'selected' : '' }}>Active (Visible)</option>
                            <option value="0" {{ $event->status == 0 ? 'selected' : '' }}>Draft (Hidden)</option>
                        </select>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-primary px-5 fw-bold">
                            <i class="fas fa-save me-2"></i> Update Event
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection