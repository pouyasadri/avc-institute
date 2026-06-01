{{-- Footer Component --}}
@props(['isRtl' => false, 'chevronsDir' => 'bx-chevrons-right'])

<footer class="footer-top-area pt-140 jarallax" data-jarallax='{"speed": 0.3}'>
    <div class="container">
        <div class="footer-middle-area pt-60">
            <div class="row">
                {{-- About Section --}}
                <div class="col-lg-3 col-md-6">
                    <div class="single-widget">
                        <a href="{{ route('index', ['locale' => app()->getLocale()]) }}">
                            <img src="{{asset("../assets/img/logo/new-logo.webp")}}" alt="{{ __('layout.logo_alt') }}">
                        </a>
                        <p>{{ __('layout.footer.about_short') }}</p>
                        <ul class="social-icon">
                            <li>
                                <a href="#" aria-label="{{ __('layout.social.facebook') }}">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" aria-label="{{ __('layout.social.twitter') }}">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/apply_vip_conseil/"
                                    aria-label="{{ __('layout.social.instagram') }}">
                                    <i class='bx bxl-instagram'></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" aria-label="{{ __('layout.social.linkedin') }}">
                                    <i class='bx bxl-linkedin'></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" aria-label="{{ __('layout.social.youtube') }}">
                                    <i class='bx bxl-youtube'></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="col-lg-3 col-md-6">
                    <div class="single-widget">
                        <h3>{{ __('layout.footer.quick_links_title') }}</h3>
                        <ul>
                            <li>
                                <a href="{{ route('index', ['locale' => app()->getLocale()]) }}">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.links.home') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services.index', ['locale' => app()->getLocale()]) }}">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.links.services') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ url(app()->getLocale() . '/universities') }}">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.links.universities') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ url(app()->getLocale() . '/cities') }}">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.links.cities') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('index', ['locale' => app()->getLocale()]) }}#about">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.links.about') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ url(app()->getLocale() . '/contactUs') }}">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.links.contact') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sitemap') }}">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.links.sitemap') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Services --}}
                <div class="col-lg-3 col-md-6">
                    <div class="single-widget">
                        <h3>{{ __('layout.footer.services_title') }}</h3>
                        <ul>
                            <li>
                                <a href="{{ route('services.show', ['locale' => app()->getLocale(), 'slug' => 'residence-permit']) }}">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.services.residence') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services.show', ['locale' => app()->getLocale(), 'slug' => 'educational-counseling']) }}">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.services.education') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services.show', ['locale' => app()->getLocale(), 'slug' => 'housing-assistance']) }}">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.services.accommodation') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services.show', ['locale' => app()->getLocale(), 'slug' => 'certified-translation']) }}">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.services.translation') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services.show', ['locale' => app()->getLocale(), 'slug' => 'administrative-advocacy']) }}">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.services.admin_support') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services.show', ['locale' => app()->getLocale(), 'slug' => 'arrival-support']) }}">
                                    <i class="right-icon bx {{ $chevronsDir }}"></i>
                                    {{ __('layout.footer.services.arrival_support') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Contact Info --}}
                <div class="col-lg-3 col-md-6">
                    <div class="single-widget">
                        <h3>{{ __('layout.footer.contact_title') }}</h3>
                        <ul class="information">
                            <li class="address">
                                <i class="flaticon-call"></i>
                                <span>{{ __('layout.contact.whatsapp_label') }}</span>
                                <a href="tel:+33780953333">
                                    0033-780-95-33-33
                                </a>
                            </li>
                            <li class="address">
                                <i class="flaticon-call"></i>
                                <span>{{ __('layout.contact.iran_phone_label') }}</span>
                                <a href="tel:+989120087194">
                                    0098-912-008-7194
                                </a>
                            </li>
                            <li class="address">
                                <i class="flaticon-envelope"></i>
                                <span>{{ __('layout.contact.email_label') }}</span>
                                <a href="mailto:info@applyvipcoseil.com">
                                    info@applyvipconseil.com
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="footer-bottom-area">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="designed">
                        <p>{{ __('layout.footer.designed_by') }} <a href="https://www.pouyasadri.com"
                                target="_blank" rel="noopener noreferrer">PouyaSadri</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
