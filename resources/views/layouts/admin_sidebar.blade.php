@php
    $rawRoles = Auth::user()->role_name ?? '[]';
    $userRoles = is_array(json_decode($rawRoles, true)) ? json_decode($rawRoles, true) : [$rawRoles];
    
    // Super Admin Check (Overrides everything)
    $isSuperAdmin = in_array('super_admin', $userRoles);
    
    // Control Center Permissions
    $canStaff   = $isSuperAdmin || in_array('manage_staff', $userRoles);
    $canMis     = $isSuperAdmin || in_array('mis_sync', $userRoles);
    $canAlumni  = $isSuperAdmin || in_array('alumni_list', $userRoles);
    $canPending = $isSuperAdmin || in_array('pending_claims', $userRoles);
    
    // Campus Engagement Permissions
    $canEvents  = $isSuperAdmin || in_array('manage_events', $userRoles);
    $canNews    = $isSuperAdmin || in_array('manage_news', $userRoles);
    $canJobs    = $isSuperAdmin || in_array('manage_jobs', $userRoles);
    $canTracer  = $isSuperAdmin || in_array('tracer_analytics', $userRoles);

    $canReports = $isSuperAdmin || in_array('generate_reports', $userRoles);

    $showCampusSection = $canEvents || $canNews || $canJobs || $canTracer || $canReports;

    // Communication Permissions
    $canEmail   = $isSuperAdmin || in_array('email_blast', $userRoles);
    $canHistory = $isSuperAdmin || in_array('email_history', $userRoles);
    $showCommSection = $canEmail || $canHistory;
@endphp

<div class="ksu-sidebar d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 280px; min-height: 100vh; background: linear-gradient(180deg, #004d00 0%, #0a3d0a 100%); position: relative;">
    {{-- Branding --}}
    <div class="brand-wrapper text-center py-4 mb-2">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
            <div class="ksu-logo-glow mb-2 mx-auto d-flex align-items-center justify-content-center" style="width: 65px; height: 65px; background: white; border-radius: 15px; box-shadow: 0 0 20px rgba(255,215,0,0.3);">
                <img src="{{ asset('assets/images/branding/ksu-logo.png') }}" alt="KSU" style="width: 50px;">
            </div>
            <h5 class="fw-bold text-white mb-0 tracking-tight" style="letter-spacing: 1px;">ALUMNI <span style="color: #FFD700;">SYSTEM</span></h5>
            <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Administrator Portal</small>
        </a>
    </div>

    <hr class="border-light opacity-10 mx-n3 mb-4">

    <ul class="nav nav-pills flex-column mb-auto custom-nav-pills">
        <li class="nav-label text-uppercase text-white-50 fw-bold mb-2 ps-3" style="font-size: 0.7rem; letter-spacing: 1.5px;">Control Center</li>
        
        <li class="nav-item mb-2">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large me-3"></i> Dashboard Overview
            </a>
        </li>

        @if($canStaff)
        <li class="nav-item mb-2">
            <a href="{{ route('admin.roles.index') }}" class="nav-link text-white {{ Request::is('admin/staff*') || Request::is('admin/roles*') ? 'active' : '' }}">
                <i class="fas fa-user-shield me-3"></i> Staff Management
            </a>
        </li>
        @endif

        @if($canMis)
        <li class="nav-item mb-2">
            <a href="{{ route('admin.mis_sync.index') }}" class="nav-link text-white {{ Request::is('admin/mis-sync*') ? 'active' : '' }}">
                <i class="fas fa-database me-3"></i> MIS Synchronization
            </a>
        </li>
        @endif

        @if($canAlumni)
        <li class="nav-item mb-2">
            <a href="{{ route('admin.alumni.index') }}" class="nav-link text-white {{ Request::is('admin/alumni') ? 'active' : '' }}">
                <i class="fas fa-user-graduate me-3"></i> Master Alumni List
            </a>
        </li>
        @endif

  @if($canPending)
        <li class="nav-item mb-2">
            {{-- 🟢 THE FIX: URL updated to reflect the unclaimed-records route --}}
            <a href="{{ route('admin.claims.index') }}" class="nav-link text-white {{ Request::is('admin/unclaimed-records*') ? 'active' : '' }}">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div>
                        <i class="fas fa-user-clock me-3"></i> Unclaimed Records
                    </div>
                    
                    {{-- DYNAMIC BADGE --}}
                    @php 
                        $sidebarPendingCount = \App\Models\User::where('status', 0)->count(); 
                    @endphp
                    
                    @if($sidebarPendingCount > 0)
                        <span class="badge bg-warning text-dark rounded-pill">{{ $sidebarPendingCount }}</span>
                    @endif
                </div>
            </a>
        </li>
        @endif

        @if($showCampusSection)
        <hr class="border-light opacity-10 my-4">
        <li class="nav-label text-uppercase text-white-50 fw-bold mb-2 ps-3" style="font-size: 0.7rem; letter-spacing: 1.5px;">Campus Engagement</li>
        @endif

        @if($canEvents)
        <li class="nav-item mb-2">
            <a href="{{ route('admin.events.index') }}" class="nav-link text-white {{ Request::is('admin/events*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check me-3"></i> University Events
            </a>
        </li>
        @endif

        @if($canNews)
        <li class="nav-item mb-2">
            <a href="{{ route('admin.news.index') }}" class="nav-link text-white {{ Request::is('admin/news*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn me-3"></i> News & Announcements
            </a>
        </li>
        @endif

        @if($canJobs)
        <li class="nav-item mb-2">
            <a href="{{ route('admin.jobs.index') }}" class="nav-link text-white {{ Request::is('admin/jobs*') ? 'active' : '' }}">
                <i class="fas fa-briefcase me-3"></i> Job Postings
            </a>
        </li>
        @endif

       @if($canTracer)
        <li class="nav-item mb-2">
            {{-- 🟢 ADDED ICON: fa-chart-line --}}
            <a href="{{ route('admin.tracer.index') }}" class="nav-link text-white {{ Request::is('admin/tracer*') ? 'active' : '' }}">
                <i class="fas fa-chart-line me-3"></i> Tracer
            </a>
        </li>
        @endif

        @if($canReports)
        <li class="nav-item mb-2">
            <a href="{{ route('admin.reports.index') }}" class="nav-link text-white {{ Request::is('admin/reports*') ? 'active' : '' }}">
                <i class="fas fa-print me-3"></i> Reports & Printing
            </a>
        </li>
        @endif

      @if($showCommSection)
        <hr class="border-light opacity-10 my-4">
        <li class="nav-label text-uppercase text-white-50 fw-bold mb-2 ps-3" style="font-size: 0.7rem; letter-spacing: 1.5px;">Communication</li>

        @if($canEmail)
        <li class="nav-item mb-2">
            {{-- 🟢 Fixed Route Name --}}
            <a href="{{ route('admin.emails.create') }}" class="nav-link text-white {{ Request::is('admin/email-center/create') ? 'active' : '' }}">
                <i class="fas fa-paper-plane me-3"></i> New Email Blast
            </a>
        </li>
        @endif

        @if($canHistory)
        <li class="nav-item mb-2">
            {{-- 🟢 Fixed Route Name --}}
            <a href="{{ route('admin.emails.index') }}" class="nav-link text-white {{ Request::is('admin/email-center') ? 'active' : '' }}">
                <i class="fas fa-envelope-open-text me-3"></i> Sent Email
            </a>
        </li>
        @endif
        @endif
    </ul>

    {{-- Footer --}}
    <div class="admin-profile-footer mt-4 p-3 rounded-4" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <div class="avatar-ksu bg-warning text-dark fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; font-size: 1.1rem;">
                    {{ strtoupper(substr(Auth::user()->first_name ?? 'A', 0, 1)) }}
                </div>
            </div>
            <div class="flex-grow-1 ms-3 overflow-hidden">
                <p class="mb-0 fw-bold text-white text-truncate">{{ Auth::user()->first_name ?? 'Admin' }}</p>
                <small class="text-white-50 text-uppercase" style="font-size: 0.65rem;">
                    {{ $isSuperAdmin ? 'Super Admin' : 'Staff Member' }}
                </small>
            </div>
            
            <div class="ms-2 d-flex align-items-center">
                <a href="{{ route('admin.profile.index') }}" class="btn btn-link p-0 text-white-50 hover-text-warning me-3" data-bs-toggle="tooltip" title="My Profile">
                    <i class="fas fa-cog"></i>
                </a>
                
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 text-white-50 hover-text-danger" data-bs-toggle="tooltip" title="Log Out">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-nav-pills .nav-link { border-radius: 12px; padding: 12px 18px; font-weight: 500; transition: all 0.3s ease; border: 1px solid transparent; color: rgba(255,255,255,0.7) !important; }
    .custom-nav-pills .nav-link:hover { background: rgba(255,255,255,0.1); color: #fff !important; transform: translateX(5px); }
    .custom-nav-pills .nav-link.active { background: #FFD700 !important; color: #004d00 !important; box-shadow: 0 10px 20px -5px rgba(255,215,0,0.4); font-weight: 700; }
    .custom-nav-pills .nav-link i { width: 20px; text-align: center; }
    .hover-text-danger:hover { color: #ff4d4d !important; }
    .hover-text-warning:hover { color: #FFD700 !important; }
</style>