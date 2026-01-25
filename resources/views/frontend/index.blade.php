@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')

<style>
    /* 1. Homepage Header/Navbar (Dark Green) */
    header, .main-menu-area, .bg-header-color {
        background-color: #004d00 !important; /* Deeper KSU Green */
    }
    
    .main-menu ul li a, .contact-us-link {
        color: #ffffff !important;
        font-weight: 600;
    }
    
    .main-menu ul li a:hover, .contact-us-link:hover {
        color: #FFD700 !important; /* Hover to Gold */
    }

    /* 2. Newsletter Section (Change Lime Green to KSU Gold) */
    .subscribe-section, .bg-cdef84 {
        background-color: #FFD700 !important; /* KSU Gold */
    }
    
    .subscribe-section h2, .subscribe-section p {
        color: #004d00 !important; /* Dark Green text for contrast */
        font-weight: 700;
    }

    /* 3. Footer Section (Solid KSU Green) */
    footer, .footer-area {
        background-color: #004d00 !important;
        border-top: 4px solid #FFD700 !important; /* Gold top border */
    }

    footer h4, footer a, footer p {
        color: #ffffff !important;
    }

    footer a:hover {
        color: #FFD700 !important;
    }

    /* 4. Social Media Icons in Footer */
    .footer-social-link a {
        background-color: #FFD700 !important;
        color: #004d00 !important;
    }
</style>

    <section class="home-banner" data-background="{{ getFileUrl(getOption('banner_background_breadcrumb')) }}">
        </section>
    <section class="joinCommunity-section pb-110">
        <div class="container">
            <div class="joinCommunity-wrap bg-event-bg py-78 px-10 bd-one bd-c-black-10 bd-ra-25 text-center"
                data-background="{{ asset('frontend/images/community-bg-1.png') }}">
                <span class="d-inline-block py-15 px-25 bg-color4 bd-ra-12 fs-18 fw-400 lh-18 mb-23">
                    {{ __('Join the KSU Community') }}
                </span>
                <h4 class="fs-36 fw-600 lh-36 text-black pb-42">{{ __('Why you should join the KSU Alumni Association') }}</h4>
                <div class="items d-flex justify-content-center flex-wrap g-25">
                    <div class="item flex-grow-1 max-w-370 bd-one bd-c-black-10 bd-ra-14 px-26 pt-51 pb-35 bg-white text-center hover-scale-1-1">
                        <img src="{{ asset(getFileUrl(getOption('join_us_left_icon'))) }}" class="mb-20" alt="" />
                        <h4 class="fs-24 fw-500 lh-28 text-black-color pb-9">{{ getOption('join_us_left_title') }}</h4>
                        <p class="fs-18 fw-400 lh-28 text-para-color">{!! getOption('join_us_left_description') !!}</p>
                    </div>
                    <div class="item flex-grow-1 max-w-370 bd-one bd-c-black-10 bd-ra-14 px-26 pt-51 pb-35 bg-white text-center hover-scale-1-1">
                        <img src="{{ asset(getFileUrl(getOption('join_us_middle_icon'))) }}" class="mb-20" alt="" />
                        <h4 class="fs-24 fw-500 lh-28 text-black-color pb-9">{{ getOption('join_us_middle_title') }}</h4>
                        <p class="fs-18 fw-400 lh-28 text-para-color">{!! getOption('join_us_middle_description') !!}</p>
                    </div>
                    <div class="item flex-grow-1 max-w-370 bd-one bd-c-black-10 bd-ra-14 px-26 pt-51 pb-35 bg-white text-center hover-scale-1-1">
                        <img src="{{ asset(getFileUrl(getOption('join_us_right_icon'))) }}" class="mb-20" alt="" />
                        <h4 class="fs-24 fw-500 lh-28 text-black-color pb-9">{{ getOption('join_us_right_title') }}</h4>
                        <p class="fs-18 fw-400 lh-28 text-para-color">{!! getOption('join_us_right_description') !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if (count($upcomingEvents))
        <section class="upcoming-events" style="background-color: #006400 !important;">
            <div class="container position-relative">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="text-center pb-37 max-w-677 m-auto">
                            <span class="d-inline-block py-15 px-25 rounded-pill bd-one bd-c-white fs-18 fw-400 lh-18 text-white mb-22">
                                {{ __('KSU Events') }}
                            </span>
                            <h4 class="pb-18 fs-50 fw-700 lh-50 text-white">{{ __('Our Upcoming Events') }}</h4>
                        </div>
                        </div>
                </div>
            </div>
        </section>
    @endif

    <section class="pt-110 pb-110 position-relative">
        <div class="world-map"><img src="{{ asset('frontend/images/world-map.png') }}" alt="" /></div>
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-6">
                    <div class="header-one">
                        <span class="d-inline-block py-15 px-25 bg-color4 bd-ra-12 fs-18 fw-400 lh-18 mb-19">
                            {{ __('Success Stories') }}
                        </span>
                        <h4 class="fs-50 fw-700 lh-60 text-black-color pb-38">{{ __('KSU Alumni Journeys') }}</h4>
                    </div>
                    </div>
                <div class="col-lg-6">
                    <div class="stories-right">
                        <h4 class="max-w-291 pb-17 fs-36 fw-700 lh-46 text-black-color">
                            {{ __('Your global KSU network.') }}
                        </h4>
                        <a href="{{ route('login') }}"
                            class="mb-54 py-15 px-32 bd-ra-12 d-inline-flex align-items-center cg-16 bg-primary-color fs-18 fw-600 lh-28 text-white hover-bg-color-one">
                            {{ __('Join the Community') }}
                            <i class="fa-solid fa-long-arrow-right"></i>
                        </a>
                        </div>
                </div>
            </div>
        </div>
    </section>

    @endsection