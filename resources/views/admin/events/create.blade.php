@extends('layouts.admin')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

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
    <div class="mb-3">
        <label>Event Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
    <label>Event Date & Time</label>
    <input type="datetime-local" name="date" class="form-control" required>
</div>

    <div class="mb-3">
        <label>Location</label>
        <input type="text" name="location" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="4" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Save Event & Notify Alumni</button>
</form>
        </div>
    </div>
</div>
@endsection