@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h3 class="mb-1">Alumni Report Builder</h3>
            <p class="text-muted mb-0">Filter by year/college/program, choose columns, then print.</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.analytics.alumni_report.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Graduation Year</label>
                    <input class="form-control" name="graduation_year" value="{{ $filters['graduation_year'] }}" placeholder="e.g. 2024">
                </div>
                <div class="col-md-5">
                    <label class="form-label">College</label>
                    <input class="form-control" name="college_name" value="{{ $filters['college_name'] }}" placeholder="e.g. College of ...">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Program</label>
                    <input class="form-control" name="program_name" value="{{ $filters['program_name'] }}" placeholder="e.g. BSIT">
                </div>

                <div class="col-12">
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <div class="fw-semibold">Columns to print:</div>
                        @foreach($columns as $key => $label)
                            <label class="d-flex align-items-center gap-2">
                                <input type="checkbox" name="columns[]" value="{{ $key }}" checked>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="col-12 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">Preview</button>
                    <button type="submit" class="btn btn-outline-secondary" formaction="{{ route('admin.analytics.alumni_report.print') }}" formmethod="POST">
                        @csrf
                        Print
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="fw-semibold">Preview (first 200 rows)</div>
            <div class="text-muted small">Filters applied to full print output.</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                    <tr>
                        <th>Student No</th>
                        <th>Name</th>
                        <th>Program</th>
                        <th>College</th>
                        <th>Year</th>
                        <th>Email</th>
                        <th>Mobile</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($preview as $row)
                        <tr>
                            <td>{{ $row->student_number }}</td>
                            <td>{{ trim(($row->first_name ?? '').' '.($row->middle_name ?? '').' '.($row->last_name ?? '')) }}</td>
                            <td>{{ $row->program_name ?? $row->program_code }}</td>
                            <td>{{ $row->college_name }}</td>
                            <td>{{ $row->graduation_year }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->mobile }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-muted p-3">No records matched your filters.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
