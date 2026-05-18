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

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />

        <!-- CSS -->
        <link href="{{ asset('backend/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet">
        <link href="{{ asset('backend/assets/css/style.bundle.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}">
        @stack('css')
    </head>

    <body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center">
    <script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>

        {{ $slot }}

        @stack('modal')

        <!-- JS Files -->
        <script src="{{ asset('backend/assets/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('backend/assets/js/scripts.bundle.js') }}"></script>

        @stack('scripts')
        @stack('js')
    </body>
</html>
