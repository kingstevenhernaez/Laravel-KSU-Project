@extends('layouts.app')

@push('title')
    {{ __('Job History') }}
@endpush

@section('content')
    <div class="p-30">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-20">
            <h4 class="fs-20 fw-600">{{ __('Job History') }}</h4>
            <a href="{{ route('job_history.create') }}" class="btn btn-primary">{{ __('Add Job') }}</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                @if($jobs->isEmpty())
                    <div class="text-muted">{{ __('No job history yet.') }}</div>
                @else
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Company') }}</th>
                                <th>{{ __('Position') }}</th>
                                <th>{{ __('Period') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($jobs as $job)
                                <tr>
                                    <td>{{ $job->company }}</td>
                                    <td>{{ $job->position }}</td>
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
                                    <td class="text-end">
                                        @if(!$job->is_current)
                                            <form method="POST" action="{{ route('job_history.set_current', $job->id) }}" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success" type="submit">{{ __('Mark Current') }}</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('job_history.edit', $job->id) }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                                        <form method="POST" action="{{ route('job_history.delete', $job->id) }}" class="d-inline" onsubmit="return confirm('{{ __('Delete this job?') }}')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" type="submit">{{ __('Delete') }}</button>
                                        </form>
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
