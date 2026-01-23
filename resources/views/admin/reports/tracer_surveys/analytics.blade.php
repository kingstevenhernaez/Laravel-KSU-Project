@extends('layouts.app')
@push('title')
    {{ __('Tracer Survey Analytics') }}
@endpush

@section('content')
    <div class="p-30">
        <div class="d-flex justify-content-between align-items-center pb-16">
            <h4 class="fs-24 fw-500 lh-34 text-black mb-0">{{ __('Tracer Survey Analytics') }}</h4>
            <a class="btn btn-outline-primary" href="{{ route('admin.tracer_surveys.index') }}">{{ __('Back to Builder') }}</a>
        </div>

        <div class="bg-white p-20 radius-8 mb-20">
            <form method="GET" action="{{ route('admin.tracer_surveys_reports.analytics') }}" class="row rg-10">
                <div class="col-md-5">
                    <label class="form-label">{{ __('Department') }}</label>
                    <select name="department_id" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}" @selected(request('department_id') == $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">{{ __('Passing Year') }}</label>
                    <select name="passing_year_id" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        @foreach($years as $y)
                            <option value="{{ $y->id }}" @selected(request('passing_year_id') == $y->id)>{{ $y->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100" type="submit">{{ __('Apply') }}</button>
                </div>
            </form>
        </div>

        <div class="row rg-20">
            <div class="col-md-3">
                <div class="bg-white p-20 radius-8">
                    <div class="text-muted">{{ __('Total Responses (All Surveys)') }}</div>
                    <div class="fs-28 fw-600">{{ $totalResponses }}</div>
                </div>
            </div>
        </div>

        <div class="row rg-20 mt-1">
            <div class="col-lg-6">
                <div class="bg-white p-20 radius-8">
                    <h5 class="fs-18 fw-500 mb-3">{{ __('Responses by Survey') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                            <tr>
                                <th>{{ __('Survey') }}</th>
                                <th style="width:120px">{{ __('Responses') }}</th>
                                <th style="width:180px">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($surveys as $s)
                                <tr>
                                    <td>
                                        <div class="fw-600">{{ $s->title }}</div>
                                        <div class="text-muted">{{ $s->is_published ? __('Published') : __('Draft') }}</div>
                                    </td>
                                    <td>{{ $responsesPerSurvey[$s->id]->total ?? 0 }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.tracer_surveys_reports.results', $s->id) }}">{{ __('View Results') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">{{ __('No surveys found.') }}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bg-white p-20 radius-8">
                    <h5 class="fs-18 fw-500 mb-3">{{ __('Responses by Department') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead><tr><th>{{ __('Department') }}</th><th style="width:120px">{{ __('Count') }}</th></tr></thead>
                            <tbody>
                            @forelse($responsesByDepartment as $r)
                                <tr><td>{{ $r->department }}</td><td>{{ $r->total }}</td></tr>
                            @empty
                                <tr><td colspan="2" class="text-center">{{ __('No data') }}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h5 class="fs-18 fw-500 mt-4 mb-3">{{ __('Responses by Passing Year') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead><tr><th>{{ __('Year') }}</th><th style="width:120px">{{ __('Count') }}</th></tr></thead>
                            <tbody>
                            @forelse($responsesByYear as $r)
                                <tr><td>{{ $r->year }}</td><td>{{ $r->total }}</td></tr>
                            @empty
                                <tr><td colspan="2" class="text-center">{{ __('No data') }}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
