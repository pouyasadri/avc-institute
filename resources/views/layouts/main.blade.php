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
            $routeName   = $currentRoute->getName();
            $routeParams = $currentRoute->parameters();

            foreach (config('seo.locales') as $locale => $localeData) {
                try {
                    // Blog show pages use per-locale slugs — resolve the real slug for each language
                    // so hreflang points to /fa/blog/slug-in-persian, /en/blog/slug-in-english, etc.
                    // (same approach already used in SitemapController for sitemap hreflang alternates)
                    if ($routeName === 'blog.show' && isset($blog)) {
                        $altTranslation = $blog->getTranslation($locale);
                        if ($altTranslation && $altTranslation->slug) {
                            $hreflangTags[$locale] = route($routeName, [
                                'locale' => $locale,
                                'blog'   => $altTranslation->slug,
                            ]);
                        }
                    } else {
                        $params = array_merge($routeParams, ['locale' => $locale]);
                        $hreflangTags[$locale] = route($routeName, $params);
                    }
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

    {{-- ═══════════════════════════════════════════════════════════════════
         CRITICAL CSS — inlined to eliminate render-blocking for first paint.
         Must come BEFORE Vite bundles and vendor CSS links.
    ═══════════════════════════════════════════════════════════════════ --}}
    @include('layouts.partials.critical-css')

    {{-- ═══════════════════════════════════════════════════════════════════
         VITE-BUNDLED CSS
         Includes: Bootstrap (LTR or vendor-CSS-only for RTL), Owl Carousel,
         Animate.css, Magnific Popup, Odometer, Slick Carousel
         All hashed and gzip-compressed by Vite.
    ═══════════════════════════════════════════════════════════════════ --}}
    @if($isRtl)
        @vite(['resources/sass/app-rtl.scss', 'resources/js/theme-rtl.js'])
    @else
        @vite(['resources/sass/app-ltr.scss', 'resources/js/theme-ltr.js'])
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════
         STATIC VENDOR CSS (no npm package — kept as raw assets)
         All loaded DEFERRED (non-render-blocking) — see vendor-css.blade.php.
    ═══════════════════════════════════════════════════════════════════ --}}
    @include('layouts.partials.vendor-css', ['isRtl' => $isRtl])

    {{-- ═══════════════════════════════════════════════════════════════════
         STATIC VENDOR JS (no npm package — remain as raw assets)
         meanmenu, nice-select, ofi, ajaxchimp, appear, mixitup, form-validator,
         contact-form-script, createScrollLinks — kept until npm equivalents found.
    ═══════════════════════════════════════════════════════════════════ --}}

    {{-- Inline navbar styles (kept as-is) --}}
    @include('layouts.partials.navbar-styles')

    {{-- ═══════════════════════════════════════════════════════════════════
         PRECONNECT HINTS
         Only connect to origins the page actually uses. Max 4 origins.
         Removed: cdn.jsdelivr.net (nothing loads from there).
    ═══════════════════════════════════════════════════════════════════ --}}
    <link rel="dns-prefetch" href="https://www.clarity.ms">
    @if(config('app.asset_url'))
        <link rel="preconnect" href="{{ config('app.asset_url') }}" crossorigin>
    @endif
    @php $routeName = optional(request()->route())->getName(); @endphp
    @if($routeName === 'index')
        {{-- amCharts is only used on the homepage map — 310 ms LCP savings --}}
        <link rel="preconnect" href="https://www.amcharts.com">
        {{-- Preload the slider background (first paint, but not the actual LCP element) --}}
        <link rel="preload" as="image" fetchpriority="high"
              href="{{ asset('assets/img/cities/Paris/paris-slider1.webp') }}">
        {{-- Preload the real LCP element: the "about" section image (confirmed via
             Lighthouse — it paints before the CSS-driven slider background because
             it's a plain <img> the preload scanner can find immediately). Without
             this, the browser doesn't discover it until it parses ~100 lines into
             the body, adding ~1.8s of avoidable "resource load delay" to LCP. --}}
        <link rel="preload" as="image" fetchpriority="high"
              href="{{ asset('assets/img/cities/Paris/paris4.webp') }}">
    @endif

    {{-- Preload Critical Font Files (reduce CLS and fix font-display penalty) --}}
    <link rel="preload" href="{{ asset('assets/fonts/boxicons.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('assets/fonts/Flaticon.woff2') }}" as="font" type="font/woff2" crossorigin>
    @if($isRtl)
        <link rel="preload" href="{{ asset('assets/fonts/webfonts/Vazirmatn-RD-FD-Regular.woff2') }}" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="{{ asset('assets/fonts/webfonts/Vazirmatn-RD-FD-Bold.woff2') }}" as="font" type="font/woff2" crossorigin>
    @endif

    @stack('styles')

    {{-- ═══════════════════════════════════════════════════════════════════
         CONVERSION CTA STYLES — Navbar button, sticky mobile bar, WhatsApp float
    ═══════════════════════════════════════════════════════════════════ --}}
    <style>
        /* Navbar Consult CTA Button */
        .navbar-consult-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: #fff !important;
            padding: 8px 18px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.875rem;
            text-decoration: none;
            margin-{{ $isRtl ? 'right' : 'left' }}: 14px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            white-space: nowrap;
            box-shadow: 0 4px 14px rgba(40,167,69,0.35);
        }
        .navbar-consult-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40,167,69,0.5);
            color: #fff !important;
            text-decoration: none;
        }

        /* Mobile Consult CTA Item */
        .mobile-consult-cta-item {
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 6px;
        }
        .mobile-consult-cta-link {
            display: flex !important;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #28a745, #20c997) !important;
            color: #fff !important;
            padding: 12px 20px !important;
            border-radius: 10px !important;
            font-weight: 700 !important;
            font-size: 1rem !important;
            text-decoration: none !important;
            justify-content: center;
        }
        .mobile-consult-cta-link i {
            font-size: 1.2rem;
        }

        /* Sticky Mobile CTA Bar */
        .sticky-mobile-cta {
            display: none;
            position: fixed;
            bottom: 0;
            {{ $isRtl ? 'right' : 'left' }}: 0;
            width: 100%;
            z-index: 9998;
            padding: 10px 16px;
            background: #1a1a2e;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.3);
            border-top: 2px solid #28a745;
        }
        .sticky-mobile-cta a {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: #fff !important;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            animation: pulse-cta 2.5s infinite;
        }
        @keyframes pulse-cta {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        @media (max-width: 991px) {
            .sticky-mobile-cta { display: block; }
            /* Add bottom padding to body so content isn't hidden behind the bar */
            body { padding-bottom: 72px; }
        }

        /* Floating WhatsApp Button */
        .whatsapp-float {
            position: fixed;
            {{ $isRtl ? 'left' : 'right' }}: 20px;
            bottom: 90px;
            z-index: 9997;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #25d366;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(37,211,102,0.5);
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .whatsapp-float:hover {
            transform: scale(1.12);
            box-shadow: 0 6px 24px rgba(37,211,102,0.7);
        }
        .whatsapp-float svg {
            width: 30px;
            height: 30px;
            fill: #fff;
        }
        @media (max-width: 991px) {
            .whatsapp-float { bottom: 90px; }
        }
    </style>


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
        // Analytics is deferred until the page has finished loading (or after a
        // short idle fallback) so it never competes with the critical rendering
        // path for bandwidth / main-thread time during FCP/LCP.
        (function (c, l, a, r, i, t, y) {
            function loadClarity() {
                c[a] = c[a] || function () {
                    (c[a].q = c[a].q || []).push(arguments)
                };
                t = l.createElement(r);
                t.async = 1;
                t.src = "https://www.clarity.ms/tag/" + i;
                y = l.getElementsByTagName(r)[0];
                y.parentNode.insertBefore(t, y);
            }

            if (document.readyState === 'complete') {
                loadClarity();
            } else {
                window.addEventListener('load', function () {
                    ('requestIdleCallback' in window)
                        ? requestIdleCallback(loadClarity, { timeout: 4000 })
                        : setTimeout(loadClarity, 1000);
                });
            }
        })(window, document, "clarity", "script", "kxqm47bwto");
    </script>
</head>

<body class="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <!-- Skip to Content Link for Accessibility -->
    <a href="#main-content" class="skip-to-content">{{ __('layout.skip_to_content') }}</a>



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

    {{-- Sticky Mobile CTA Bar --}}
    <div class="sticky-mobile-cta" id="sticky-mobile-cta" role="complementary" aria-label="{{ __('layout.nav.consult') }}">
        <a href="{{ url(app()->getLocale() . '/consult') }}" id="sticky-cta-btn">
            <i class='bx bx-calendar-check' style="font-size:1.3rem"></i>
            {{ __('layout.nav.consult') }}
        </a>
    </div>

    {{-- Floating WhatsApp Button --}}
    <a href="https://wa.me/33768688326?text={{ urlencode(__('layout.whatsapp_default_message', [], 'fa')) }}"
       class="whatsapp-float"
       id="whatsapp-float-btn"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="WhatsApp"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" aria-hidden="true">
            <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
        </svg>
    </a>

    {{-- Static vendor JS files have been moved into resources/js/vendor and are now bundled by Vite in theme-ltr.js / theme-rtl.js --}}

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
