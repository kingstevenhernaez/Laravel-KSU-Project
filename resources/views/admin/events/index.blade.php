@extends('layouts.admin')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary">Events Management</h5>
        <a href="{{ route('admin.events.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> Create Event
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Event Title</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $event->title }}</div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($event->date)->format('M d, Y h:i A') }}</td>
                        <td>{{ $event->location }}</td>
                        <td>
                            @if($event->status == 1)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                    <td>
    <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    
    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3 opacity-50"></i>
                            <p>No events found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection