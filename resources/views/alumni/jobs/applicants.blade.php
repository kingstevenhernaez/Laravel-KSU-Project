@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Applicants for: <span class="text-primary">{{ $job->title }}</span></h3>
        <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Back to Jobs</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($applicants->count() > 0)
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Applicant Name</th>
                            <th>Email</th>
                            <th>Batch / Dept</th>
                            <th>Cover Letter</th>
                            <th>Applied On</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applicants as $app)
                        <tr>
                            <td class="fw-bold">
                                {{ $app->user->first_name }} {{ $app->user->last_name }}
                            </td>
                            <td>{{ $app->user->email }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $app->user->batch }}</span>
                                <small class="d-block text-muted">{{ $app->user->department_id }}</small>
                            </td>
                            <td>
                                @if($app->cover_letter)
                                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#cover{{ $app->id }}">
                                        Read
                                    </button>
                                    <div class="collapse mt-2 p-2 bg-light border rounded" id="cover{{ $app->id }}" style="min-width: 200px;">
                                        <small><em>"{{ $app->cover_letter }}"</em></small>
                                    </div>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td>{{ $app->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($app->status) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-5">
                    <h4 class="text-muted">No applicants yet.</h4>
                    <p>Wait for alumni to apply!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection