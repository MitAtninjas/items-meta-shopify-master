<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">

    <!-- Fonts and Styles -->
    @stack('css_before')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{ asset('css/dashmix.css') }}">

    <!-- SweetAlert CSS-->
    <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">
    <!-- ! SweetAlert CSS-->

    <!-- You can include a specific file from public/css/themes/ folder to alter the default color theme of the template. eg: -->
    @stack('css_after')

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};
    </script>
</head>

<body>
    <!-- Page Container -->
    <div id="page-container"
        class="enable-cookies sidebar-o enable-page-overlay side-scroll page-header-fixed side-trans-enabled sidebar-dark page-header-dark">
        <!-- Sidebar -->
        @include('layouts.partials.backend_sidebar')
        <!-- END Sidebar -->

        <!-- Header -->
        @include('layouts.partials.backend_header')
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container">
            <!-- Hero -->
            @include('layouts.partials.backend_hero')
            <!-- END Hero -->

            <div class="content">
                @yield('content')
            </div>
        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        @include('layouts.partials.backend_footer')
        <!-- END Footer -->
    </div>
    <!-- END Page Container -->

    <!-- Dashmix Core JS -->
    <script src="{{ asset('js/dashmix.app.js') }}"></script>

    <!-- SweetAlert JS-->
    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script src="{{ asset('js/custom.js') }}"></script>

    <!-- Laravel Scaffolding JS -->
    <!-- <script src="{{ mix('/js/laravel.app.js') }}"></script> -->

    @stack('js_after')
</body>

</html>