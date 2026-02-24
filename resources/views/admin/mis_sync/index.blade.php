@extends('layouts.admin') 

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold mb-4"><i class="fas fa-sync-alt text-success me-2"></i> MIS Data Synchronization</h2>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        {{-- Stats Cards --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary rounded-circle p-3 text-white me-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Total Staged Records</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success rounded-circle p-3 text-white me-3">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Claimed Accounts</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['claimed'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🟢 NEW: Live API Sync Section --}}
    <div class="card shadow-sm border-0 mb-4 border-success border-start border-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold text-success"><i class="fas fa-cloud-download-alt me-2"></i> Live API Sync</h5>
        </div>
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-6 border-end">
                    <p class="text-muted">Instantly pull and stage a specific student or alumni record directly from the KSU MIS system.</p>
                    <form action="{{ route('admin.mis_sync.api') }}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-light fw-bold" id="basic-addon1">ID Number</span>
                            <input type="text" class="form-control" name="id_number" placeholder="e.g., 18-11013" required aria-describedby="basic-addon1">
                        </div>
                        <button type="submit" class="btn btn-success fw-bold">
                            <i class="fas fa-sync-alt me-2"></i> Sync from API
                        </button>
                    </form>
                </div>
                <div class="col-md-6 ps-md-4 mt-4 mt-md-0">
                    <h6 class="fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>How it works</h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li>Enter a valid KSU ID Number.</li>
                        <li>The system will fetch their full name, course, and year.</li>
                        <li><b>Note:</b> Birthdates are currently set to a default value until the API update is completed.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Upload Section (Now the Fallback) --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold">Manual CSV Sync (Fallback)</h5>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6 border-end">
                    <p class="text-muted">Use this tool to bulk upload exported lists of graduates from the Registrar if the API is unavailable.</p>
                    <form action="{{ route('admin.mis_sync.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select CSV File</label>
                            <input class="form-control" type="file" name="csv_file" accept=".csv" required>
                        </div>
                        <button type="submit" class="btn btn-secondary fw-bold">
                            <i class="fas fa-upload me-2"></i> Upload & Sync CSV
                        </button>
                    </form>
                </div>
                <div class="col-md-6 ps-md-4 mt-4 mt-md-0">
                    <h6 class="fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Required CSV Format</h6>
                    <p class="small text-muted mb-2">The first row of your CSV <b>must</b> contain these exact column headers:</p>
                    <code class="bg-light p-2 d-block rounded mb-3 border">student_id,first_name,last_name,course,year_graduated,birthdate</code>
                    <ul class="small text-muted mb-0 ps-3">
                        <li><b>birthdate</b> should ideally be in YYYY-MM-DD format.</li>
                        <li>Existing student IDs will be updated, new ones will be added.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold">Current Staged Records</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-4">Student ID</th>
                            <th>Name</th>
                            <th>Course & Year</th>
                            <th>Birthdate</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $record->student_id }}</td>
                            <td>{{ $record->first_name }} {{ $record->last_name }}</td>
                            <td>
                                <span class="d-block">{{ $record->course }}</span>
                                <small class="text-muted">Class of {{ $record->year_graduated }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($record->birthdate)->format('M d, Y') }}</td>
                            <td>
                                @if($record->is_claimed)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Claimed</span>
                                @else
                                    <span class="badge bg-secondary">Unclaimed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No records staged yet. Use the API Sync or upload a CSV to begin.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($records->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $records->links() }}
        </div>
        @endif
    </div>
</div>
@endsection