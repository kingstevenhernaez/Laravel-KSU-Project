<div class="ksu-sidebar d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 280px; min-height: 100vh; background: linear-gradient(180deg, #004d00 0%, #0a3d0a 100%); position: relative;">
    {{-- Decorative Branding Header --}}
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
        {{-- Section: Control Center --}}
        <li class="nav-label text-uppercase text-white-50 fw-bold mb-2 ps-3" style="font-size: 0.7rem; letter-spacing: 1.5px;">Control Center</li>
        
        <li class="nav-item mb-2">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large me-3"></i> Dashboard Overview
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('admin.mis_sync.index') }}" class="nav-link text-white {{ Request::is('admin/mis-sync*') ? 'active' : '' }}">
                <i class="fas fa-database me-3"></i> MIS Synchronization
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('admin.alumni.index') }}" class="nav-link text-white {{ Request::is('admin/alumni') ? 'active' : '' }}">
                <i class="fas fa-user-graduate me-3"></i> Master Alumni List
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('admin.alumni.pending') }}" class="nav-link text-white {{ Request::is('admin/alumni/pending') ? 'active' : '' }}">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <span><i class="fas fa-id-badge me-3"></i> Pending Claims</span>
                    <span class="badge rounded-pill bg-warning text-dark" style="font-size: 0.65rem;">3</span>
                </div>
            </a>
        </li>

        <hr class="border-light opacity-10 my-4">

        {{-- Section: Campus Engagement --}}
        <li class="nav-label text-uppercase text-white-50 fw-bold mb-2 ps-3" style="font-size: 0.7rem; letter-spacing: 1.5px;">Campus Engagement</li>

        <li class="nav-item mb-2">
            <a href="{{ route('admin.events.index') }}" class="nav-link text-white {{ Request::is('admin/events*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check me-3"></i> University Events
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('admin.news.index') }}" class="nav-link text-white {{ Request::is('admin/news*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn me-3"></i> News & Announcements
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('admin.jobs.index') }}" class="nav-link text-white {{ Request::is('admin/jobs*') ? 'active' : '' }}">
                <i class="fas fa-briefcase me-3"></i> Job Postings
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('admin.tracer.index') }}" class="nav-link text-white {{ Request::is('admin/tracer*') ? 'active' : '' }}">
                <i class="fas fa-analytics me-3"></i> Tracer Analytics
            </a>
        </li>

        <hr class="border-light opacity-10 my-4">

        {{-- Section: Communication --}}
        <li class="nav-label text-uppercase text-white-50 fw-bold mb-2 ps-3" style="font-size: 0.7rem; letter-spacing: 1.5px;">Communication</li>

        <li class="nav-item mb-2">
            <a href="{{ route('admin.messages.create') }}" class="nav-link text-white {{ Request::is('admin/messages/create') ? 'active' : '' }}">
                <i class="fas fa-paper-plane me-3"></i> New Email Blast
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('admin.messages.sent') }}" class="nav-link text-white {{ Request::is('admin/messages/sent') ? 'active' : '' }}">
                <i class="fas fa-envelope-open-text me-3"></i> History Log
            </a>
        </li>
    </ul>

    {{-- Refined User Profile Section --}}
    <div class="admin-profile-footer mt-4 p-3 rounded-4" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <div class="avatar-ksu bg-warning text-dark fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; font-size: 1.1rem;">
                    {{ strtoupper(substr(Auth::user()->first_name ?? 'A', 0, 1)) }}
                </div>
            </div>
            <div class="flex-grow-1 ms-3 overflow-hidden">
                <p class="mb-0 fw-bold text-white text-truncate">{{ Auth::user()->first_name ?? 'Admin' }}</p>
                <small class="text-white-50">Main Admin</small>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="ms-2">
                @csrf
                <button type="submit" class="btn btn-link p-0 text-white-50 hover-text-danger">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* Custom Sidebar Styles */
    .custom-nav-pills .nav-link {
        border-radius: 12px;
        padding: 12px 18px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        color: rgba(255,255,255,0.7) !important;
    }
    .custom-nav-pills .nav-link:hover {
        background: rgba(255,255,255,0.1);
        color: #fff !important;
        transform: translateX(5px);
    }
    .custom-nav-pills .nav-link.active {
        background: #FFD700 !important; /* KSU GOLD */
        color: #004d00 !important; /* KSU DEEP GREEN */
        box-shadow: 0 10px 20px -5px rgba(255,215,0,0.4);
        font-weight: 700;
    }
    .custom-nav-pills .nav-link i {
        width: 20px;
        text-align: center;
    }
    .hover-text-danger:hover { color: #ff4d4d !important; }
</style>