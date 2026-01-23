@extends('layouts.app')
@push('title')
    {{ __('Tracer Survey Results') }}
@endpush

@section('content')
    <div class="p-30">
        <div class="d-flex justify-content-between align-items-center pb-16">
            <div>
                <h4 class="fs-24 fw-500 lh-34 text-black mb-0">{{ __('Tracer Survey Results') }}</h4>
                <div class="text-muted">{{ $survey->title }}</div>
            </div>
            <div class="d-flex cg-10">
                <a class="btn btn-outline-primary" href="{{ route('admin.tracer_surveys_reports.analytics') }}">{{ __('Back to Analytics') }}</a>
                <a class="btn btn-primary" href="{{ route('admin.tracer_surveys_reports.export', $survey->id) }}">{{ __('Export CSV') }}</a>
            </div>
        </div>

        <div class="row rg-20 mb-20">
            <div class="col-md-3">
                <div class="bg-white p-20 radius-8">
                    <div class="text-muted">{{ __('Total Responses') }}</div>
                    <div class="fs-28 fw-600">{{ $responseCount }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-20 radius-8">
                    <div class="text-muted">{{ __('Latest Submission') }}</div>
                    <div class="fw-600">{{ optional($latest?->submitted_at)->format('Y-m-d H:i') ?? __('N/A') }}</div>
                    <div class="text-muted">{{ $latest?->alumni?->user?->name }}</div>
                </div>
            </div>
        </div>

        @foreach($survey->questions as $q)
            <div class="bg-white p-20 radius-8 mb-20">
                <div class="fw-600 mb-2">{{ $q->question }}</div>

                @if($q->type === 'text')
                    <div class="text-muted mb-2">{{ __('Latest text responses (showing up to 200)') }}</div>
                    <div class="border rounded p-10" style="max-height: 260px; overflow:auto;">
                        @forelse(($textAnswers[$q->id] ?? collect()) as $t)
                            <div class="mb-2">• {{ $t->answer_text }}</div>
                        @empty
                            <div class="text-muted">{{ __('No text answers yet.') }}</div>
                        @endforelse
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                            <tr>
                                <th>{{ __('Option') }}</th>
                                <th style="width:120px">{{ __('Count') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($q->options as $opt)
                                <tr>
                                    <td>{{ $opt->option_text }}</td>
                                    <td>{{ $optionCountsMap[$q->id][$opt->id] ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center">{{ __('No options configured.') }}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endforeach

        <div class="bg-white p-20 radius-8">
            <h5 class="fs-18 fw-500 mb-3">{{ __('Recent Responses') }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                    <tr>
                        <th>{{ __('Submitted') }}</th>
                        <th>{{ __('Alumni') }}</th>
                        <th>{{ __('Department') }}</th>
                        <th>{{ __('Passing Year') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($responsesPage as $r)
                        <tr>
                            <td>{{ optional($r->submitted_at)->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="fw-600">{{ $r->alumni?->user?->name }}</div>
                                <div class="text-muted">{{ $r->alumni?->id_number }}</div>
                            </td>
                            <td>{{ $r->alumni?->department?->name }}</td>
                            <td>{{ $r->alumni?->passing_year?->name }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">{{ __('No responses yet.') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $responsesPage->links() }}
            </div>
        </div>
    </div>
@endsection
