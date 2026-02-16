@extends('layouts.admin') @section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="fas fa-user-clock text-warning me-2"></i> Pending Alumni Accounts
        </h2>
        <a href="{{ route('admin.mis_sync.index') }}" class="btn btn-outline-success fw-bold">
            <i class="fas fa-upload me-2"></i> Upload More MIS Data
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-muted">Unclaimed MIS Records</h5>
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">
                {{ $pendingRecords->total() }} Pending
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Student ID</th>
                            <th>Full Name</th>
                            <th>Course</th>
                            <th>Graduation Year</th>
                            <th>Birthdate</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingRecords as $record)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">{{ $record->student_id }}</td>
                            <td class="fw-bold text-dark">{{ $record->last_name }}, {{ $record->first_name }}</td>
                            <td>{{ $record->course ?? 'N/A' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $record->year_graduated ?? 'N/A' }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($record->birthdate)->format('M d, Y') }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary px-2 py-1"><i class="fas fa-hourglass-half me-1"></i> Unclaimed</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted mb-2"><i class="fas fa-check-circle fa-3x text-success opacity-50"></i></div>
                                <h5 class="fw-bold text-dark">All Caught Up!</h5>
                                <p class="mb-0">There are no pending alumni. Everyone has claimed their account!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pendingRecords->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $pendingRecords->links() }}
        </div>
        @endif
    </div>
</div>
@endsection