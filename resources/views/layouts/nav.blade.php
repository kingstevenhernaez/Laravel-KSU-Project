<style>
    /* --- ICON & TEXT STYLES --- */
    .ksu-icon-gold img {
        filter: invert(84%) sepia(35%) saturate(1131%) hue-rotate(357deg) brightness(103%) contrast(104%) !important;
    }
    
    .welcome-text {
        color: #FFD700 !important;
        font-weight: bold;
    }

    /* --- SIDEBAR TRANSFORMATION (POWER SELECTORS) --- */
    /* 1. Force the main Sidebar background to KSU Green */
    .sidebar-wrapper, 
    .left-menu,
    .vertical-menu,
    .sidebar,
    .main-sidebar,
    aside.main-sidebar {
        background-color: #006400 !important; /* KSU Green */
        background: #006400 !important;
        border-right: 2px solid #FFD700 !important; /* Gold divider */
    }

    /* 2. Make all sidebar text white */
    .sidebar-menu-item a, 
    .menu-link,
    .nav-link p,
    .nav-link span {
        color: #ffffff !important;
        transition: all 0.3s ease;
    }

    /* 3. Style the Active and Hover states with KSU Gold */
    .sidebar-menu-item.active a,
    .nav-item.menu-open > .nav-link,
    .nav-link.active,
    .sidebar-menu-item a:hover {
        background-color: #FFD700 !important;
        background: #FFD700 !important;
        color: #006400 !important; /* Green text on gold */
        border-radius: 8px;
    }

    /* 4. Turn sidebar icons white by default */
    .sidebar-menu-item i,
    .nav-link i,
    .sidebar-menu-item img {
        color: #ffffff !important;
        filter: brightness(0) invert(1);
    }

    /* 5. Turn active icon green to match the gold background */
    .nav-link.active i,
    .sidebar-menu-item.active i,
    .sidebar-menu-item a:hover i {
        filter: invert(18%) sepia(87%) saturate(2131%) hue-rotate(104deg) brightness(93%) contrast(105%) !important;
        color: #006400 !important;
    }
</style>
<div class="main-header pt-28 pb-27 px-30 bd-one bd-c-ebedf0 d-flex justify-content-between align-items-center" 
     style="background-color: #006400; border-bottom: 3px solid #FFD700 !important;">
    
    <div class="d-flex align-items-center cg-15">
        <div class="mobileMenu">
            <button class="bd-one bd-c-ededed bd-ra-12 w-30 h-30 d-flex justify-content-center align-items-center text-white p-0 bg-transparent">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <a href="{{ route('home') }}" class="d-flex align-items-center">
            <img src="{{ asset('images/ksu-logo.png') }}" alt="KSU Logo" style="height: 50px; width: auto;">
            <div class="d-none d-md-block mx-3" style="width: 2px; height: 35px; background-color: #FFD700;"></div>
            <img src="{{ asset('images/ksu-alumni-logo.png') }}" alt="Alumni Logo" class="d-none d-sm-block" style="height: 45px; width: auto;">
        </a>

        @can('Manage Alumni')
        <a href="{{ route('alumni.list-search-with-filter') }}"
            class="d-none d-sm-inline-block fs-15 fw-600 lh-25 text-dark py-10 px-26 bd-ra-12"
            style="background-color: #FFD700; border: 1px solid #fff;">
            {{ __('Find an Alumni') }}
        </a>
        @endcan
    </div>

    <div class="right d-flex justify-content-end align-items-center cg-15">
        
        @if (!empty(getOption('show_language_switcher')) && getOption('show_language_switcher') == STATUS_ACTIVE)
        <div class="dropdown headerUserDropdown lanDropdown">
            <button class="dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center cg-8" type="button" data-bs-toggle="dropdown">
                <div class="flex-shrink-0 w-42 h-42 rounded-circle overflow-hidden bd-one bd-c-white-5 d-flex justify-content-center align-items-center">
                    <img class="h-100 object-fit-cover w-100" src="{{ asset(selectedLanguage()?->flag) }}" alt="" />
                </div>
                <div class="text-start d-none d-md-block">
                    <h4 class="fs-15 fw-500 lh-18 text-white">{{ selectedLanguage()?->language }}</h4>
                </div>
            </button>
            <ul class="dropdown-menu dropdownItem-one">
                @foreach (appLanguages() as $app_lang)
                <li>
                    <a class="d-flex align-items-center cg-8" href="{{ url('/local/' . $app_lang->iso_code) }}">
                        <img src="{{ asset($app_lang->flag) }}" alt="" class="max-w-26" />
                        <p class="fs-14 fw-500 lh-16 text-707070">{{ $app_lang->language }}</p>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="d-flex align-items-center cg-17">
            <div class="d-flex align-items-center cg-5">
        @can('Chat')
<a href="{{ route('chats.index') }}" class="item-one ksu-icon-gold position-relative d-inline-flex align-items-center justify-content-center">
    <img src="{{ asset('assets/images/icon/chat-one.svg') }}" alt="" />
    <span class="notify_no" id="unseen-user-message" 
          style="position: absolute; top: -5px; right: -8px; background-color: #FFD700 !important; color: #006400 !important; border: 1px solid #006400; font-weight: bold; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 11px;">
          {{ userMessageUnseen() }}
    </span>
</a>
@endcan

                <div class="dropdown notifyDropdown">
    <button class="item-one dropdown-toggle ksu-icon-gold position-relative" type="button" data-bs-toggle="dropdown">
        <img src="{{ asset('assets/images/icon/bell.svg') }}" alt="" />
        <span class="notify_no" 
              style="position: absolute; top: -5px; right: -8px; background-color: #FFD700 !important; color: #006400 !important; border: 1px solid #006400; font-weight: bold; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 11px;">
              {{ count(userNotification('unseen')) }}
        </span>
    </button>
    </div>
            </div>

            <div class="dropdown headerUserDropdown">
                <button class="dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center cg-8" type="button" data-bs-toggle="dropdown">
                    <div class="w-42 h-42 rounded-circle overflow-hidden bd-one" style="border-color: #FFD700 !important;">
                        <img src="{{ asset(getFileUrl(auth()->user()->image)) }}" alt="{{ auth()->user()->name }}" />
                    </div>
                    <div class="text-start d-none d-sm-block">
                        <p class="fs-12 fw-400 lh-15 text-white" style="color: #FFD700 !important; opacity: 0.9;">{{ __('Welcome') }}</p>
                        <h4 class="fs-15 fw-600 lh-18 text-white">{{ auth()->user()->name }}</h4>
                    </div>
                </button>
                <ul class="dropdown-menu dropdownItem-one">
                    <li>
                        <a class="d-flex align-items-center cg-8" href="{{ route('profile') }}">
                            <p class="fs-14 fw-500 lh-16 text-707070">{{ __('Profile') }}</p>
                        </a>
                    </li>
                    <li>
                        <a class="d-flex align-items-center cg-8" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <p class="fs-14 fw-500 lh-16 text-707070">{{ __('Logout') }}</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>