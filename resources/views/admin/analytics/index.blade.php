@extends('layouts.app')

@section('content')
<div class="p-30">
    <div class="d-flex justify-content-between align-items-center mb-20">
        <h4 class="mb-0">{{ __('Analytics') }}</h4>
        <a href="{{ route('admin.analytics.alumni_report.index') }}" class="zBtn zBtn-primary">{{ __('Alumni Report Builder') }}</a>
    </div>

    <div class="row rg-20">
        <div class="col-md-3">
            <div class="bg-white p-20 border radius-10">
                <div class="mb-5 fw-600">{{ __('Users') }}</div>
                <div class="fs-24 fw-700">{{ $totalUsers }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white p-20 border radius-10">
                <div class="mb-5 fw-600">{{ __('Synced Alumni Registry') }}</div>
                <div class="fs-24 fw-700">{{ $totalRegistry }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white p-20 border radius-10">
                <div class="mb-5 fw-600">{{ __('Active Memberships') }}</div>
                <div class="fs-24 fw-700">{{ $activeMemberships }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white p-20 border radius-10">
                <div class="mb-5 fw-600">{{ __('Transactions') }}</div>
                <div class="fs-24 fw-700">{{ $totalTransactions }}</div>
                <div class="text-muted mt-5">{{ __('Total Amount') }}: {{ number_format((float)$totalAmount, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="row rg-20 mt-20">
        <div class="col-lg-6">
            <div class="bg-white p-20 border radius-10">
                <h5 class="mb-15">{{ __('Top Graduation Years') }}</h5>
                <div class="table-responsive">
                    <table class="table zTable">
                        <thead>
                            <tr>
                                <th>{{ __('Year') }}</th>
                                <th class="text-end">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byYear as $r)
                                <tr>
                                    <td>{{ $r->graduation_year }}</td>
                                    <td class="text-end">{{ $r->total }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">{{ __('No data yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="bg-white p-20 border radius-10">
                <h5 class="mb-15">{{ __('Top Programs') }}</h5>
                <div class="table-responsive">
                    <table class="table zTable">
                        <thead>
                            <tr>
                                <th>{{ __('Program') }}</th>
                                <th class="text-end">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byProgram as $r)
                                <tr>
                                    <td>{{ $r->program_name }}</td>
                                    <td class="text-end">{{ $r->total }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">{{ __('No data yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
