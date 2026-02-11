@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center my-4">
        <h2 class="fw-bold text-dark">Job Board Management</h2>
        <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Post New Job
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Job Table --}}
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
                                <small class="text-muted">{{ $job->location }}</small>
                            </td>
                            <td>{{ $job->company }}</td>
                            <td><span class="badge bg-secondary">{{ $job->type }}</span></td>
                            <td>{{ $job->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($job->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    {{-- 🟢 MOVED APPLICANTS BUTTON HERE --}}
                                    <a href="{{ route('admin.jobs.applicants', $job->id) }}" class="btn btn-sm btn-info text-white" title="View Applicants">
                                        <i class="fas fa-users"></i>
                                    </a>

                                    <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-sm btn-warning text-dark" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this job?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-briefcase fa-2x mb-3 opacity-50"></i>
                                <p>No job postings found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination --}}
        <div class="card-footer bg-white border-0 py-3">
            {{ $jobs->links() }}
        </div>
    </div>
</div>
@endsection