@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- 🟢 Dynamic Title based on which route you are on --}}
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-graduate me-2"></i> {{ $pageTitle ?? 'Alumni Master List' }}
        </h1>
        
        {{-- 🟢 Dynamic Button: Switch between Masterlist and Unclaimed --}}
        @if(Request::is('admin/alumni'))
            <a href="{{ route('admin.claims.index') }}" class="btn btn-warning shadow-sm fw-bold">
                <i class="fas fa-user-clock me-1"></i> View Unclaimed Records
            </a>
        @else
            <a href="{{ route('admin.alumni.index') }}" class="btn btn-success shadow-sm fw-bold">
                <i class="fas fa-list me-1"></i> Back to Master List
            </a>
        @endif
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="m-0 font-weight-bold text-primary">Registered Alumni</h6>
            
            {{-- 🟢 SEARCH BAR: Works perfectly for both pages --}}
            <form action="{{ url()->current() }}" method="GET" class="d-inline-block form-inline mt-2 mt-md-0">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search Name, ID, Email..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="bg-gray-100">
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Batch</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alumni as $alum)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $alum->student_id ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bold">{{ $alum->first_name }} {{ $alum->last_name }}</span>
                                    <small class="text-muted">{{ $alum->email }}</small>
                                </div>
                            </td>
                            <td>{{ $alum->course ?? 'Not Set' }}</td>
                            <td><span class="badge badge-info px-2 py-1">{{ $alum->batch ?? ($alum->year_graduated ?? 'Unknown') }}</span></td>
                            <td>
                                @if($alum->status == 1)
                                    <span class="badge badge-success">Verified</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.alumni.show', $alum->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach

                        @if($alumni->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-25"></i>
                                <h5 class="fw-bold text-secondary">No records found.</h5>
                                @if(request('search'))
                                    <p>No results matched your search for "<strong>{{ request('search') }}</strong>".</p>
                                    <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary mt-2">Clear Search</a>
                                @endif
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div> 

        @if($alumni->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $alumni->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div> 
</div> 
@endsection