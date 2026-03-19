@extends('layouts.alumni')

@section('content')
<div class="container-fluid py-4">
    
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark"><i class="fas fa-folder-open me-2 text-primary"></i> Registrar Document Center</h2>
            <p class="text-muted">Manage and process alumni document requests.</p>
        </div>
        <div class="col-md-4 text-end">
            {{-- 🟢 Link to the new Reports Page --}}
            <a href="{{ route('registrar.reports') }}" class="btn btn-outline-primary fw-bold rounded-pill px-4 shadow-sm">
                <i class="fas fa-chart-pie me-2"></i> View Reports & Analytics
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats Widgets --}}
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card bg-danger text-white border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title text-uppercase fw-bold opacity-75">Pending Review</h6>
                    <h2 class="fw-bold mb-0">{{ $pendingCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title text-uppercase fw-bold opacity-75">Processing</h6>
                    <h2 class="fw-bold mb-0">{{ $processingCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title text-uppercase fw-bold opacity-75">Ready for Pickup</h6>
                    <h2 class="fw-bold mb-0">{{ $readyCount }}</h2>
                </div>
            </div>
        </div>
    </div>

   {{-- 🟢 THE NEW FILTER TABS (FIXED VISIBILITY) --}}
    <ul class="nav nav-pills mb-3 gap-2">
        <li class="nav-item">
            <a class="nav-link fw-bold rounded-pill {{ $status == 'All' ? 'active bg-primary shadow-sm text-white' : 'text-dark bg-white border shadow-sm' }}" href="{{ route('registrar.dashboard') }}">All Requests</a>
        </li>
        <li class="nav-item">
            <a class="nav-link fw-bold rounded-pill {{ $status == 'Pending' ? 'active bg-danger shadow-sm text-white' : 'text-dark bg-white border shadow-sm' }}" href="{{ route('registrar.dashboard', ['status' => 'Pending']) }}">Pending</a>
        </li>
        <li class="nav-item">
            <a class="nav-link fw-bold rounded-pill {{ $status == 'Processing' ? 'active bg-warning shadow-sm text-dark' : 'text-dark bg-white border shadow-sm' }}" href="{{ route('registrar.dashboard', ['status' => 'Processing']) }}">Processing</a>
        </li>
        <li class="nav-item">
            <a class="nav-link fw-bold rounded-pill {{ $status == 'Ready for Pickup' ? 'active bg-success shadow-sm text-white' : 'text-dark bg-white border shadow-sm' }}" href="{{ route('registrar.dashboard', ['status' => 'Ready for Pickup']) }}">Ready for Pickup</a>
        </li>
        <li class="nav-item">
            <a class="nav-link fw-bold rounded-pill {{ $status == 'Claimed' ? 'active bg-secondary shadow-sm text-white' : 'text-dark bg-white border shadow-sm' }}" href="{{ route('registrar.dashboard', ['status' => 'Claimed']) }}">Claimed / History</a>
        </li>
    </ul>

    {{-- Document Requests Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0">Tracking Code</th>
                            <th class="border-0">Alumni Details</th>
                            <th class="border-0">Document Requested</th>
                            <th class="border-0">Status</th>
                            <th class="text-end px-4 border-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td class="px-4 fw-bold text-primary">{{ $req->tracking_code }}<br><small class="text-muted fw-normal">{{ $req->created_at->format('M d, Y') }}</small></td>
                            <td>
                                <div class="fw-bold">{{ $req->user->first_name ?? 'Unknown' }} {{ $req->user->last_name ?? '' }}</div>
                                <div class="text-muted small">{{ $req->user->student_id ?? 'No ID' }} | {{ $req->user->course ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $req->document_type }}</div>
                                <div class="text-muted small">{{ $req->copies }} copy(ies) - {{ $req->purpose }}</div>
                            </td>
                            <td>
                                @if($req->status == 'Pending') <span class="badge bg-danger">Pending</span>
                                @elseif($req->status == 'Processing') <span class="badge bg-warning text-dark">Processing</span>
                                @elseif($req->status == 'Ready for Pickup') <span class="badge bg-success">Ready</span>
                                @else <span class="badge bg-secondary">{{ $req->status }}</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                <form action="{{ route('registrar.documents.status', $req->id) }}" method="POST" class="d-flex justify-content-end align-items-center gap-2">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm w-auto" required>
                                        <option value="Pending" {{ $req->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Processing" {{ $req->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="Ready for Pickup" {{ $req->status == 'Ready for Pickup' ? 'selected' : '' }}>Ready</option>
                                        <option value="Claimed" {{ $req->status == 'Claimed' ? 'selected' : '' }}>Claimed</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0">No {{ $status != 'All' ? strtolower($status) : '' }} document requests found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top p-3">
            {{-- 🟢 Pagination that remembers the current tab! --}}
            {{ $requests->appends(['status' => $status])->links() }}
        </div>
    </div>

</div>
@endsection