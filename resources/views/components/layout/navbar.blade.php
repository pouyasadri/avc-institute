{{-- Desktop Navigation Bar Component --}}
@props(['isRtl' => false])

<div class="eorik-nav-style eorik-nav-style-four fixed-top">
    <div class="navbar-area">
        {{-- Menu For Mobile Device --}}
        <nav class="mobile-nav" role="navigation" aria-label="{{ __('layout.mobile_navigation') }}">
            <a href="{{ route('index', ['locale' => app()->getLocale()]) }}" class="logo" aria-label="{{ __('layout.home') }}">
                <img style="width: 4rem;padding-bottom: 0.5rem;" src="{{asset("../assets/img/logo/new-logo.webp")}}"
                    alt="{{ __('layout.logo_alt') }}">
            </a>

            {{-- Hamburger Button --}}
            <button class="mobile-menu-toggle" aria-label="Toggle menu" aria-expanded="false"
                onclick="toggleMobileMenu()">
                <i class='bx bx-menu-alt-right'></i>
            </button>
        </nav>

        {{-- Menu For Desktop Device --}}
        <div class="main-nav">
            <nav class="navbar navbar-expand-md navbar-light" role="navigation"
                aria-label="{{ __('layout.main_navigation') }}">
                <div class="container">
                    <a class="navbar-brand" href="{{ route('index', ['locale' => app()->getLocale()]) }}" aria-label="{{ __('layout.home') }}">
                        <img style="width: 8rem" src="{{asset("../assets/img/logo/new-logo.png")}}"
                            alt="{{ __('layout.logo_alt') }}">
                    </a>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav m-auto">
                            <li class="nav-item">
                                <a href="{{ route('index', ['locale' => app()->getLocale()]) }}"
                                    class="nav-link {{ request()->is(app()->getLocale()) || request()->is(app()->getLocale() . '/') ? 'active' : '' }}"
                                    {{ (request()->is(app()->getLocale()) || request()->is(app()->getLocale() . '/')) ? 'aria-current="page"' : '' }}>
                                    {{ __('layout.nav.home') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('services.index', ['locale' => app()->getLocale()]) }}"
                                    class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}"
                                    {{ request()->routeIs('services.*') ? 'aria-current="page"' : '' }}>
                                    {{ __('layout.nav.services') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url(app()->getLocale() . '/blog') }}"
                                    class="nav-link {{ request()->is(app()->getLocale() . '/blog*') ? 'active' : '' }}"
                                    {{ request()->is(app()->getLocale() . '/blog*') ? 'aria-current="page"' : '' }}>
                                    {{ __('layout.nav.blogs') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url(app()->getLocale() . '/universities') }}"
                                    class="nav-link {{ request()->is(app()->getLocale() . '/universities*') ? 'active' : '' }}"
                                    {{ request()->is(app()->getLocale() . '/universities*') ? 'aria-current="page"' : '' }}>
                                    {{ __('layout.nav.universities') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url(app()->getLocale() . '/cities') }}"
                                    class="nav-link {{ request()->is(app()->getLocale() . '/cities*') ? 'active' : '' }}"
                                    {{ request()->is(app()->getLocale() . '/cities*') ? 'aria-current="page"' : '' }}>
                                    {{ __('layout.nav.cities') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url(app()->getLocale() . '/contactUs') }}"
                                    class="nav-link {{ request()->is(app()->getLocale() . '/contactUs*') ? 'active' : '' }}"
                                    {{ request()->is(app()->getLocale() . '/contactUs*') ? 'aria-current="page"' : '' }}>
                                    {{ __('layout.nav.contact') }}
                                </a>
                            </li>
                        </ul>

                        {{-- Desktop Language Switcher --}}
                        <x-layout.language-switcher :desktop="true" />
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
