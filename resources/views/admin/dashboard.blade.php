@extends('layouts.admin')

@section('content')

{{-- 🟢 BULLETPROOF OVERRIDE: Fetches the live data directly if the route fails --}}
@php
    $verifiedCount = $verifiedCount ?? \App\Models\User::where('status', 1)->count();
    $pendingCount  = $pendingCount ?? \App\Models\User::where('status', 0)->count();
    $totalUsers    = $totalUsers ?? \App\Models\User::count();
    $recentUsers   = $recentUsers ?? \App\Models\User::orderBy('created_at', 'desc')->take(5)->get();

    $employmentData = $employmentData ?? \App\Models\User::select('employment_status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->whereNotNull('employment_status')
        ->where('employment_status', '!=', '')
        ->groupBy('employment_status')
        ->pluck('total', 'employment_status')
        ->toArray();

    $batchData = $batchData ?? \App\Models\User::select('batch', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->whereNotNull('batch')
        ->where('batch', '!=', '')
        ->groupBy('batch')
        ->orderBy('total', 'desc')
        ->take(5)
        ->pluck('total', 'batch')
        ->toArray();
@endphp

<div class="container-fluid py-4">

    {{-- TOP ROW: Live Core Metrics --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-success border-4">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-0">Verified Alumni</p>
                        <h2 class="fw-bold text-dark mb-0">{{ $verifiedCount }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-warning border-4">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-hourglass-half fa-2x"></i>
                    </div>
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-0">Pending Claims</p>
                        <h2 class="fw-bold text-dark mb-0">{{ $pendingCount }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-primary border-4">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-0">Total Registered</p>
                        <h2 class="fw-bold text-dark mb-0">{{ $totalUsers }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MIDDLE ROW: Advanced Analytics Charts --}}
    <div class="row mb-4">
        {{-- Chart 1: Employment Status --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-pie me-2 text-primary"></i> Employment Distribution</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center p-4" style="position: relative; height: 300px;">
                    @if(empty($employmentData))
                        <p class="text-muted small text-center">No career data available yet.<br>Alumni need to update their profiles.</p>
                    @else
                        <canvas id="employmentChart"></canvas>
                    @endif
                </div>
            </div>
        </div>

        {{-- Chart 2: Top Batches --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-bar me-2 text-success"></i> Registered Alumni by Batch</h6>
                </div>
                <div class="card-body p-4" style="position: relative; height: 300px;">
                    @if(empty($batchData))
                        <p class="text-muted small text-center mt-5">No batch data available yet.</p>
                    @else
                        <canvas id="batchChart"></canvas>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- BOTTOM ROW: Recent Activity Log --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2 text-warning"></i> Recent Registrations</h6>
           <a href="{{ route('admin.alumni.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Alumni Profile</th>
                            <th>Email Address</th>
                            <th>Batch</th>
                            <th>Date Joined</th>
                            <th class="pe-4 text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex justify-content-center align-items-center me-3 fw-bold" style="width: 40px; height: 40px;">
                                        {{ substr($user->first_name, 0, 1) }}
                                    </div>
                                    <span class="fw-bold text-dark">{{ $user->first_name }} {{ $user->last_name }}</span>
                                </div>
                            </td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td class="text-muted">{{ $user->batch ?? 'N/A' }}</td>
                            <td class="text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="pe-4 text-end">
                                @if($user->status == 1)
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success border-opacity-25"><i class="fas fa-check-circle me-1"></i> Verified</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill border border-warning border-opacity-25"><i class="fas fa-clock me-1"></i> Pending</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- INCLUDE CHART.JS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- 1. Employment Doughnut Chart ---
        @if(!empty($employmentData))
            const empCtx = document.getElementById('employmentChart').getContext('2d');
            new Chart(empCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($employmentData)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($employmentData)) !!},
                        backgroundColor: ['#198754', '#0d6efd', '#ffc107', '#6c757d', '#dc3545', '#0dcaf0'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        @endif

        // --- 2. Batch Bar Chart ---
        @if(!empty($batchData))
            const batchCtx = document.getElementById('batchChart').getContext('2d');
            new Chart(batchCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($batchData)) !!},
                    datasets: [{
                        label: 'Registered Alumni',
                        data: {!! json_encode(array_values($batchData)) !!},
                        backgroundColor: '#198754', // KSU Green
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        @endif
    });
</script>
@endsection