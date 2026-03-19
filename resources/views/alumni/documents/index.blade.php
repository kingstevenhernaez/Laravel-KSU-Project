@extends('layouts.alumni')

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h3 class="fw-bold text-success"><i class="fas fa-file-alt me-2"></i> Document Requests</h3>
            <p class="text-muted">Request official documents from the KSU Registrar and track their status.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('alumni.documents.create') }}" class="btn btn-success fw-bold shadow-sm rounded-pill px-4">
                <i class="fas fa-plus me-2"></i> Request Document
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm border-start border-4 border-success">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Tracking Code</th>
                            <th>Document</th>
                            <th>Copies</th>
                            <th>Date Requested</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td class="px-4 fw-bold text-success">{{ $req->tracking_code }}</td>
                            <td class="fw-bold">{{ $req->document_type }}</td>
                            <td>{{ $req->copies }}</td>
                            <td class="text-muted">{{ $req->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($req->status == 'Pending') <span class="badge bg-danger">Pending Review</span>
                                @elseif($req->status == 'Processing') <span class="badge bg-warning text-dark">Processing</span>
                                @elseif($req->status == 'Ready for Pickup') <span class="badge bg-success">Ready for Pickup</span>
                                @else <span class="badge bg-secondary">{{ $req->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0">You haven't requested any documents yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection