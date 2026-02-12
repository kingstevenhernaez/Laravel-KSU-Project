<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 260px; min-height: 100vh; box-shadow: 4px 0 10px rgba(0,0,0,0.3);">
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <div class="bg-success rounded-3 p-2 me-2">
            <i class="fas fa-university fa-lg"></i>
        </div>
        <span class="fs-4 fw-bold tracking-tight">Admin Panel</span>
    </a>
    <hr class="opacity-50">

    <ul class="nav nav-pills flex-column mb-auto">
        <small class="text-uppercase text-muted fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Main Menu</small>
        
        <li class="nav-item mb-1">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ Request::is('admin/dashboard') ? 'active bg-success shadow' : 'hover-opacity' }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>

        <li class="mb-1">
            <a href="{{ route('admin.alumni.index') }}" class="nav-link text-white {{ Request::is('admin/alumni*') ? 'active bg-success shadow' : 'hover-opacity' }}">
                <i class="fas fa-user-graduate me-2"></i> Alumni List
            </a>
        </li>

        <hr class="opacity-25 my-2">

        <small class="text-uppercase text-muted fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Alumni Services</small>

        <li class="mb-1">
            <a href="{{ route('admin.events.index') }}" class="nav-link text-white {{ Request::is('admin/events*') ? 'active bg-success shadow' : 'hover-opacity' }}">
                <i class="fas fa-calendar-alt me-2"></i> Events
            </a>
        </li>

        <li class="mb-1">
            <a href="{{ route('admin.news.index') }}" class="nav-link text-white {{ Request::is('admin/news*') ? 'active bg-success shadow' : 'hover-opacity' }}">
                <i class="fas fa-newspaper me-2"></i> News & Updates
            </a>
        </li>

        <li class="mb-1">
            <a href="{{ route('admin.jobs.index') }}" class="nav-link text-white {{ Request::is('admin/jobs*') ? 'active bg-success shadow' : 'hover-opacity' }}">
                <i class="fas fa-briefcase me-2"></i> Jobs
            </a>
        </li>

        <li class="mb-1">
            <a href="{{ route('admin.tracer.index') }}" class="nav-link text-white {{ Request::is('admin/tracer*') ? 'active bg-success shadow' : 'hover-opacity' }}">
                <i class="fas fa-chart-line me-2"></i> Tracer Study
            </a>
        </li>

        <hr class="opacity-25 my-2">

        <small class="text-uppercase text-muted fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Communication</small>

        <li class="mb-1">
            <a href="{{ route('admin.messages.create') }}" class="nav-link text-white {{ Request::is('admin/messages/create') ? 'active bg-success shadow' : 'hover-opacity' }}">
                <i class="fas fa-paper-plane me-2"></i> Email Blast
            </a>
        </li>

        <li class="mb-1">
            <a href="{{ route('admin.messages.sent') }}" class="nav-link text-white {{ Request::is('admin/messages/sent') ? 'active bg-success shadow' : 'hover-opacity' }}">
                <i class="fas fa-history me-2"></i> Sent Box
            </a>
        </li>
    </ul>
    
    <hr class="opacity-50">
    
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle p-2 rounded hover-bg" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2 border border-2 border-white border-opacity-25" style="width: 35px; height: 35px;">
                {{ strtoupper(substr(Auth::user()->first_name ?? 'A', 0, 1)) }}
            </div>
            <div class="d-flex flex-column">
                <strong class="lh-1">{{ Auth::user()->first_name ?? 'Admin' }}</strong>
                <small class="text-muted" style="font-size: 0.7rem;">Administrator</small>
            </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item py-2">
                        <i class="fas fa-sign-out-alt me-2 text-danger"></i> Sign out
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

<style>
    .hover-opacity:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transition: 0.3s;
    }
    .hover-bg:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }
    .nav-link.active {
        transition: transform 0.2s;
        transform: translateX(5px);
    }
</style>