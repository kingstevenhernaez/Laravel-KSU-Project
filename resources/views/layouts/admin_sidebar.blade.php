<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 250px; min-height: 100vh;">
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4 fw-bold">Admin Panel</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ Request::is('admin/dashboard') ? 'active bg-success' : '' }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('admin.alumni.index') }}" class="nav-link text-white {{ Request::is('admin/alumni*') ? 'active bg-success' : '' }}">
                <i class="fas fa-user-graduate me-2"></i> Alumni List
            </a>
        </li>

        <li>
            <a href="{{ route('admin.events.index') }}" class="nav-link text-white {{ Request::is('admin/events*') ? 'active bg-success' : '' }}">
                <i class="fas fa-calendar-alt me-2"></i> Events
            </a>
        </li>

        <li>
            <a href="{{ route('admin.news.index') }}" class="nav-link text-white {{ Request::is('admin/news*') ? 'active bg-success' : '' }}">
                <i class="fas fa-newspaper me-2"></i> News & Updates
            </a>
        </li>
        <li>
    <a href="{{ route('admin.jobs.index') }}" class="nav-link text-white {{ Request::is('admin/jobs*') ? 'active bg-success' : '' }}">
        <i class="fas fa-briefcase me-2"></i> Jobs
    </a>
</li>

<li>
    <a href="{{ route('admin.tracer.index') }}" class="nav-link text-white {{ Request::is('admin/tracer*') ? 'active bg-success' : '' }}">
        <i class="fas fa-chart-line me-2"></i> Tracer Study
    </a>
</li>

<li>
    <a href="{{ route('admin.messages.create') }}" class="nav-link text-white {{ Request::is('admin/messages*') ? 'active bg-success' : '' }}">
        <i class="fas fa-envelope me-2"></i> Email Blast
    </a>
</li>
    </ul>
    
    <hr>
    
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}
            </div>
            <strong>{{ Auth::user()->first_name ?? 'Admin' }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">Sign out</button>
                </form>
            </li>
        </ul>
    </div>
</div>