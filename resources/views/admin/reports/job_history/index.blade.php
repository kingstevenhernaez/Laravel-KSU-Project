@extends('layouts.app')
@push('title')
    {{ __('Job History Report') }}
@endpush

@section('content')
    <div class="p-30">
        <div class="d-flex justify-content-between align-items-center pb-16">
            <h4 class="fs-24 fw-500 lh-34 text-black mb-0">{{ __('Job History Report') }}</h4>
            <div class="d-flex cg-10">
                <a class="btn btn-outline-primary" href="{{ route('admin.job_history_reports.analytics', request()->query()) }}">{{ __('Analytics') }}</a>
                <a class="btn btn-primary" href="{{ route('admin.job_history_reports.export', request()->query()) }}">{{ __('Export CSV') }}</a>
            </div>
        </div>

        <div class="bg-white p-20 radius-8 mb-20">
            <form method="GET" action="{{ route('admin.job_history_reports.index') }}" class="row rg-10">
                <div class="col-md-3">
                    <label class="form-label">{{ __('Department') }}</label>
                    <select name="department_id" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}" @selected(request('department_id') == $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('Passing Year') }}</label>
                    <select name="passing_year_id" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        @foreach($years as $y)
                            <option value="{{ $y->id }}" @selected(request('passing_year_id') == $y->id)>{{ $y->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('Industry') }}</label>
                    <select name="industry" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        @foreach($industryOptions as $ind)
                            <option value="{{ $ind }}" @selected(request('industry') == $ind)>{{ $ind }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('Employment Type') }}</label>
                    <select name="employment_type" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        @foreach($employmentTypeOptions as $et)
                            <option value="{{ $et }}" @selected(request('employment_type') == $et)>{{ $et }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('Current') }}</label>
                    <select name="is_current" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        <option value="1" @selected(request('is_current') === '1')>{{ __('Current Only') }}</option>
                        <option value="0" @selected(request('is_current') === '0')>{{ __('Not Current') }}</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-primary w-100" type="submit">{{ __('Filter') }}</button>
                </div>
            </form>
        </div>

        <div class="bg-white p-20 radius-8">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                    <tr>
                        <th>{{ __('Alumni') }}</th>
                        <th>{{ __('Department') }}</th>
                        <th>{{ __('Year') }}</th>
                        <th>{{ __('Company') }}</th>
                        <th>{{ __('Job Title') }}</th>
                        <th>{{ __('Industry') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Location') }}</th>
                        <th>{{ __('Current') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($items as $r)
                        <tr>
                            <td>
                                <div class="fw-600">{{ $r->alumni?->user?->name }}</div>
                                <div class="text-muted">{{ $r->alumni?->id_number }}</div>
                            </td>
                            <td>{{ $r->alumni?->department?->name }}</td>
                            <td>{{ $r->alumni?->passing_year?->name }}</td>
                            <td>{{ $r->company_name }}</td>
                            <td>{{ $r->job_title }}</td>
                            <td>{{ $r->industry }}</td>
                            <td>{{ $r->employment_type }}</td>
                            <td>{{ $r->location }}</td>
                            <td>
                                @if($r->is_current)
                                    <span class="badge bg-success">{{ __('Yes') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('No') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">{{ __('No records found.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection
