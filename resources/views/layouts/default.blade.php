<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <title>Finance Web</title>

    <!-- jQuery (load first) -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>

    <!-- Preload critical CSS -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"></noscript>

    <!-- Font Awesome (preload) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"></noscript>

    <!-- Non-critical CSS -->
    <link href="{{ asset('assets/css/style.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">

    @yield('style')

    <!-- Preload critical JS -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" as="script">
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        @include('layouts.header')
        @include('partials.alert')
        @include('layouts.sidebar')
        <div class="page-wrapper">
            @yield('content')
            <footer class="footer text-center text-muted">
                All Rights Reserved by Finance Web. Designed and Developed by
                <a href="https://wrappixel.com">WrapPixel</a>.
            </footer>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

    <!-- Core Scripts -->
    <script src="{{ asset('assets/js/app-style-switcher.js') }}" defer></script>
    <script src="{{ asset('assets/js/feather.min.js') }}" defer></script>
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}" defer></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}" defer></script>
    <script src="{{ asset('assets/js/sidebarmenu.js') }}" defer></script>
    <script src="{{ asset('assets/js/custom.min.js') }}" defer></script>

    <!-- Dashboard Scripts -->
    <script src="{{ asset('assets/extra-libs/c3/d3.min.js') }}" defer></script>
    <script src="{{ asset('assets/extra-libs/c3/c3.min.js') }}" defer></script>
    <script src="{{ asset('assets/libs/chartist/dist/chartist.min.js') }}" defer></script>
    <script src="{{ asset('assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}" defer></script>
    <script src="{{ asset('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js') }}" defer></script>
    <script src="{{ asset('assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js') }}" defer></script>
    <script src="{{ asset('assets/libs/raphael/raphael.min.js') }}" defer></script>
    <script src="{{ asset('assets/libs/morris.js/morris.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/pages/morris/morris-data.js') }}" defer></script>
    <script src="{{ asset('assets/js/pages/dashboards/dashboard1.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/pages/dashboard/dashboard.js') }}" defer></script>

    <!-- Initialize scripts after DOM is ready -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize dashboard scripts
            if (typeof initDashboard === 'function') {
                initDashboard();
            }
            
            // Initialize chartist
            if (typeof initChartist === 'function') {
                initChartist();
            }
            
            // Initialize other components
            if (typeof initComponents === 'function') {
                initComponents();
            }
        });
    </script>

    @yield('scripts')

</body>

</html>
