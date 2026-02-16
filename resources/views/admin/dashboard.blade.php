@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4" style="background-color: #f4f7f6; min-height: 100vh;">

    {{-- High-Impact Header --}}
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h2 class="fw-extrabold text-dark mb-1" style="letter-spacing: -1px;">System Analytics</h2>
            <p class="text-muted mb-0">Live monitoring of KSU Alumni registrations and verification status.</p>
        </div>
        <div class="date-badge bg-white px-4 py-2 rounded-pill shadow-sm border border-light">
            <i class="far fa-calendar-alt text-success me-2"></i>
            <span class="fw-bold">{{ now()->format('F d, Y') }}</span>
        </div>
    </div>

    {{-- Premium Statistic Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-xl-4 col-md-6">
            <div class="ksu-stat-card bg-white p-4 rounded-4 shadow-sm border-0 position-relative overflow-hidden" style="transition: 0.3s;">
                <div class="stat-icon-bg" style="position: absolute; right: -20px; top: -20px; font-size: 8rem; opacity: 0.03; color: #004d00;">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-success-subtle rounded-3 p-3 me-3">
                        <i class="fas fa-user-graduate fa-2x text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Verified Alumni</h6>
                        <h2 class="fw-bold mb-0">{{ $total_alumni }}</h2>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-success small fw-bold"><i class="fas fa-arrow-up me-1"></i> +12.5%</span>
                    <span class="text-muted small ms-2">from last month</span>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="ksu-stat-card bg-white p-4 rounded-4 shadow-sm border-0 position-relative overflow-hidden">
                <div class="stat-icon-bg" style="position: absolute; right: -20px; top: -20px; font-size: 8rem; opacity: 0.03; color: #f6c23e;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-warning-subtle rounded-3 p-3 me-3">
                        <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Pending Claims</h6>
                        <h2 class="fw-bold mb-0">{{ $pending_verify }}</h2>
                    </div>
                </div>
                <div class="mt-3 text-muted small">Requires registrar validation.</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="ksu-stat-card bg-white p-4 rounded-4 shadow-sm border-0 position-relative overflow-hidden">
                <div class="stat-icon-bg" style="position: absolute; right: -20px; top: -20px; font-size: 8rem; opacity: 0.03; color: #2e59d9;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-primary-subtle rounded-3 p-3 me-3">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Active Users</h6>
                        <h2 class="fw-bold mb-0">{{ $total_users }}</h2>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modern Table Section --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-4 px-4 d-flex justify-content-between align-items-center border-bottom-0">
            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-history text-success me-2"></i> Recent Activity Log</h5>
            <a href="{{ route('admin.alumni.index') }}" class="btn btn-light btn-sm fw-bold px-3">View Master List</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">
                        <tr>
                            <th class="px-4 py-3">User Profile</th>
                            <th class="py-3">Email Address</th>
                            <th class="py-3 text-center">Date Joined</th>
                            <th class="py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($alumni as $alum)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3 bg-success-subtle text-success fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($alum->first_name ?? 'A', 0, 1)) }}
                                    </div>
                                    <span class="fw-bold text-dark">{{ $alum->first_name ?? 'N/A' }} {{ $alum->last_name ?? '' }}</span>
                                </div>
                            </td>
                            <td class="text-muted">{{ $alum->email }}</td>
                            <td class="text-center fw-medium">{{ \Carbon\Carbon::parse($alum->created_at)->format('M d, Y') }}</td>
                            <td class="text-center">
                                @if($alum->email_verified_at)
                                    <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2 fw-bold" style="font-size: 0.7rem;">
                                        <i class="fas fa-check-circle me-1"></i> VERIFIED
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-2 fw-bold" style="font-size: 0.7rem;">
                                        <i class="fas fa-clock me-1"></i> PENDING
                                    </span>
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

<style>
    /* Dashboard Specific Styles */
    .ksu-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
    }
    .bg-success-subtle { background-color: #e8f5e9 !important; }
    .bg-warning-subtle { background-color: #fff8e1 !important; }
    .bg-primary-subtle { background-color: #e3f2fd !important; }
    .fw-extrabold { font-weight: 800; }
</style>
@endsection