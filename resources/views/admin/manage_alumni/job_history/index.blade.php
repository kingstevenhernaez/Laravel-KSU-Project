@extends('layouts.app')

@push('title')
    {{ __('Alumni Job History') }}
@endpush

@section('content')
    <div class="p-30">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-20">
            <div>
                <h4 class="fs-20 fw-600 mb-2">{{ __('Job History') }}</h4>
                <div class="text-muted">{{ $alumnus->user?->name }} • {{ $alumnus->department?->name }} • {{ $alumnus->passing_year?->name }}</div>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">{{ __('Back') }}</a>
        </div>

        <div class="card">
            <div class="card-body">
                @if($jobs->isEmpty())
                    <div class="text-muted">{{ __('No job history found.') }}</div>
                @else
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Company') }}</th>
                                <th>{{ __('Position') }}</th>
                                <th>{{ __('Location') }}</th>
                                <th>{{ __('Period') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($jobs as $job)
                                <tr>
                                    <td>{{ $job->company }}</td>
                                    <td>{{ $job->position }}</td>
                                    <td>{{ $job->location }}</td>
                                    <td>
                                        {{ $job->start_date ? \Illuminate\Support\Carbon::parse($job->start_date)->format('M Y') : __('N/A') }}
                                        -
                                        {{ $job->is_current ? __('Present') : ($job->end_date ? \Illuminate\Support\Carbon::parse($job->end_date)->format('M Y') : __('N/A')) }}
                                    </td>
                                    <td>
                                        @if($job->is_current)
                                            <span class="badge bg-success">{{ __('Current') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('Past') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
