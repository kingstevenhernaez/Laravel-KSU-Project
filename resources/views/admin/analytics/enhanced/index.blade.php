@extends('layouts.app')
@push('title')
    {{ __('Enhanced Analytics') }}
@endpush

@section('content')
    <div class="p-30">
        <h4 class="fs-24 fw-500 lh-34 text-black pb-16">{{ __('Enhanced Analytics') }}</h4>

        <div class="row rg-20">
            <div class="col-lg-3">
                <div class="bg-white p-20 radius-8">
                    <div class="fw-600">{{ __('Total Alumni (Synced)') }}</div>
                    <div class="fs-32 fw-700 mt-2">{{ number_format($total ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="row rg-20 mt-3">
            <div class="col-lg-4">
                <div class="bg-white p-20 radius-8">
                    <div class="fw-600 mb-2">{{ __('Top Years') }}</div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>{{ __('Year') }}</th><th class="text-end">{{ __('Total') }}</th></tr></thead>
                            <tbody>
                            @forelse($byYear as $r)
                                <tr><td>{{ $r->graduation_year }}</td><td class="text-end">{{ number_format($r->total) }}</td></tr>
                            @empty
                                <tr><td colspan="2">{{ __('No data') }}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bg-white p-20 radius-8">
                    <div class="fw-600 mb-2">{{ __('Top Colleges') }}</div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>{{ __('College') }}</th><th class="text-end">{{ __('Total') }}</th></tr></thead>
                            <tbody>
                            @forelse($byCollege as $r)
                                <tr><td>{{ $r->college_name }}</td><td class="text-end">{{ number_format($r->total) }}</td></tr>
                            @empty
                                <tr><td colspan="2">{{ __('No data') }}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bg-white p-20 radius-8">
                    <div class="fw-600 mb-2">{{ __('Top Programs') }}</div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>{{ __('Program') }}</th><th class="text-end">{{ __('Total') }}</th></tr></thead>
                            <tbody>
                            @forelse($byProgram as $r)
                                <tr><td>{{ $r->program_name }}</td><td class="text-end">{{ number_format($r->total) }}</td></tr>
                            @empty
                                <tr><td colspan="2">{{ __('No data') }}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
