@extends('frontend.layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <h2 class="m-0">Alumni Directory</h2>
        <small class="text-muted">Public search (Name, Course, Year Graduated only)</small>
    </div>

    <form method="GET" action="{{ route('public.alumni.directory') }}" class="row g-3 mb-4">
        <div class="col-md-5">
            <label class="form-label">Search Name</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="e.g., Dela Cruz">
        </div>
        <div class="col-md-4">
            <label class="form-label">Course</label>
            <input type="text" name="course" value="{{ $course }}" class="form-control" placeholder="e.g., BSIT">
        </div>
        <div class="col-md-3">
            <label class="form-label">Year Graduated</label>
            <input type="text" name="year" value="{{ $year }}" class="form-control" placeholder="e.g., 2026">
        </div>

        <div class="col-12 d-flex gap-2">
            <button class="btn btn-success" type="submit">Search</button>
            <a class="btn btn-outline-secondary" href="{{ route('public.alumni.directory') }}">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th style="width: 45%;">Name</th>
                <th style="width: 35%;">Course</th>
                <th style="width: 20%;">Year Graduated</th>
            </tr>
            </thead>
            <tbody>
            @forelse($rows as $r)
                <tr>
                    <td>{{ $r->full_name }}</td>
                    <td>{{ $r->course }}</td>
                    <td>{{ $r->year_graduated }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">No results found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $rows->links() }}
    </div>
</div>
@endsection
