<div class="main-navigation-wrapper">

    <div id="ksu-navbar" class="py-12" style="background:#0B3D2E; border-bottom: 4px solid #FFC72C;">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-4 col-12">
                    <a href="{{ route('index') }}" class="d-flex align-items-center gap-3">
                        @php
                            // Fetching the dynamic logo URL from the system settings
                            $logoFileId = getOption('app_logo'); // Matches "Logo Black" or "App Logo" in your settings
                            $logoUrl = $logoFileId ? getFileUrl($logoFileId) : asset('frontend/images/logo.png');
                        @endphp
                        
                        <img src="{{ $logoUrl }}" 
                             alt="{{ getOption('app_name') }}" 
                             style="height:55px; width:auto; object-fit:contain;">
                        
                        <div class="d-flex flex-column">
                            <span style="color:#FFC72C; font-weight:800; font-size:22px; line-height:1.1; letter-spacing:0.5px; text-transform:uppercase;">
                                {{ getOption('app_name') }}
                            </span>
                            <span style="color:#ffffff; font-size:11px; font-weight:500; letter-spacing:1px; opacity: 0.9;">
                                KALINGA STATE UNIVERSITY
                            </span>
                        </div>
                    </a>
                </div>

                <div class="col-lg-6 d-none d-lg-block">
                    <nav class="navbar navbar-expand-lg p-0">
                        <ul class="navbar-nav d-flex flex-row justify-content-center gap-2 w-100">
                            @php
                                $linkStyle = 'color:#fff; font-weight:600; padding:8px 15px; border-radius:8px; transition:all 0.3s ease;';
                                $hoverLogic = 'onmouseover="this.style.color=\'#FFC72C\'; this.style.background=\'rgba(255,255,255,0.1)\';" onmouseout="this.style.color=\'#fff\'; this.style.background=\'transparent\';"';
                            @endphp

                            <li class="nav-item"><a class="nav-link" style="{{ $linkStyle }}" {!! $hoverLogic !!} href="{{ route('index') }}">Home</a></li>
                            <li class="nav-item"><a class="nav-link" style="{{ $linkStyle }}" {!! $hoverLogic !!} href="{{ route('all.alumni') }}">Alumni</a></li>
                            <li class="nav-item"><a class="nav-link" style="{{ $linkStyle }}" {!! $hoverLogic !!} href="{{ route('all.event') }}">Events</a></li>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" style="{{ $linkStyle }}" {!! $hoverLogic !!} data-bs-toggle="dropdown">Community</a>
                                <ul class="dropdown-menu shadow-lg border-0" style="background:#0E4A37; border-top: 3px solid #FFC72C !important;">
                                    <li><a class="dropdown-item text-white py-2" href="{{ route('all.job') }}">Jobs</a></li>
                                    <li><a class="dropdown-item text-white py-2" href="{{ route('all.stories') }}">Stories</a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a class="nav-link" style="{{ $linkStyle }}" {!! $hoverLogic !!} href="{{ route('contact_us') }}">Contact</a></li>
                        </ul>
                    </nav>
                </div>

                <div class="col-lg-2 col-6 text-end d-flex align-items-center justify-content-end gap-3">
                    @auth
                        @php
                            $user = auth()->user();
                            $isAdmin = in_array((int)$user->role, [USER_ROLE_ADMIN, USER_ROLE_SUPER_ADMIN], true);
                            $dashboardUrl = $isAdmin ? route('admin.dashboard') : route('profile');
                        @endphp
                        <a href="{{ $dashboardUrl }}" class="d-flex align-items-center">
                            <div class="rounded-circle overflow-hidden" style="width:40px; height:40px; border: 2px solid #FFC72C;">
                                <img src="{{ asset(getFileUrl($user->image)) }}" class="w-100 h-100 object-fit-cover">
                            </div>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn" style="background:#FFC72C; color:#0B3D2E; font-weight:700; border-radius:10px; padding:10px 25px; transition: 0.3s;">
                            LOGIN
                        </a>
                    @endauth
                    
                    <button class="navbar-toggler d-lg-none text-white border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                        <i class="fa-solid fa-bars fs-2"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>