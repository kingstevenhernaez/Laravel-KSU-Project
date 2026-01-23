@extends('frontend.layouts.app')

@push('title')
    {{ __('Alumni Directory') }}
@endpush

@section('content')
    <section class="breadcrumb-area" data-background="{{ getFileUrl(getOption('banner_background_breadcrumb')) }}">

        <div class="container">
            <div class="breadcrumb-wrap text-center">
                <h2 class="title">{{ __('Public Alumni Directory') }}</h2>
            </div>
        </div>
    </section>

    <section class="pt-60 pb-60">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form method="GET" action="{{ route('directory') }}" class="row g-2 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('Search Name') }}</label>
                                    <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="{{ __('Type a name...') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Program') }}</label>
                                    <select name="department_id" class="form-select">
                                        <option value="">{{ __('All Programs') }}</option>
                                        @foreach($departments as $d)
                                            <option value="{{ $d->id }}" @selected((string)$selectedDepartment === (string)$d->id)>
                                                {{ $d->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Graduation Year') }}</label>
                                    <select name="passing_year_id" class="form-select">
                                        <option value="">{{ __('All Years') }}</option>
                                        @foreach($passingYears as $y)
                                            <option value="{{ $y->id }}" @selected((string)$selectedPassingYear === (string)$y->id)>
                                                {{ $y->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary w-100" type="submit">{{ __('Search') }}</button>
                                </div>
                            </form>
                            <div class="text-muted small mt-2">
                                {{ __('Directory shows public fields only: Name, Program, Graduation Year.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Program') }}</th>
                                        <th>{{ __('Graduation Year') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($alumni as $a)
                                        <tr>
                                            <td>{{ $a->user?->name }}</td>
                                            <td>{{ $a->department?->name }}</td>
                                            <td>{{ $a->passing_year?->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                {{ __('No alumni found.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $alumni->links('frontend.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
