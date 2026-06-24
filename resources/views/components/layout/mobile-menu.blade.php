{{-- Mobile Menu Drawer Component --}}
@props(['isRtl' => false])

{{-- Mobile Menu Backdrop --}}
<div class="mobile-menu-backdrop" id="mobileMenuBackdrop" onclick="toggleMobileMenu()"></div>

{{-- Mobile Menu Drawer --}}
<div class="mobile-menu-drawer" id="mobileMenuDrawer">
    {{-- Drawer Header --}}
    <div class="mobile-menu-header">
        <div class="mobile-menu-logo">
            <a href="{{ route('index', ['locale' => app()->getLocale()]) }}">
                <img src="{{asset("assets/img/logo/new-logo.webp")}}" alt="{{ __('layout.logo_alt') }}">
            </a>
        </div>
        <button class="mobile-menu-close" aria-label="Close menu" onclick="toggleMobileMenu()">
            <i class='bx bx-x'></i>
        </button>
    </div>

    {{-- Drawer Body --}}
    <div class="mobile-menu-body">
        <nav class="mobile-menu-nav">
            <ul>
                {{-- Consult CTA — Prominently at Top --}}
                <li class="mobile-consult-cta-item">
                    <a href="{{ url(app()->getLocale() . '/consult') }}"
                       class="mobile-consult-cta-link"
                       id="mobile-consult-btn"
                    >
                        <i class='bx bx-calendar-check'></i>
                        {{ __('layout.nav.consult') }}
                    </a>
                </li>

                <li>
                    <a href="{{ route('index', ['locale' => app()->getLocale()]) }}"
                        class="{{ request()->is(app()->getLocale()) || request()->is(app()->getLocale() . '/') ? 'active' : '' }}">
                        {{ __('layout.nav.home') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('services.index', ['locale' => app()->getLocale()]) }}"
                        class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
                        {{ __('layout.nav.services') }}
                    </a>
                </li>
                <li>
                    <a href="{{ url(app()->getLocale() . '/blog') }}"
                        class="{{ request()->is(app()->getLocale() . '/blog*') ? 'active' : '' }}">
                        {{ __('layout.nav.blogs') }}
                    </a>
                </li>
                <li>
                    <a href="{{ url(app()->getLocale() . '/universities') }}"
                        class="{{ request()->is(app()->getLocale() . '/universities*') ? 'active' : '' }}">
                        {{ __('layout.nav.universities') }}
                    </a>
                </li>
                <li>
                    <a href="{{ url(app()->getLocale() . '/cities') }}"
                        class="{{ request()->is(app()->getLocale() . '/cities*') ? 'active' : '' }}">
                        {{ __('layout.nav.cities') }}
                    </a>
                </li>
                <li>
                    <a href="{{ url(app()->getLocale() . '/contactUs') }}"
                        class="{{ request()->is(app()->getLocale() . '/contactUs*') ? 'active' : '' }}">
                        {{ __('layout.nav.contact') }}
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    {{-- Drawer Footer --}}
    <div class="mobile-menu-footer">
        {{-- Language Switcher --}}
        <x-layout.language-switcher :desktop="false" />

        {{-- Social Links --}}
        <div class="mobile-menu-social">
            <a href="https://www.instagram.com/apply_vip_conseil/" aria-label="Instagram"
                target="_blank" rel="noopener noreferrer"><i class='bx bxl-instagram'></i></a>
            <a href="https://www.facebook.com/applyvipconseil" aria-label="Facebook"
                target="_blank" rel="noopener noreferrer"><i class='bx bxl-facebook'></i></a>
            <a href="https://www.linkedin.com/company/applyvipconseil" aria-label="LinkedIn"
                target="_blank" rel="noopener noreferrer"><i class='bx bxl-linkedin'></i></a>
        </div>
    </div>
</div>
