@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- LEFT COLUMN: Open Builder Card --}}
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 text-center p-4">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-clipboard-list fa-3x"></i>
                    </div>
                    
                    <h4 class="fw-bold text-dark mb-3">Create New Tracer Study</h4>
                    <p class="text-muted small mb-4 px-2">
                        Use the advanced survey builder to add custom questions, dropdowns, or instantly load the standard CHED format.
                    </p>
                    
                    <a href="{{ route('admin.tracer.create') }}" class="btn btn-primary btn-lg fw-bold w-100 rounded-pill shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i> Open Survey Builder
                    </a>

                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Active Surveys Table --}}
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark">Active Tracer Studies</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4">Title</th>
                                    <th>Status</th>
                                    <th>Responses</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($surveys as $survey)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">{{ $survey->title }}</td>
                                    <td>
                                        @if($survey->is_active) 
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">Active</span>
                                        @else 
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill">Closed</span> 
                                        @endif
                                    </td>
                                    <td class="text-muted fw-bold">
                                        <i class="fas fa-users me-1 text-primary"></i> {{ $survey->answers_count }} Alumni
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.tracer.show', $survey->id) }}" class="btn btn-sm btn-info text-white shadow-sm rounded-pill px-3 fw-bold me-1">
                                            <i class="fas fa-chart-pie me-1"></i> Results
                                        </a>
                                        <form action="{{ route('admin.tracer.destroy', $survey->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this survey? All questions and alumni answers will be permanently lost.');">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm rounded-pill px-3">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                                        <p class="mb-0">No surveys created yet. Click the button to build one!</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection