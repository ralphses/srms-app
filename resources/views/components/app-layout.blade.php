<!doctype html>

<html lang="en" class="remember-theme">
<head>
    <meta charset="utf-8">
    <!--
      Available classes for <html> element:

      'dark'                  Enable dark mode - Default dark mode preference can be set in app.js file (always saved and retrieved in localStorage afterwards):
                                window.Codebase = new App({ darkMode: "system" }); // "on" or "off" or "system"
      'dark-custom-defined'   Dark mode is always set based on the preference in app.js file (no localStorage is used)
      'remember-theme'        Remembers active color theme between pages using localStorage when set through
                                - Theme helper buttons [data-toggle="theme"]
    -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>Codebase - Bootstrap 5 Admin Template &amp; UI Framework</title>

    <meta name="description" content="Codebase - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="Codebase - Bootstrap 5 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="Codebase">
    <meta property="og:description"
          content="Codebase - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('assets/media/favicons/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"
          href="{{ asset('assets/media/favicons/apple-touch-icon-180x180.png') }}">
    <!-- END Icons -->

    <!-- Stylesheets -->

    <!-- Codebase framework -->
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/codebase.min.css') }}">

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="{{ asset('assets/css/themes/flat.min.css') }}"> -->
    <link rel="stylesheet" id="css-theme" href="{{ asset('assets/css/themes/corporate.min.css') }}">
    <!-- END Stylesheets -->

    <!-- Load and set color theme + dark mode preference (blocking script to prevent flashing) -->
    <script src="{{ asset('assets/js/setTheme.js') }}"></script>

</head>

<body>

<div id="page-container"
     class="sidebar-dark side-scroll page-header-fixed page-header-glass page-header-dark main-content-boxed">


    <!-- Header -->
    <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
            <!-- Left Section -->
            <div class="d-flex align-items-center space-x-3">
                <!-- Logo -->
                <a class="link-fx fw-semibold" href="{{ route("home") }}">
                    <span class="text-dual">SRMS</span><span class="text-primary"> APP</span>
                </a>
                <!-- END Logo -->
            </div>

           @if(auth()->check())
                <div class="space-x-1 d-flex align-items-center">
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-alt-danger">
                            <i class="fa fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </form>
                </div>
           @endif

            <!-- END Right Section -->
        </div>
        <!-- END Header Content -->

        <!-- Header Search -->
{{--        <div id="page-header-search" class="overlay-header bg-body-extra-light">--}}
{{--            <div class="content-header">--}}
{{--                <form class="w-100" action="be_pages_generic_search.html" method="POST">--}}
{{--                    <div class="input-group">--}}
{{--                        <!-- Close Search Section -->--}}
{{--                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->--}}
{{--                        <button type="button" class="btn btn-secondary" data-toggle="layout"--}}
{{--                                data-action="header_search_off">--}}
{{--                            <i class="fa fa-fw fa-times"></i>--}}
{{--                        </button>--}}
{{--                        <!-- END Close Search Section -->--}}
{{--                        <input type="text" class="form-control" placeholder="Search or hit ESC.."--}}
{{--                               id="page-header-search-input" name="page-header-search-input">--}}
{{--                        <button type="submit" class="btn btn-secondary">--}}
{{--                            <i class="fa fa-fw fa-search"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
        <!-- END Header Search -->

        <!-- Header Loader -->
        <div id="page-header-loader" class="overlay-header bg-primary">
            <div class="content-header">
                <div class="w-100 text-center">
                    <i class="far fa-sun fa-spin text-white"></i>
                </div>
            </div>
        </div>
        <!-- END Header Loader -->
    </header>
    <!-- END Header -->

    {{ $slot }}

    <!-- Footer -->
    <footer id="page-footer" class="bg-body-extra-light">
        <div class="content py-3">
            <div class="row fs-sm">
            </div>
        </div>
    </footer>
    <!-- END Footer -->
</div>
<!-- END Page Container -->

<!--
    Codebase JS

    Core libraries and functionality
    webpack is putting everything together at assets/_js/main/app.js
-->
<script src="{{ asset('assets/js/codebase.app.min.js') }}"></script>
<!-- Page JS Code -->
<script src="{{ asset('assets/js/pages/db_corporate.min.js') }}"></script>
</body>
</html>
