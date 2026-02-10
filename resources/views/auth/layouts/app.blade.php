<!DOCTYPE html>
<html lang="en">

@include('layouts.header')

<body class="direction-ltr">

    <div id="preloader" style="display: none;">
        <div id="preloader_status">
            <img src="{{ asset('assets/images/branding/ksu-logo.png') }}" alt="KSU Alumni"/>
        </div>
    </div>

    @yield('content')

    @include('layouts.script')
    @stack('script')

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