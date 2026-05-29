<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ 'fa' === app()->getLocale() ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">

    {{-- SEO Meta Tags --}}
    @php
        $currentLocale = app()->getLocale();
        $seoService = app(\App\Services\SeoService::class);

        // Enforce the primary domain for canonical URLs to avoid duplicate content issues
        $appUrl = config('app.url');
        $canonicalUrl = $appUrl ? rtrim($appUrl, '/') . '/' . ltrim(request()->path(), '/') : url()->current();

        $currentRoute = request()->route();
        if ($currentRoute) {
            $routeName = $currentRoute->getName();
            if ($routeName) {
                try {
                    $params = $currentRoute->parameters();
                    $paramNames = method_exists($currentRoute, 'parameterNames') ? $currentRoute->parameterNames() : [];
                    if (!empty($paramNames)) {
                        $params = array_intersect_key($params, array_flip($paramNames));
                    }

                    // For the route helper, we should also ensure it uses the correct base URL
                    $canonicalUrl = route($routeName, $params);

                    if ($appUrl) {
                        $parsedAppUrl = parse_url($appUrl);
                        $parsedCanonical = parse_url($canonicalUrl);

                        $canonicalUrl = ($parsedAppUrl['scheme'] ?? 'https') . '://' .
                            ($parsedAppUrl['host'] ?? $parsedCanonical['host']) .
                            ($parsedCanonical['path'] ?? '');

                        if (!empty($parsedCanonical['query'])) {
                            // Generally we strip query params for canonicals as per existing logic,
                            // but if they are needed they would be here. Existing test implies stripping.
                        }
                    }
                } catch (\Throwable $e) {
                    // Fallback to primary domain + path
                }
            }
        }

        // Set default SEO for layout
        // Always enforce HTTPS on canonical URLs — safety net in case APP_URL scheme is wrong
        $canonicalUrl = preg_replace('/^http:\/\//', 'https://', $canonicalUrl);

        $seoService->setLocale($currentLocale)
            ->setCanonical($canonicalUrl)
            ->setTwitterCard('summary_large_image');

        // Ensure og:type is appropriate per-route (can still be overridden by page-specific SEO setup)
        if ($currentRoute) {
            $routeName = $currentRoute->getName();
            $actionName = $currentRoute->getActionName();

            if ($routeName === 'blog.show') {
                $seoService->setType('article');
            }

            // Only mark properties pages as products when the real feature is enabled
            // (coming-soon closures should remain "website").
            if (($routeName === 'properties.show' || $routeName === 'property.show') && $actionName !== 'Closure') {
                $seoService->setType('product');
            }
        }

        // Get meta, og, twitter data
        $metaTags = $seoService->getMeta();
        $ogTags = $seoService->getOpenGraph();
        $twitterTags = $seoService->getTwitter();

        // Build hreflang for current route
        $hreflangTags = [];
        $currentRoute = request()->route();
        if ($currentRoute) {
            $routeName = $currentRoute->getName();
            $routeParams = $currentRoute->parameters();

            foreach (config('seo.locales') as $locale => $localeData) {
                try {
                    $params = array_merge($routeParams, ['locale' => $locale]);
                    $hreflangTags[$locale] = route($routeName, $params);
                } catch (\Exception $e) {
                    // If route doesn't exist for this locale, skip
                }
            }
        }
    @endphp

    <title>@yield('title', $metaTags['title'] ?? config('seo.defaults.title'))</title>

    <!-- Required meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="@yield('keywords', $metaTags['keywords'] ?? config('seo.defaults.keywords'))">
    {{-- Description: prefer SeoService $metaTags, then @section override, then config default --}}
    <meta name="description" content="@hasSection('description')@yield('description')@else{{ $metaTags['description'] ?? config('seo.defaults.description') }}@endif">
    <meta name="author" content="{{ config('seo.defaults.author') }}">
    <meta name="robots" content="{{ $metaTags['robots'] ?? config('seo.robots.default') }}">

    {{-- Language Meta (Bing requires content-language with BCP-47 tag e.g. fa-IR, not just fa) --}}
    @php
        $bcp47Map = ['fa' => 'fa-IR', 'fr' => 'fr-FR', 'en' => 'en-US'];
        $contentLanguage = $bcp47Map[$currentLocale] ?? $currentLocale;
    @endphp
    <meta http-equiv="content-language" content="{{ $contentLanguage }}">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $canonicalUrl }}" />

    {{-- Hreflang Tags for Multilingual SEO --}}
    @if(!empty($hreflangTags))
        <x-seo.hreflang :hreflang="$hreflangTags" :defaultLocale="config('seo.default_locale')" />
    @endif

    {{-- Open Graph Tags --}}
    <x-seo.open-graph :og="$ogTags" />

    {{-- Twitter Card Tags --}}
    <x-seo.twitter-card :twitter="$twitterTags" />

    @php
        // Locales that should use RTL layout
        $rtlLocales = ['fa'];
        $isRtl = in_array(app()->getLocale(), $rtlLocales, true);

        $arrowIcon = $isRtl ? 'bx bx-left-arrow-alt' : 'bx bx-right-arrow-alt';
        $chevronsDir = $isRtl ? 'bx-chevrons-left' : 'bx-chevrons-right';
    @endphp

    {{-- Conditionally load RTL or LTR Bootstrap --}}
    @if($isRtl)
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.rtl.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{asset("assets/css/owl.theme.default.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/owl.carousel.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/boxicons.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/flaticon.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/meanmenu.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/animate.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/nice-select.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/odometer.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/date-picker.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/magnific-popup.min.css")}}">

    @if($isRtl)
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}?v={{ filemtime(public_path('assets/css/style-rtl.css')) }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style-ltr.css') }}?v={{ filemtime(public_path('assets/css/style-ltr.css')) }}">
    @endif
    <link class="responsive-style" rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}?v={{ filemtime(public_path('assets/css/responsive.css')) }}">

    {{-- Load rtl.css only for RTL locales (contains overrides for RTL layout) --}}
    @if($isRtl)
        <link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}?v={{ filemtime(public_path('assets/css/rtl.css')) }}">
    @endif

    {{-- Include navbar styles from external file or inline --}}
    @include('layouts.partials.navbar-styles')

    {{-- Preconnect for CDN resources BEFORE the actual resource requests --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Slick Slider CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    @stack('styles')


    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/img/favicon/site.webmanifest') }}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    {{-- Organization Structured Data (cached — static content never changes per-request) --}}
    @php
        $orgSchemaJson = \Illuminate\Support\Facades\Cache::rememberForever(
            'schema:organization',
            fn() => (new \App\Services\StructuredData\OrganizationSchema())->toScript()
        );
    @endphp
    {!! $orgSchemaJson !!}

    {{-- LocalBusiness Structured Data (cached) --}}
    @php
        $businessSchemaJson = \Illuminate\Support\Facades\Cache::rememberForever(
            'schema:local_business',
            fn() => (new \App\Services\StructuredData\LocalBusinessSchema())->toScript()
        );
    @endphp
    {!! $businessSchemaJson !!}

    {{-- WebSite Structured Data (cached) --}}
    @php
        $baseUrl = rtrim(config('app.url'), '/');
        $webSiteSchemaJson = \Illuminate\Support\Facades\Cache::rememberForever(
            'schema:website',
            fn() => (new \App\Services\StructuredData\WebSiteSchema(
                $baseUrl,
                config('seo.organization.name'),
                null,
                array_keys(config('seo.locales', ['en' => [], 'fr' => [], 'fa' => []]))
            ))->toScript()
        );
    @endphp
    {!! $webSiteSchemaJson !!}

    @stack('json')

    <script type="text/javascript">
        (function (c, l, a, r, i, t, y) {
            c[a] = c[a] || function () {
                (c[a].q = c[a].q || []).push(arguments)
            };
            t = l.createElement(r);
            t.async = 1;
            t.src = "https://www.clarity.ms/tag/" + i;
            y = l.getElementsByTagName(r)[0];
            y.parentNode.insertBefore(t, y);
        })(window, document, "clarity", "script", "kxqm47bwto");
    </script>
</head>

<body class="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <!-- Skip to Content Link for Accessibility -->
    <a href="#main-content" class="skip-to-content">{{ __('layout.skip_to_content') }}</a>

    <!-- Start Preloader Area -->
    <div class="preloader" aria-hidden="true">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- End Preloader Area -->

    <!-- Start Navbar Area -->
    <header role="banner">
        <x-layout.navbar :isRtl="$isRtl" />
        <x-layout.mobile-menu :isRtl="$isRtl" />
    </header>

    <!-- Main Content -->
    <main id="main-content" role="main">
        @yield('content')
    </main>

    <!-- Footer -->
    <x-layout.footer :isRtl="$isRtl" :chevronsDir="$chevronsDir" />

    <!-- Go Top Button -->
    <div class="go-top">
        <i class="bx bx-chevrons-up"></i>
        <i class="bx bx-chevrons-up"></i>
    </div>

    <!-- Scripts -->
    <script src="{{asset("../assets/js/jquery.min.js")}}"></script>
    <script src="{{asset("../assets/js/bootstrap.bundle.min.js")}}"></script>
    <script src="{{asset("../assets/js/meanmenu.min.js")}}"></script>
    <script src="{{asset("../assets/js/owl.carousel.min.js")}}"></script>
    <script src="{{asset("../assets/js/wow.min.js")}}"></script>
    <script src="{{asset("../assets/js/nice-select.min.js")}}"></script>
    <script src="{{asset("../assets/js/magnific-popup.min.js")}}"></script>
    <script src="{{asset("../assets/js/jquery.mixitup.min.js")}}"></script>
    <script src="{{asset("../assets/js/appear.min.js")}}"></script>
    <script src="{{asset("../assets/js/odometer.min.js")}}"></script>
    <script src="{{asset("../assets/js/bootstrap-datepicker.min.js")}}"></script>
    <script src="{{asset("../assets/js/ofi.min.js")}}"></script>
    <script src="{{asset("../assets/js/jarallax.min.js")}}"></script>
    <script src="{{asset("../assets/js/form-validator.min.js")}}"></script>
    <script src="{{asset("../assets/js/contact-form-script.js")}}"></script>
    <script src="{{asset("../assets/js/ajaxchimp.min.js")}}"></script>

    @if($isRtl)
        <script src="{{asset("../assets/js/custom-rtl.js")}}"></script>
    @else
        <script src="{{asset("../assets/js/custom-ltr.js")}}"></script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    @stack('scripts')

    <script>
        // Mobile Menu Toggle Logic - Updated for drawer
        (function () {
            window.toggleMobileMenu = function () {
                const backdrop = document.getElementById('mobileMenuBackdrop');
                const drawer = document.getElementById('mobileMenuDrawer');
                const body = document.body;
                const toggles = document.querySelectorAll('.mobile-menu-toggle');
                const toggleButton = toggles[0];

                if (backdrop && drawer) {
                    const isActive = backdrop.classList.contains('active');

                    // Toggle backdrop and drawer
                    backdrop.classList.toggle('active');
                    drawer.classList.toggle('active');

                    // Toggle active state on buttons for animation
                    toggles.forEach(btn => btn.classList.toggle('active'));

                    // Toggle body class to prevent scroll
                    body.classList.toggle('mobile-menu-open');

                    // Update aria-expanded for accessibility
                    if (toggleButton) {
                        toggleButton.setAttribute('aria-expanded', !isActive);
                    }

                    // Prevent body scroll when menu is open
                    if (!isActive) {
                        body.style.overflow = 'hidden';
                    } else {
                        body.style.overflow = '';
                    }
                }
            };

            // Close menu when clicking on a link
            document.addEventListener('DOMContentLoaded', function () {
                const mobileMenuLinks = document.querySelectorAll('.mobile-menu-nav a');
                mobileMenuLinks.forEach(link => {
                    link.addEventListener('click', function () {
                        setTimeout(() => {
                            const backdrop = document.getElementById('mobileMenuBackdrop');
                            if (backdrop && backdrop.classList.contains('active')) {
                                toggleMobileMenu();
                            }
                        }, 200);
                    });
                });

                // Close menu on ESC key
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        const backdrop = document.getElementById('mobileMenuBackdrop');
                        if (backdrop && backdrop.classList.contains('active')) {
                            toggleMobileMenu();
                        }
                    }
                });
            });

            // Sticky Navbar Logic
            const navbar = document.querySelector('.eorik-nav-style-four');
            const handleScroll = () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('is-sticky');
                } else {
                    navbar.classList.remove('is-sticky');
                }
            };

            // Throttling scroll event for better performance
            let ticking = false;
            window.addEventListener('scroll', function () {
                if (!ticking) {
                    window.requestAnimationFrame(function () {
                        handleScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            });

            // Run on load in case page is refreshed at scroll position
            document.addEventListener('DOMContentLoaded', handleScroll);
        })();
    </script>
</body>

</html>
