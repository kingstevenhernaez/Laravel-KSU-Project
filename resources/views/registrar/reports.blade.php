@extends('layouts.alumni')

@section('content')
<div class="container-fluid py-4">
    
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark"><i class="fas fa-chart-pie me-2 text-primary"></i> Document Analytics & Reports</h2>
            <p class="text-muted">Filter and generate targeted reports for specific courses or batches.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('registrar.dashboard') }}" class="btn btn-light fw-bold shadow-sm rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
    </div>

    {{-- 🟢 THE TARGETED REPORT FILTER SYSTEM --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-filter text-primary me-2"></i> Targeted Report Generator</h5>
            
            {{-- Print Button (Passes current filters to the print route) --}}
            <a href="{{ route('registrar.reports.print', request()->all()) }}" target="_blank" class="btn btn-success btn-sm fw-bold px-3 rounded-pill shadow-sm">
                <i class="fas fa-print me-1"></i> Print Report
            </a>
        </div>
        <div class="card-body bg-light p-4">
            <form action="{{ route('registrar.reports') }}" method="GET" class="row g-3">
                
                <div class="col-md-3">
                    <label class="form-label fw-bold small">Course / Program</label>
                    <select name="course" class="form-select">
                        <option value="">All Courses</option>
                        @foreach($courses as $c)
                            <option value="{{ $c }}" {{ request('course') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold small">Batch (Year)</label>
                    <select name="batch" class="form-select">
                        <option value="">All Batches</option>
                        @foreach($batches as $b)
                            <option value="{{ $b }}" {{ request('batch') == $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold small">Document Type</label>
                    <select name="document_type" class="form-select">
                        <option value="">All Documents</option>
                        @foreach($docTypes as $doc)
                            <option value="{{ $doc }}" {{ request('document_type') == $doc ? 'selected' : '' }}>{{ $doc }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold small">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Ready for Pickup" {{ request('status') == 'Ready for Pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                        <option value="Claimed" {{ request('status') == 'Claimed' ? 'selected' : '' }}>Claimed</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fas fa-search me-1"></i> Filter</button>
                    @if(request()->anyFilled(['course', 'batch', 'document_type', 'status']))
                        <a href="{{ route('registrar.reports') }}" class="btn btn-link text-danger ms-2" title="Clear Filters"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </form>
        </div>
        
        {{-- Display Filtered Results --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Tracking Code</th>
                            <th>Alumni Name</th>
                            <th>Course & Batch</th>
                            <th>Document Requested</th>
                            <th>Date Requested</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($filteredRequests as $req)
                        <tr>
                            <td class="px-4 fw-bold text-primary">{{ $req->tracking_code }}</td>
                            <td class="fw-bold">{{ $req->user->first_name ?? 'Unknown' }} {{ $req->user->last_name ?? '' }}</td>
                            <td>{{ $req->user->course ?? 'N/A' }} <span class="badge bg-secondary ms-1">{{ $req->user->batch ?? 'N/A' }}</span></td>
                            <td>{{ $req->document_type }} ({{ $req->copies }})</td>
                            <td class="text-muted small">{{ $req->created_at->format('M d, Y') }}</td>
                            <td><span class="badge bg-dark">{{ $req->status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">No records match your filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $filteredRequests->links() }}
        </div>
    </div>

    {{-- Aggregate Summary Tables (Kept for quick dashboard overview) --}}
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3"><h6 class="mb-0 fw-bold"><i class="fas fa-file-alt text-success me-2"></i> Total by Document Type</h6></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tbody>
                            @foreach($byDocType as $doc)
                            <tr><td class="px-4">{{ $doc->document_type }}</td><td class="text-end px-4 fw-bold">{{ $doc->total }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3"><h6 class="mb-0 fw-bold"><i class="fas fa-graduation-cap text-warning me-2"></i> Total by Course</h6></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tbody>
                            @foreach($byCourse as $course)
                            <tr><td class="px-4">{{ $course->course ?? 'Unspecified' }}</td><td class="text-end px-4 fw-bold">{{ $course->total }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection