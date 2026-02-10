@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Job Board Management</h2>
        <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Post New Job
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Job Title</th>
                            <th>Company</th>
                            <th>Type</th>
                            <th>Posted On</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $job->title }}</div>
                                <div class="small text-muted"><i class="fas fa-map-marker-alt me-1"></i> {{ $job->location }}</div>
                            </td>
                            <td>{{ $job->company }}</td>
                            <td>
                                <span class="badge bg-{{ $job->type_color ?? 'secondary' }}-soft text-dark border">
                                    {{ $job->type }}
                                </span>
                            </td>
                            <td>{{ $job->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($job->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Closed</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-sm btn-light border me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger text-white">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                No jobs posted yet. Click "Post New Job" to start.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $jobs->links() }}
        </div>
    </div>
</div>
@endsection