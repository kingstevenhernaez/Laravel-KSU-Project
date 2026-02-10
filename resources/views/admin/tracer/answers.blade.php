@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">{{ $survey->title }}</h2>
            <p class="text-muted">Viewing all submitted responses from alumni.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tracer.export', $survey->id) }}" class="btn btn-success shadow-sm">
                <i class="fas fa-file-excel me-1"></i> Export to CSV
            </a>
            <a href="{{ route('admin.tracer.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Surveys
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary me-3">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ $stats['total'] }}</h4>
                        <small class="text-muted">Total Responses</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success me-3">
                        <i class="fas fa-user-check fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ $stats['employed'] }}</h4>
                        <small class="text-muted">Employed Alumni</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger me-3">
                        <i class="fas fa-user-minus fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ $stats['unemployed'] }}</h4>
                        <small class="text-muted">Unemployed</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 fw-bold">
                    <i class="fas fa-chart-pie text-primary me-2"></i> Employment Status Overview
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                    <canvas id="employmentChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body p-5 d-flex flex-column justify-content-center text-center">
                    <h3 class="fw-bold display-4">{{ $stats['total'] }}</h3>
                    <p class="lead opacity-75">Total Respondents</p>
                    <hr class="border-white opacity-25">
                    <div class="row mt-4">
                        <div class="col-6 border-end border-white border-opacity-25">
                            <h2 class="fw-bold">{{ $stats['employed'] }}</h2>
                            <small class="opacity-75 text-uppercase ls-1">Employed</small>
                        </div>
                        <div class="col-6">
                            <h2 class="fw-bold">{{ $stats['unemployed'] }}</h2>
                            <small class="opacity-75 text-uppercase ls-1">Unemployed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0 text-dark">Detailed Response List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Alumni Name</th>
                            <th>Status</th>
                            <th>Current Job / Company</th>
                            <th>Relevance</th>
                            <th>Date Submitted</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
    @forelse($survey->answers as $answer)
        {{-- SAFETY CHECK: Only show row if User exists --}}
        @if($answer->user) 
        <tr>
            <td class="ps-4">
                <div class="fw-bold text-dark">{{ $answer->user->first_name }} {{ $answer->user->last_name }}</div>
                <small class="text-muted">{{ $answer->user->email }}</small>
            </td>
            <td>
                @if($answer->employment_status == 'Employed')
                    <span class="badge bg-success-soft text-success px-3 py-2">Employed</span>
                @else
                    <span class="badge bg-danger-soft text-danger px-3 py-2">Unemployed</span>
                @endif
            </td>
            <td>
                @if($answer->employment_status == 'Employed')
                    <div class="small fw-bold">{{ $answer->job_title }}</div>
                    <div class="text-muted x-small">{{ $answer->company_name }}</div>
                @else
                    <span class="text-muted italic">--</span>
                @endif
            </td>
            <td>
                @if($answer->is_related == 'Yes')
                    <span class="text-success small"><i class="fas fa-check-circle"></i> Related</span>
                @else
                    <span class="text-muted small">Not Related</span>
                @endif
            </td>
            <td>{{ $answer->created_at->format('M d, Y') }}</td>
            <td class="text-end pe-4">
                <button class="btn btn-sm btn-light border" title="View Details">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
        </tr>
        @endif
    @empty
        <tr>
            <td colspan="6" class="text-center py-5 text-muted">
                No responses have been submitted for this survey yet.
            </td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    .x-small { font-size: 0.75rem; }
    .ls-1 { letter-spacing: 1px; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('employmentChart');

        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Employed', 'Unemployed'],
                    datasets: [{
                        data: [{{ $stats['employed'] }}, {{ $stats['unemployed'] }}],
                        backgroundColor: [
                            '#198754', // Bootstrap Success Green
                            '#dc3545'  // Bootstrap Danger Red
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    cutout: '70%', // Makes it a thinner donut
                }
            });
        }
    });
</script>
@endsection