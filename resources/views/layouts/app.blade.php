<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{asset("assets/css/bootstrap.rtl.min.css")}}">

    <link rel="preload" href="{{asset("assets/css/owl.theme.default.min.css")}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{asset("assets/css/owl.theme.default.min.css")}}"></noscript>

    <link rel="preload" href="{{asset("assets/css/owl.carousel.min.css")}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{asset("assets/css/owl.carousel.min.css")}}"></noscript>

    <link rel="preload" href="{{asset("assets/css/boxicons.min.css")}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{asset("assets/css/boxicons.min.css")}}"></noscript>

    <link rel="preload" href="{{asset("assets/css/flaticon.css")}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{asset("assets/css/flaticon.css")}}"></noscript>

    <link rel="preload" href="{{asset("assets/css/meanmenu.min.css")}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{asset("assets/css/meanmenu.min.css")}}"></noscript>

    <link rel="preload" href="{{asset("assets/css/animate.min.css")}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{asset("assets/css/animate.min.css")}}"></noscript>

    <link rel="preload" href="{{asset("assets/css/nice-select.min.css")}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{asset("assets/css/nice-select.min.css")}}"></noscript>

    <link rel="preload" href="{{asset("assets/css/odometer.min.css")}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{asset("assets/css/odometer.min.css")}}"></noscript>

    <link rel="preload" href="{{asset("assets/css/date-picker.min.css")}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{asset("assets/css/date-picker.min.css")}}"></noscript>

    <link rel="preload" href="{{asset("assets/css/magnific-popup.min.css")}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{asset("assets/css/magnific-popup.min.css")}}"></noscript>

    <link rel="stylesheet" href="{{asset("assets/css/beautiful-fonts.css")}}">

    <link rel="stylesheet" href="{{asset("assets/css/style-rtl.css")}}">

    <link rel="stylesheet" href="{{asset("assets/css/responsive.css")}}">

    <link rel="stylesheet" href="{{asset("assets/css/rtl.css")}}">

    <script src="https://www.amcharts.com/lib/3/ammap.js?3.17.0"></script>
    <script src="https://www.amcharts.com/lib/3/maps/js/franceLow.js"></script>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('ورود') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('ثبت نام') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('خروج') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
