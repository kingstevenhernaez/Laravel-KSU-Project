@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <a href="{{ route('admin.tracer.index') }}" class="btn btn-outline-secondary mb-3">Back</a>
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white p-3 h-100">
                <h3>{{ $total }}</h3>
                <span>Total Respondents</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white p-3 h-100">
                <h3>{{ $employed }}</h3>
                <span>Employed</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark p-3 h-100">
                <h3>{{ $unemployed }}</h3>
                <span>Unemployed / Seeking</span>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold">Detailed Responses</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Alumni Name</th>
                            <th>Status</th>
                            <th>Company</th>
                            <th>Position</th>
                            <th>Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($survey->answers as $ans)
                        <tr>
                            <td>{{ $ans->user->first_name ?? 'Unknown' }} {{ $ans->user->last_name ?? '' }}</td>
                            <td>{{ $ans->employment_status }}</td>
                            <td>{{ $ans->company_name ?? 'N/A' }}</td>
                            <td>{{ $ans->position ?? 'N/A' }}</td>
                            <td>{{ $ans->salary_range ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection