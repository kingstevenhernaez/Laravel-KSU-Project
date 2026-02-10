<!DOCTYPE html>
<html lang="en">

@include('frontend.layouts.header')

<body class="direction-ltr">

    <div id="preloader">
        <div id="preloader_status">
            <img src="{{ asset('assets/images/branding/ksu-logo.png') }}" alt="KSU Alumni"/>
        </div>
    </div>

    @include('frontend.layouts.nav')

    @yield('content')

    @include('frontend.layouts.footer')

    @include('frontend.layouts.script')

    <script>
        window.onload = function() {
            var preloader = document.getElementById('preloader');
            if(preloader) {
                preloader.style.display = 'none';
            }
        };
    </script>

</body>
</html>