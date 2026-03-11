@extends('layouts.admin')

@section('content')

{{-- 🟢 BULLETPROOF OVERRIDE --}}
@php
    $verifiedCount = $verifiedCount ?? \App\Models\User::where('status', 1)->count();
    $pendingCount  = $pendingCount ?? \App\Models\User::where('status', 0)->count();
    $unclaimedCount = $unclaimedCount ?? \App\Models\MisAlumniRecord::where('is_claimed', false)->count();
    $totalUsers    = $totalUsers ?? \App\Models\User::count();
    $recentUsers   = $recentUsers ?? \App\Models\User::orderBy('created_at', 'desc')->take(5)->get();

    // Default arrays if route override fails
    $courses = $courses ?? [];
    $batches = $batches ?? [];

    $employmentData = $employmentData ?? \App\Models\User::select('employment_status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->whereNotNull('employment_status')->where('employment_status', '!=', '')
        ->groupBy('employment_status')->pluck('total', 'employment_status')->toArray();

    $batchData = $batchData ?? \App\Models\User::select('batch', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->whereNotNull('batch')->where('batch', '!=', '')
        ->groupBy('batch')->orderBy('total', 'desc')->take(5)->pluck('total', 'batch')->toArray();
@endphp

<div class="container-fluid py-4">

    {{-- TOP ROW: 4 Core Metrics to clear up ICT Confusion --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-success border-4">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-user-check fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-0">Verified Alumni</p>
                        <h3 class="fw-bold text-dark mb-0">{{ $verifiedCount }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-warning border-4">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-user-clock fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-0">Unverified Web Accts</p>
                        <h3 class="fw-bold text-dark mb-0">{{ $pendingCount }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
            <a href="{{ route('admin.claims.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-danger border-4 transition-hover">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-database fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-0">Unclaimed MIS Records</p>
                            <h3 class="fw-bold text-dark mb-0">{{ $unclaimedCount }}</h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-primary border-4">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-0">Total Registered</p>
                        <h3 class="fw-bold text-dark mb-0">{{ $totalUsers }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MIDDLE ROW: Advanced Analytics Charts --}}
    <div class="row mb-4">
        {{-- Chart 1: Employment Status WITH FILTERS --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-pie me-2 text-primary"></i> Employment Distribution</h6>
                    
                    {{-- 🟢 ICT Requested Filters (Course & Batch) --}}
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex gap-2">
                        <select name="course" class="form-select form-select-sm" style="width: auto;">
                            <option value="">All Programs</option>
                            @foreach($courses as $c)
                                <option value="{{ $c }}" {{ request('course') == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                        <select name="batch" class="form-select form-select-sm" style="width: auto;">
                            <option value="">All Batches</option>
                            @foreach($batches as $b)
                                <option value="{{ $b }}" {{ request('batch') == $b ? 'selected' : '' }}>{{ $b }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        @if(request('course') || request('batch'))
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                        @endif
                    </form>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center p-4" style="position: relative; height: 300px;">
                    @if(empty($employmentData))
                        <p class="text-muted small text-center">No employment data available for this selection.</p>
                    @else
                        <canvas id="employmentChart"></canvas>
                    @endif
                </div>
            </div>
        </div>

        {{-- Chart 2: Top Batches --}}
        <div class="col-md-5">
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

<style>
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
</style>
@endsection