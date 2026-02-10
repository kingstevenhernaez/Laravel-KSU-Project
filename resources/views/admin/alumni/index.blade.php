@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">🎓 Alumni Master List</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Registered Alumni</h6>
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
                            <td class="font-weight-bold text-dark">
                                {{ $alum->student_id ?? 'N/A' }}
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bold">{{ $alum->first_name }} {{ $alum->last_name }}</span>
                                    <small class="text-muted">{{ $alum->email }}</small>
                                </div>
                            </td>

                            <td>{{ $alum->course ?? 'Not Set' }}</td>

                            <td>
                                <span class="badge badge-info px-2 py-1">
                                    {{ $alum->year_graduated ?? 'Unknown' }}
                                </span>
                            </td>

                            <td>
                                @if($alum->email_verified_at)
                                    <span class="badge badge-success">Active</span>
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
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-3 d-block"></i>
                                No alumni records found.
                            </td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection