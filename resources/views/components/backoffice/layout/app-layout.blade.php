<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $title }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ asset('backend/assets/media/logos/logo-small.png') }}" rel="icon">
    <link href="{{ asset('backend/assets/media/logos/logo-small.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('backend/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/style.bundle.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/tutorials/timelines/timeline-2/assets/css/timeline-2.css">
    @stack('css')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        .bg-dashboard {
            background-image: url("{{ asset('backend/assets/media/auth/bg6.jpg') }}");
        }
        .ck .ck-powered-by{
            display: none;
        }
        .menu-subtitle {
            /* background: #f5f4f8; */
            text-align: center;
            color: #2884EF;
        }

    </style>
</head>

<body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

            <x-backoffice.navbar />

            <div class="app-wrapper flex-column flex-row-fluid bg-dashboard" id="kt_app_wrapper">
            {{ $slot }}
            </div>

            <div class="app-container container-xxl ">
                <div class="app-main flex-column flex-row-fluid">
                    <x-backoffice.footer />
                </div>
            </div>
        </div>
    </div>
    @stack('drawer')
    @stack('modal')

    <script src="{{ asset('backend/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    

    @stack('scripts')
    @stack('js')
</body>

</html>
