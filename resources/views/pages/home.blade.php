@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $seoService = app(\App\Services\SeoService::class);
    $seoService->forHomepage($currentLocale);
@endphp

@section('title', __('index.meta.title'))
@section("keywords", __('index.meta.keywords'))
@section("description", __('index.meta.description'))
@section('content')
    <div>

        @php
            // Determine RTL/LTR and pick appropriate flaticon arrow class
            $rtlLocales = ['fa'];
            $isRtl = in_array(app()->getLocale(), $rtlLocales, true);
            // For RTL languages the forward/CTA arrow should point left (flaticon-left-arrow)
            // For LTR languages the forward/CTA arrow should point right (flaticon-right-arrow)
            $arrowIcon = $isRtl ? 'flaticon-left-arrow' : 'flaticon-right-arrow';
        @endphp

        <!-- Start Ecorik Slider Area -->
        <section class="eorik-slider-area eorik-slider-area-five">
            <div class="eorik-slider-five owl-carousel owl-theme">
                <div class="eorik-slider-item slider-item-bg-13">
                    <div class="d-table">
                        <div class="d-table-cell">
                            <div class="container">
                                <div class="eorik-slider-text overflow-hidden one">
                                    <h1>{{ __('index.slider.slide1.title') }}</h1>
                                    <span>{{ __('index.slider.slide1.subtitle') }}</span>
                                    <div class="slider-btn">
                                        <a class="default-btn" href="{{url(app()->getLocale() . "/consult")}}">
                                            {{ __('index.slider.slide1.button') }}
                                            <i class="{{ $arrowIcon }}"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="eorik-slider-item slider-item-bg-14">
                    <div class="d-table">
                        <div class="d-table-cell">
                            <div class="container">
                                <div class="eorik-slider-text overflow-hidden two">
                                    <h2>{{ __('index.slider.slide2.title') }}</h2>
                                    <span>{{ __('index.slider.slide2.subtitle') }}</span>
                                    <div class="slider-btn">
                                        <a class="default-btn" href="{{url(app()->getLocale() . "/consult")}}">
                                            {{ __('index.slider.slide2.button') }}
                                            <i class="{{ $arrowIcon }}"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="eorik-slider-item slider-item-bg-15">
                    <div class="d-table">
                        <div class="d-table-cell">
                            <div class="container">
                                <div class="eorik-slider-text overflow-hidden three">
                                    <h2>{{ __('index.slider.slide3.title') }}</h2>
                                    <span>{{ __('index.slider.slide3.subtitle') }}</span>
                                    <div class="slider-btn">
                                        <a class="default-btn" href="{{url(app()->getLocale() . "/consult")}}">
                                            {{ __('index.slider.slide3.button') }}
                                            <i class="{{ $arrowIcon }}"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Ecorik Slider Area -->

        <!-- Start Explore Area -->
        <section id="about" class="explore-area pt-100 pb-100">
            <div class="container">
                <div class="section-title">
                    <span>{{ __('index.about.subtitle') }}</span>
                    <h2>{{ __('index.about.title') }}</h2>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="explore-img">
                            <img src="{{asset("assets/img/cities/Paris/paris4.webp")}}"
                                alt="{{ __('index.about.image_alt') }}"
                                width="679" height="424"
                                loading="lazy">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="explore-content ml-30">
                            <h2>{{ __('index.about.heading') }}</h2>
                            <p>{{ __('index.about.p1') }}</p>

                            <p>{{ __('index.about.p2') }}</p>

                            <p>{{ __('index.about.p3') }}</p>

                            <p>{{ __('index.about.p4') }}</p>

                            <a href="{{ url(app()->getLocale() . '/consult') }}" class="default-btn"
                               id="about-consult-btn">
                                {{ __('index.about.button') }}
                                <i class="{{ $arrowIcon }}"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Explore Area -->

        <x-sections.features :subtitle="__('index.facilities.subtitle')" :title="__('index.facilities.title')"
            :items="collect(__('index.facilities.items'))->map(function ($item, $index) {
            $icons = ['globe', 'book', 'home', 'chat'];
            $item['icon'] = $icons[$index] ?? 'chat';
            return $item;
        })->toArray()" />

        <!-- Start Incredible Area -->
        <section class="incredible-area ptb-140 jarallax" data-jarallax='{"speed": 0.3}'>
            <div class="container">
                <div class="incredible-content">
                    <h2><span>{{ __('index.video.title_start') }}</span> {{ __('index.video.title_end') }}</h2>

                    <p>{{ __('index.video.p1') }}</p>

                    <p>{{ __('index.video.p2') }}</p>

                    <a href="{{url(app()->getLocale() . "/consult")}}" class="default-btn">
                        {{ __('index.video.button') }}
                        <i class="{{ $arrowIcon }}"></i>
                    </a>

                </div>
            </div>
            <div class="white-shape">
                <img src="{{asset("assets/img/shape/white-shape-top.png")}}" alt="{{ __('index.video.image_alt') }}">
                <img src="{{asset("../assets/img/shape/white-shape-bottom.png")}}" alt="{{ __('index.video.image_alt') }}">
            </div>
        </section>
        <!-- End Incredible Area -->

        <x-sections.services :subtitle="__('index.services.subtitle')" :title="__('index.services.title')"
            :items="__('index.services.items')" />
        <!-- Start City View Area -->
        <section class="city-view-area ptb-100">
            <div class="container">
                <div class="row">
                    @php
                        $mapCityNamesJson = json_encode([
                            __('cities.map_paris'),
                            __('cities.map_strasbourg'),
                            __('cities.map_lyon'),
                            __('cities.map_nice'),
                            __('cities.map_toulouse'),
                        ], JSON_UNESCAPED_UNICODE);
                    @endphp
                    <div aria-hidden="true" role="presentation" class="col-lg-5 col-md-12" style="height: 40rem" id="chartdiv"
                        data-city-names="{{ $mapCityNamesJson }}"></div>
                    <div class="city-wrap col-lg-5 col-md-12">
                        <div class="single-city-item owl-carousel owl-theme">
                            @foreach(__('index.cities.items') as $index => $city)
                                <div class="city-view-single-item">
                                    <div class="city-content">
                                        <span>{{ __('index.cities.subtitle') }}</span>
                                        <h3>{{ $city['title'] }}</h3>
                                        <p>{{ $city['description'] }}</p>

                                        @php
                                            $cityRouteNames = ['paris', 'strasbourg', 'lyon', 'nice', 'toulouse'];
                                            $citySlug = $cityRouteNames[$index] ?? 'paris';
                                        @endphp
                                        <a href="{{ route('cities.' . $citySlug, ['locale' => app()->getLocale()]) }}" class="default-btn">
                                            {{ $city['button'] }}
                                            <i class="{{ $arrowIcon }}"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End City View Area -->
        {{--
        ================================================================================
        PROPERTIES FEATURE DISABLED - COMING SOON
        ================================================================================
        This section displays property listings on the homepage.
        To re-enable:
        1. Uncomment this entire section (lines 344-407)
        2. Uncomment property data fetching in IndexController.php (lines 26-27)
        3. Clear view cache: php artisan view:clear
        4. See PROPERTIES_DISABLED.md for full restoration guide
        ================================================================================

        <!-- Start Properties Area -->
        <section class="restaurants-area ptb-100">
            <div class="container-fluid p-0">
                <div class="section-title">
                    <span>{{ __('index.properties.subtitle') }}</span>
                    <h2>{{ __('index.properties.title') }}</h2>
                </div>

                @if($properties->count() > 0)
                <div class="restaurants-wrap owl-carousel owl-theme" data-items="4" data-loop="false" data-margin="0"
                    data-autoplay="true">
                    @foreach($properties as $property)
                    @php
                    $translation = $property->getTranslation(app()->getLocale());
                    @endphp
                    <div class="single-restaurants" @if($property->main_image)
                        style="background-image: url('{{ asset('storage/' . $property->main_image) }}'); background-size:
                        cover; background-position: center;"
                        @else
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"
                        @endif
                        >
                        <i class="restaurants-icon flaticon-expand"></i>
                        <span>{{ $translation->name ?? __('No translation') }}</span>
                        <p>
                            {{ $property->city }}, {{ $property->country }}
                            | €{{ number_format($property->price, 0) }}
                        </p>
                        <div class="property-features mb-2">
                            <small class="text-white">
                                <i class="bx bx-bed"></i> {{ $property->rooms }} {{ __('index.properties.rooms') }}
                                &nbsp;|&nbsp;
                                <i class="bx bx-car"></i> {{ $property->garages }} {{ __('index.properties.garages') }}
                            </small>
                        </div>
                        @if($property->status !== 'available')
                        <span class="badge badge-warning mb-2">
                            {{ ucfirst($property->status) }}
                        </span>
                        @endif
                        <a href="{{ route('properties.show', ['locale' => app()->getLocale(), 'id' => $property->id]) }}"
                            class="default-btn">
                            {{ __('index.properties.button') }}
                            <i class="{{ $arrowIcon }}"></i>
                        </a>
                    </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('properties.index', ['locale' => app()->getLocale()]) }}" class="default-btn">
                        {{ __('index.properties.view_all') }}
                        <i class="{{ $arrowIcon }}"></i>
                    </a>
                </div>
                @else
                <div class="alert alert-info text-center mx-auto" style="max-width: 500px;" role="alert">
                    <i class="bx bx-info-circle"></i>
                    {{ __('index.properties.no_properties') }}
                </div>
                @endif
            </div>
        </section>
        <!-- End Properties Area -->

        ================================================================================
        END PROPERTIES FEATURE DISABLED
        ================================================================================
        --}}

        <!-- Start News Area -->
        <section class="news-area pt-100 pb-100">
            <div class="container">
                <div class="section-title">
                    <span>{{ __('index.blogs.subtitle') }}</span>
                    <h2>{{ __('index.blogs.title') }}</h2>
                </div>
                <div class="row">
                    @forelse($blogs as $blog)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <x-blog-card :blog="$blog" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center" role="alert">
                                <i class="bx bx-info-circle"></i>
                                {{ __('index.blogs.no_blogs') }}
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($blogs->count() > 0)
                    <div class="text-center mt-4">
                        <a href="{{ route('blog.index', ['locale' => app()->getLocale()]) }}" class="default-btn">
                            {{ __('index.blogs.view_all') }}
                            <i class="{{ $flaticonArrow }}"></i>
                        </a>
                    </div>
                @endif
            </div>
        </section>
        <!-- End News Area -->

        <x-sections.faq :title="__('faq.title')" :subtitle="__('faq.subtitle')" :items="__('faq.items')" />

    </div>
@endsection
@push("json")
    {{-- WebPage Schema --}}
    @php
        $homeTitle = __('index.meta.title');
        $homeDescription = __('index.meta.description');
        $homeImage = config('seo.defaults.image');
        $webPageSchema = new \App\Services\StructuredData\WebPageSchema(
            url()->current(),
            $homeTitle,
            $homeDescription,
            $currentLocale,
            null,
            $homeImage
        );
    @endphp
    <x-seo.structured-data :schema="$webPageSchema" />
@endpush
@push("scripts")
    {{-- amCharts scripts are already at end-of-body (non-render-blocking).
         Do NOT use defer here — the inline AmCharts.makeChart() block below
         would execute before the deferred library loads. --}}
    <script src="https://www.amcharts.com/lib/3/ammap.js"></script>
    <script src="https://www.amcharts.com/lib/3/maps/js/franceLow.js"></script>
    <script>
        // Dynamic Map Configuration with Locale-based City Names
        var targetSVG = "M9,0C4.029,0,0,4.029,0,9s4.029,9,9,9s9-4.029,9-9S13.971,0,9,0z M9,15.93 c-3.83,0-6.93-3.1-6.93-6.93S5.17,2.07,9,2.07s6.93,3.1,6.93,6.93S12.83,15.93,9,15.93 M12.5,9c0,1.933-1.567,3.5-3.5,3.5S5.5,10.933,5.5,9S7.067,5.5,9,5.5 S12.5,7.067,12.5,9z";
        var planeSVG = "m2,106h28l24,30h72l-44,-133h35l80,132h98c21,0 21,34 0,34l-98,0 -80,134h-35l43,-133h-71l-24,30h-28l15,-47";

        // Get city names from translations
        var chartEl = document.getElementById("chartdiv");
        var cityNames = [];
        if (chartEl && chartEl.dataset && chartEl.dataset.cityNames) {
            cityNames = JSON.parse(chartEl.dataset.cityNames);
        }

        AmCharts.makeChart("chartdiv", {
            type: "map",
            fontSize: 20,
            balloon: { horizontalPadding: 20, verticalPadding: 15 },
            creditsPosition: "top-right",
            dataProvider: {
                map: "franceLow",
                zoomLevel: 1,
                lines: [{
                    id: "line1",
                    arc: -0.85,
                    alpha: 0.3,
                    latitudes: [48.8567, 48.584614, 45.7578137, 43.7009358, 43.6044622],
                    longitudes: [2.3510, 7.7507127, 4.8320114, 7.2683912, 1.4442469]
                }, {
                    id: "line2",
                    alpha: 0,
                    color: "#000000",
                    latitudes: [48.8567, 48.584614, 45.7578137, 43.7009358, 43.6044622],
                    longitudes: [2.3510, 7.7507127, 4.8320114, 7.2683912, 1.4442469]
                }],
                images: [{
                    svgPath: targetSVG,
                    title: cityNames[0],
                    latitude: 48.8567,
                    longitude: 2.3510
                }, {
                    svgPath: targetSVG,
                    title: cityNames[1],
                    latitude: 48.584614,
                    longitude: 7.7507127
                }, {
                    svgPath: targetSVG,
                    title: cityNames[2],
                    latitude: 45.7578137,
                    longitude: 4.8320114
                }, {
                    svgPath: targetSVG,
                    title: cityNames[3],
                    latitude: 43.7009358,
                    longitude: 7.2683912
                }, {
                    svgPath: targetSVG,
                    title: cityNames[4],
                    latitude: 43.6044622,
                    longitude: 1.4442469
                }, {
                    svgPath: planeSVG,
                    positionOnLine: 0,
                    color: "#000000",
                    alpha: 0.1,
                    animateAlongLine: true,
                    lineId: "line2",
                    flipDirection: true,
                    loop: true,
                    scale: 0.03,
                    positionScale: 1.3
                }, {
                    svgPath: planeSVG,
                    positionOnLine: 0,
                    color: "#585869",
                    animateAlongLine: true,
                    lineId: "line1",
                    flipDirection: true,
                    loop: true,
                    scale: 0.03,
                    positionScale: 1.8
                }]
            },

            areasSettings: {
                unlistedAreasColor: "#cc8c18"
            },

            imagesSettings: {
                color: "#585869",
                rollOverColor: "#ffffff",
                selectedColor: "#585869",
                pauseDuration: 0.2,
                animationDuration: 2.5,
                adjustAnimationSpeed: false
            },

            linesSettings: {
                color: "#585869",
                alpha: 0.4
            }
        });

        // Fix Owl Carousel nav button accessibility after carousel initialises.
        // Owl Carousel 2 injects role="presentation" on <button> which is invalid
        // (interactive elements cannot use role="none"/"presentation"), and leaves
        // the buttons without an accessible name. We correct both issues here.
        document.addEventListener('DOMContentLoaded', function () {
            var cityCarousel = document.querySelector('.single-city-item');
            if (!cityCarousel) return;

            // Owl fires 'initialized.owl.carousel' on the element
            $(cityCarousel).on('initialized.owl.carousel', function () {
                fixOwlNavButtons(cityCarousel);
            });

            // Fallback: also fix after a short delay in case init already fired
            setTimeout(function () { fixOwlNavButtons(cityCarousel); }, 500);
        });

        function fixOwlNavButtons(carousel) {
            var prevLabel = '{{ __('layout.carousel.prev') }}';
            var nextLabel = '{{ __('layout.carousel.next') }}';
            var prevBtn = carousel.querySelector('.owl-prev');
            var nextBtn = carousel.querySelector('.owl-next');
            if (prevBtn) {
                prevBtn.removeAttribute('role');
                prevBtn.setAttribute('aria-label', prevLabel);
            }
            if (nextBtn) {
                nextBtn.removeAttribute('role');
                nextBtn.setAttribute('aria-label', nextLabel);
            }
        }
    </script>
@endpush