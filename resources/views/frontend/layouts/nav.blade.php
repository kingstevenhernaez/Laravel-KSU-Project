<!-- Start Header -->
<div>

    <!-- Top Header -->
    <div class="pt-19 pb-15 d-none d-lg-block bg-white">
        <div class="container">
            <div class="row align-items-center rg-10">

                <!-- Left -->
                <div class="col-lg-6">
                    <div class="d-flex justify-content-center justify-content-lg-start align-items-center flex-wrap cg-23 rg-10">
                        <a href="mailto:{{ getOption('app_email') }}"
                           class="d-flex align-items-center cg-7 fs-18 fw-600 lh-28 text-black-color">
                            <img src="{{ asset('frontend/images/icon/envelope.svg') }}" alt="">
                            <p>Email : <span class="fw-500">{{ getOption('app_email') }}</span></p>
                        </a>

                        <a href="tel:{{ getOption('app_contact_number') }}"
                           class="d-flex align-items-center cg-7 fs-18 fw-600 lh-28 text-black-color">
                            <img src="{{ asset('frontend/images/icon/phone.svg') }}" alt="">
                            <p>Hotline : <span class="fw-500">{{ getOption('app_contact_number') }}</span></p>
                        </a>
                    </div>
                </div>

                <!-- Right -->
                <div class="col-lg-6">
                    <div class="d-flex justify-content-center justify-content-lg-end align-items-center g-11 flex-wrap">

                        <!-- Search -->
                        <form action="{{ route('public.alumni.directory') }}" method="GET"
                              class="d-flex align-items-center" style="gap:8px; min-width:360px;">
                            <input type="text" name="q" value="{{ request('q') }}"
                                   class="form-control" placeholder="Search alumni..."
                                   style="height:40px;border-radius:10px;">
                            <button type="submit"
                                    class="btn"
                                    style="height:40px;border-radius:10px;background:#FFC72C;color:#1b1c17;font-weight:700;">
                                Search
                            </button>
                        </form>

                    @auth
    @php
        $u = auth()->user();
        $displayName = $u->name ?? $u->nick_name ?? $u->email;
        $role = $u->role ?? null;
        $isAdmin = in_array((int)$role, [USER_ROLE_ADMIN, USER_ROLE_SUPER_ADMIN], true);
        $accountUrl = $isAdmin ? route('admin.dashboard') : route('profile');
    @endphp
    <a href="{{ $accountUrl }}" class="btn btn-outline-dark fw-600">
        {{ $displayName }}
    </a>
@else
    <a href="{{ route('login') }}" class="btn btn-dark fw-600">Login</a>
    @if (!getOption('disable_registration'))
        <a href="{{ route('claim.show') }}" class="btn btn-outline-dark fw-600">Sign Up</a>
    @endif
@endauth

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MAIN NAVBAR (KSU THEME) -->
    <div id="ksu-navbar" class="py-16" style="background:#0B3D2E;">
        <div class="container">
            <div class="row align-items-center">

 <!-- Logo -->
<div class="col-lg-3 col-6">
    @php
        // Primary: DB-driven logo IDs (settings -> file_managers).
        // getSettingImage() returns a public URL with cache-busting (?v=...).
        $logoUrl =
            getSettingImage('app_black_logo')
            ?: getSettingImage('app_logo')
            ?: getSettingImage('app_white_logo');
    @endphp

    <a href="{{ route('index') }}" class="d-flex align-items-center gap-2" style="min-height:55px;">
        <img
            src="{{ $logoUrl }}"
            alt="{{ getOption('app_name') }}"
            style="height:40px;width:auto;object-fit:contain;"
        >
        <span style="color:#FFC72C;font-weight:800;letter-spacing:.6px;">
            KSUFAAI
        </span>
    </a>
</div

<!-- Menu -->
                <div class="col-lg-7 col-6">
                    <nav class="navbar navbar-expand-lg p-0">
                        <button class="navbar-toggler bg-white" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                            ☰
                        </button>

                        <div class="navbar-collapse offcanvas offcanvas-start" id="offcanvasNavbar">
                            <button type="button"
                                    class="btn-close d-lg-none ms-auto m-3"
                                    data-bs-dismiss="offcanvas"></button>

                            <ul class="navbar-nav d-flex flex-row justify-content-center gap-4 w-100">

                                @php
                                    $navItem = 'color:#fff;font-weight:600;padding:.5rem .75rem;border-radius:.5rem;';
                                    $hover = 'onmouseover="this.style.color=\'#FFC72C\'" onmouseout="this.style.color=\'#fff\'"';
                                @endphp

                                <li class="nav-item"><a class="nav-link" style="{{ $navItem }}" {!! $hover !!} href="{{ route('index') }}">Home</a></li>
                                <li class="nav-item"><a class="nav-link" style="{{ $navItem }}" {!! $hover !!} href="{{ route('all.alumni') }}">Alumni</a></li>
                                <li class="nav-item"><a class="nav-link" style="{{ $navItem }}" {!! $hover !!} href="{{ route('all.event') }}">Events</a></li>
                                <li class="nav-item"><a class="nav-link" style="{{ $navItem }}" {!! $hover !!} href="{{ route('our.news') }}">News</a></li>
                                <li class="nav-item"><a class="nav-link" style="{{ $navItem }}" {!! $hover !!} href="{{ route('our.notice') }}">Notice</a></li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" style="{{ $navItem }}" {!! $hover !!}
                                       data-bs-toggle="dropdown">Community</a>
                                    <ul class="dropdown-menu" style="background:#0E4A37;border:1px solid rgba(255,255,255,.15);">
                                        <li><a class="dropdown-item text-white" href="{{ route('all.job') }}">Jobs</a></li>
                                        <li><a class="dropdown-item text-white" href="{{ route('all.membership') }}">Membership</a></li>
                                        <li><a class="dropdown-item text-white" href="{{ route('all.stories') }}">Stories</a></li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                    </nav>
                </div>

                <!-- Contact -->
                <div class="col-lg-2 d-none d-lg-block text-end">
                    <a href="{{ route('contact_us') }}" style="color:#FFC72C;font-weight:700;">
                        Contact Us →
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>
<!-- End Header -->
