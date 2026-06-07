@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);

    // Map each university slug to its city page slug (only cities that have a page)
    $universityCityMap = [
        'paris-saclay-university'   => 'paris',
        'sorbonne-paris-nord'       => 'paris',
        'paris-cite'                => 'paris',
        'paris-4-sorbonne'          => 'paris',
        'paris-3'                   => 'paris',
        'paris-2'                   => 'paris',
        'pantheon-sorbonne'         => 'paris',
        'universite-psl'            => 'paris',
        'ip-paris'                  => 'paris',
        'sciences-po'               => 'paris',
        'lyon-1'                    => 'lyon',
        'lyon-2'                    => 'lyon',
        'lyon-3'                    => 'lyon',
        'cote-d-azure'              => 'nice',
        'toulouse'                  => 'toulouse',
        'strasbourg'                => 'strasbourg',
        'universite-grenoble-alpes' => 'grenoble',
        'universite-de-bordeaux'    => 'bordeaux',
        // Universities in cities with no dedicated page return null
        'aix-marseille-university'  => null,
        'universite-de-lille'       => null,
        'universite-de-montpellier' => 'montpellier',
    ];
    $universitySlug = $universityName ?? '';
    $linkedCity = $universityCityMap[$universitySlug] ?? null;
@endphp

@section('content')
    <!-- Page Title Area -->
    <header class="page-title-area @yield('header_class')" role="banner">
        <div class="container">
            <div class="page-title-content">
                <x-premium-breadcrumb :items="[
            ['url' => url($currentLocale . '/'), 'label' => __('layout.home') ?? 'Home'],
            ['url' => url($currentLocale . '/universities'), 'label' => __('universities.breadcrumb_universities')],
            ['label' => trim($__env->yieldContent('breadcrumb_current'))]
        ]" />
                <h1>@yield('page_title_heading')</h1>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <section class="service-details-area ptb-100">
        <div class="container" id="mydiv">
            <div class="row g-4">
                <!-- Sidebar -->
                <aside class="col-lg-4 order-2 order-lg-1">
                    <div class="service-sidebar-area">
                        <!-- Table of Contents -->
                        @if (trim($__env->yieldContent('toc')))
                            <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
                                <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
                                    @yield('toc_title', 'Table of Contents')</h4>
                                <ol id="board" class="list-unstyled mb-0"></ol>
                            </div>
                        @endif

                        <!-- Contact Sidebar -->
                        <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
                            <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
                                @yield('contact_title', 'Contact Us')</h4>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <a href="{{ url($currentLocale . "/consult") }}"
                                        class="d-flex align-items-center text-decoration-none">
                                        <i class='bx bx-time me-2 fs-5 text-primary'></i>
                                        <span>@yield('consultation_text')</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="mailto:info@applyvipconseil.com"
                                        class="d-flex align-items-center text-decoration-none">
                                        <i class='bx bx-envelope me-2 fs-5 text-primary'></i>
                                        <span>info@applyvipconseil.com</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {{-- Related Universities --}}
                        <x-university.related :currentSlug="$universitySlug" />

                        {{-- City Guide Link (auto-mapped from university slug) --}}
                        @if($linkedCity)
                            <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
                                <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
                                    {{ __('universities.city_guide') }}
                                </h4>
                                <a href="{{ route('cities.' . $linkedCity, ['locale' => $currentLocale]) }}"
                                   class="d-flex align-items-center text-decoration-none text-dark">
                                    <i class='bx bx-map-alt me-2 fs-5 text-primary'></i>
                                    <span class="small fw-medium">
                                        {{ __('cities.' . $linkedCity . '_title') }}
                                    </span>
                                </a>
                            </div>
                        @endif

                        <!-- Useful Links -->
                        <div class="sidebar-widgets">
                            @yield('useful_links')
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="col-lg-8 order-1 order-lg-2">
                    <article class="service-details-wrap p-4 p-md-5 rounded-5 shadow-sm bg-white border-0">
                        <div class="article-content">
                            @yield('university_content')
                        </div>

                        <!-- Contact Form -->
                        <div class="ask-question mt-5 pt-5 border-top">
                            <h3 class="h4 fw-bold mb-4">@yield('ask_question_title')</h3>
                            <x-forms.university-contact pageType="university" :pageName="$universityName ?? 'unknown'" />
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/js/createScrollLinks.js') }}"></script>
@endsection
