<div class="sidebar-menu-area">
    <div class="sidebar-layout">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('assets/images/branding/ksu-logo.png') }}" class="logo-lg" alt="Logo">
                    <img src="{{ asset('assets/images/branding/ksu-logo.png') }}" class="logo-sm" alt="Small Logo">
                </a>
            </div>
        </div>

        <div class="sidebar-content">
            <div class="sidebar-menu">
                <nav class="menu-navigation">
                    <ul>
                        <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}">
                                <span class="icon"><i class="fa-solid fa-gauge"></i></span>
                                <span class="text">{{ __('Dashboard') }}</span>
                            </a>
                        </li>

                        <li class="has-sub {{ Route::is('admin.alumni.*') ? 'active open' : '' }}">
                            <a href="#">
                                <span class="icon"><i class="fa-solid fa-user-graduate"></i></span>
                                <span class="text">{{ __('Alumni') }}</span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ route('admin.alumni.index') }}" class="{{ Route::is('admin.alumni.index') ? 'active' : '' }}">
                                        {{ __('All List') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.alumni.pending') }}" class="{{ Route::is('admin.alumni.pending') ? 'active' : '' }}">
                                        {{ __('Pending List') }}
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="{{ Route::is('admin.events.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.events.index') }}">
                                <span class="icon"><i class="fa-solid fa-calendar-days"></i></span>
                                <span class="text">{{ __('My Event') }}</span>
                            </a>
                        </li>

                        <li class="{{ Route::is('admin.jobs.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.jobs.index') }}">
                                <span class="icon"><i class="fa-solid fa-briefcase"></i></span>
                                <span class="text">{{ __('Jobs') }}</span>
                            </a>
                        </li>

                        <li class="{{ Route::is('admin.stories.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.stories.index') }}">
                                <span class="icon"><i class="fa-solid fa-book-open"></i></span>
                                <span class="text">{{ __('Stories') }}</span>
                            </a>
                        </li>

                        <li class="{{ Route::is('admin.notices.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.notices.index') }}">
                                <span class="icon"><i class="fa-solid fa-bullhorn"></i></span>
                                <span class="text">{{ __('Manage Notice') }}</span>
                            </a>
                        </li>

                        <li class="{{ Route::is('admin.transactions.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.transactions.index') }}">
                                <span class="icon"><i class="fa-solid fa-money-bill-transfer"></i></span>
                                <span class="text">{{ __('Manage Transaction') }}</span>
                            </a>
                        </li>

                        <li class="{{ Route::is('admin.tracer-survey.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.tracer-survey.index') }}">
                                <span class="icon"><i class="fa-solid fa-poll"></i></span>
                                <span class="text">{{ __('Tracer Surveys') }}</span>
                            </a>
                        </li>

                        <li class="{{ Route::is('admin.enrollment_sync.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.enrollment_sync.index') }}">
                                <span class="icon"><i class="fa-solid fa-sync"></i></span>
                                <span class="text">{{ __('Enrollment Sync') }}</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>