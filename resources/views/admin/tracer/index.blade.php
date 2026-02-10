@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold text-primary">Create New Tracer Study</div>
            <div class="card-body">
                <form action="{{ route('admin.tracer.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Study Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Batch 2025 Tracker" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create Survey</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold text-primary">Active Tracer Studies</div>
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Responses</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($surveys as $survey)
                        <tr>
                            <td class="fw-bold">{{ $survey->title }}</td>
                            <td>
                                @if($survey->status == 1) <span class="badge bg-success">Active</span>
                                @else <span class="badge bg-secondary">Closed</span> @endif
                            </td>
                            <td>{{ $survey->answers_count }} Alumni</td>
                            <td>
                                <a href="{{ route('admin.tracer.show', $survey->id) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-chart-pie"></i> Results
                                </a>
                                <form action="{{ route('admin.tracer.destroy', $survey->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">No surveys yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection