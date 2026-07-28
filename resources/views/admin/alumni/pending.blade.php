@extends('layouts.admin') 

@section('content')
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
        {{-- 🟢 SEARCH BAR & HEADER --}}
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-muted me-3">Unclaimed MIS Records</h5>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">
                    {{ $pendingRecords->total() }} Pending
                </span>
            </div>
            
            <form action="{{ url()->current() }}" method="GET" class="d-flex" style="width: 350px;">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-secondary border-opacity-25 shadow-none" 
                           placeholder="Search Name, ID, or Course..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
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
                            
                            {{-- 🟢 PRIVACY FIX: Mask the date if it exists, otherwise show N/A --}}
                            <td>
                                @if($record->birthdate)
                                    <span class="text-muted"><i class="fas fa-lock small me-1"></i> Hidden</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            
                            <td class="text-center">
                                <span class="badge bg-secondary px-2 py-1"><i class="fas fa-hourglass-half me-1"></i> Unclaimed</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                {{-- 🟢 DYNAMIC EMPTY STATE (Checks if it's a failed search vs an empty database) --}}
                                @if(request('search'))
                                    <i class="fas fa-search fa-3x text-muted mb-3 opacity-25"></i>
                                    <h5 class="fw-bold text-secondary">No records found.</h5>
                                    <p class="text-muted mb-2">No results matched your search for "<strong>{{ request('search') }}</strong>".</p>
                                    <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary mt-2">Clear Search</a>
                                @else
                                    <div class="text-muted mb-2"><i class="fas fa-check-circle fa-3x text-success opacity-50"></i></div>
                                    <h5 class="fw-bold text-dark">All Caught Up!</h5>
                                    <p class="mb-0">There are no pending alumni. Everyone has claimed their account!</p>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- 🟢 UPDATED PAGINATION (Appends search query) --}}
        @if($pendingRecords->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $pendingRecords->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
