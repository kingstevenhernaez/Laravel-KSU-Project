@extends('layouts.app')

@push('title')
    {{ __('Tracer Surveys') }}
@endpush

@section('content')
    <div class="p-30">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h4 class="fs-20 fw-600 lh-24 text-1b1c17">{{ __('Tracer Surveys') }}</h4>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="bg-white bd-one bd-c-black-10 bd-ra-10 p-20">
            @if($surveys->count() === 0)
                <p class="mb-0 text-muted">{{ __('No surveys available for you at this time.') }}</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Survey') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($surveys as $s)
                                @php($submitted = in_array($s->id, $submittedIds ?? [], true))
                                <tr>
                                    <td>
                                        <div class="fw-600">{{ $s->title }}</div>
                                        @if($s->description)
                                            <div class="text-muted small">{{ $s->description }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submitted)
                                            <span class="badge bg-success">{{ __('Submitted') }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ __('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($submitted)
                                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('tracer_surveys.show', $s->id) }}">{{ __('View') }}</a>
                                        @else
                                            <a class="btn btn-sm btn-primary" href="{{ route('tracer_surveys.show', $s->id) }}">{{ __('Answer Now') }}</a>
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
@endsection
