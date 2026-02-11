@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4">Applicants for: <span class="text-primary">{{ $job->title }}</span></h3>
    
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header">
            <i class="fas fa-users me-1"></i> Total Applicants: {{ $applicants->count() }}
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Applicant Name</th>
                        <th>Email</th>
                        <th>Date Applied</th>
                        <th>Current Status</th>
                        <th>Action (Update Status)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applicants as $app)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    {{ substr($app->user->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $app->user->first_name }} {{ $app->user->last_name }}</div>
                                    <small class="text-muted">{{ $app->user->mobile }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $app->user->email }}</td>
                        <td>{{ $app->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($app->status == 'pending') <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($app->status == 'interview') <span class="badge bg-info">For Interview</span>
                            @elseif($app->status == 'hired') <span class="badge bg-success">Hired</span>
                            @elseif($app->status == 'rejected') <span class="badge bg-danger">Declined</span>
                            @endif
                        </td>
                        <td>
                            {{-- STATUS UPDATE FORM --}}
                            <form action="{{ route('admin.jobs.application.status', $app->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <select name="status" class="form-select form-select-sm" style="width: 140px;">
                                    <option value="pending" {{ $app->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="interview" {{ $app->status == 'interview' ? 'selected' : '' }}>For Interview</option>
                                    <option value="hired" {{ $app->status == 'hired' ? 'selected' : '' }}>Hired</option>
                                    <option value="rejected" {{ $app->status == 'rejected' ? 'selected' : '' }}>Decline</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($applicants->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No applicants yet.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection