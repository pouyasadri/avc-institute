@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
    $arrowClass = $isRtl ? 'flaticon-left-arrow' : 'flaticon-right-arrow';

    // Metadata for SEO
    $pageTitle = __('cities.title');
    $pageKeywords = __('cities.keywords');
    $pageDescription = __('cities.description');

    $cities = [
        [
            'slug' => 'paris',
            'img' => 'assets/img/cities/Paris/paris-slider.webp',
            'title_key' => 'paris_title',
            'desc_key' => 'paris_description',
            'alt_key' => 'paris_alt',
        ],
        [
            'slug' => 'lyon',
            'img' => 'assets/img/cities/Lyon/lyon.webp',
            'title_key' => 'lyon_title',
            'desc_key' => 'lyon_description',
            'alt_key' => 'lyon_alt',
        ],
        [
            'slug' => 'strasbourg',
            'img' => 'assets/img/cities/Strasbourg/strasbourg.webp',
            'title_key' => 'strasbourg_title',
            'desc_key' => 'strasbourg_description',
            'alt_key' => 'strasbourg_alt',
        ],
        [
            'slug' => 'nice',
            'img' => 'assets/img/cities/Nice/nice1.webp',
            'title_key' => 'nice_title',
            'desc_key' => 'nice_description',
            'alt_key' => 'nice_alt',
        ],
        [
            'slug' => 'toulouse',
            'img' => 'assets/img/cities/Toulouse/toulouse.webp',
            'title_key' => 'toulouse_title',
            'desc_key' => 'toulouse_description',
            'alt_key' => 'toulouse_alt',
        ],
        [
            'slug' => 'grenoble',
            'img' => 'assets/img/cities/Grenoble/grenoble.webp',
            'title_key' => 'grenoble_title',
            'desc_key' => 'grenoble_description',
            'alt_key' => 'grenoble_alt',
        ],
        [
            'slug' => 'bordeaux',
            'img' => 'assets/img/cities/bordeaux/bordeaux.webp',
            'title_key' => 'bordeaux_title',
            'desc_key' => 'bordeaux_description',
            'alt_key' => 'bordeaux_alt',
        ],
        [
            'slug' => 'montpellier',
            'img' => 'assets/img/cities/montpellier/montpellier.webp',
            'title_key' => 'montpellier_title',
            'desc_key' => 'montpellier_description',
            'alt_key' => 'montpellier_alt',
        ],

        [
            'slug' => 'marseille',
            'img' => 'assets/img/cities/marseille/marseille.webp',
            'title_key' => 'marseille_title',
            'desc_key' => 'marseille_description',
            'alt_key' => 'marseille_alt',
        ],
    ];
@endphp

@section('title', $pageTitle)
@section('keywords', $pageKeywords)
@section('description', $pageDescription)

@section('content')
    <div>
        <!-- Start Page Title Area -->
        <header class="page-title-area" role="banner">
            <div class="container">
                <div class="page-title-content">
                    <x-premium-breadcrumb :items="[
            ['url' => url($currentLocale . '/'), 'label' => __('cities.breadcrumb_home')],
            ['label' => __('cities.breadcrumb_cities')]
        ]" />
                    <h1>{{ __('cities.main_heading') }}</h1>
                </div>
            </div>
        </header>

        <!-- Start Cities Section -->
        <section class="exclusive-area pt-100 pb-100">
            <div class="container">
                <div class="section-title text-center mb-5">
                    <span class="text-uppercase fw-bold text-primary tracking-wider">{{ __('cities.section_title') }}</span>
                    <h2 class="display-6 fw-bold mt-2">{{ __('cities.section_heading') }}</h2>
                    <p class="mx-auto mt-3 text-muted" style="max-width: 800px;">
                        {{ __('cities.section_paragraph') }}
                    </p>
                </div>

                {{-- مقایسه شهرها — targeting "بهترین شهرهای فرانسه برای تحصیل" keyword --}}
                <div class="mb-5" id="cities-comparison">
                    <h3 class="h4 fw-bold mb-2 text-center">{{ __('cities.comparison_title') }}</h3>
                    <p class="text-muted text-center mb-4 small">{{ __('cities.comparison_subtitle') }}</p>

                    <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-xl-5">

                        {{-- لیون --}}
                        <div class="col">
                            <div class="city-compare-card h-100 rounded-4 p-4 bg-white shadow-sm border-0 d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="city-icon fs-2">🦁</span>
                                    <div>
                                        <h4 class="h6 fw-bold mb-0">{{ __('cities.lyon') }}</h4>
                                        <span class="badge bg-success-subtle text-success small">{{ __('cities.lyon_tag') }}</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>💸 {{ __('cities.comparison_monthly_cost') }}</span>
                                    </div>
                                    <div class="cost-pill fw-bold text-primary">{{ __('cities.lyon_cost') }}</div>
                                    <div class="progress mt-1" style="height:4px;">
                                        <div class="progress-bar bg-success" style="width:55%"></div>
                                    </div>
                                </div>
                                <ul class="list-unstyled small text-muted mt-2 mb-3 flex-grow-1">
                                    <li class="mb-1"><i class="bx bx-check text-success me-1"></i>{{ __('cities.lyon_feature_1') }}</li>
                                    <li class="mb-1"><i class="bx bx-group text-primary me-1"></i>{{ __('cities.lyon_feature_2') }}</li>
                                    <li><i class="bx bx-heart text-danger me-1"></i>{{ __('cities.lyon_feature_3') }}</li>
                                </ul>
                                <a href="{{ url($currentLocale . '/cities/lyon') }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto">{{ __('cities.comparison_more_info') }}</a>
                            </div>
                        </div>

                        {{-- پاریس --}}
                        <div class="col">
                            <div class="city-compare-card h-100 rounded-4 p-4 bg-white shadow-sm border-0 d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="city-icon fs-2">🗼</span>
                                    <div>
                                        <h4 class="h6 fw-bold mb-0">{{ __('cities.paris') }}</h4>
                                        <span class="badge bg-primary-subtle text-primary small">{{ __('cities.paris_tag') }}</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>💸 {{ __('cities.comparison_monthly_cost') }}</span>
                                    </div>
                                    <div class="cost-pill fw-bold text-primary">{{ __('cities.paris_cost') }}</div>
                                    <div class="progress mt-1" style="height:4px;">
                                        <div class="progress-bar bg-danger" style="width:90%"></div>
                                    </div>
                                </div>
                                <ul class="list-unstyled small text-muted mt-2 mb-3 flex-grow-1">
                                    <li class="mb-1"><i class="bx bx-check text-success me-1"></i>{{ __('cities.paris_feature_1') }}</li>
                                    <li class="mb-1"><i class="bx bx-group text-primary me-1"></i>{{ __('cities.paris_feature_2') }}</li>
                                    <li><i class="bx bx-trending-up text-warning me-1"></i>{{ __('cities.paris_feature_3') }}</li>
                                </ul>
                                <a href="{{ url($currentLocale . '/cities/paris') }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto">{{ __('cities.comparison_more_info') }}</a>
                            </div>
                        </div>

                        {{-- تولوز --}}
                        <div class="col">
                            <div class="city-compare-card h-100 rounded-4 p-4 bg-white shadow-sm border-0 d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="city-icon fs-2">✈️</span>
                                    <div>
                                        <h4 class="h6 fw-bold mb-0">{{ __('cities.toulouse') }}</h4>
                                        <span class="badge bg-warning-subtle text-warning-emphasis small">{{ __('cities.toulouse_tag') }}</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>💸 {{ __('cities.comparison_monthly_cost') }}</span>
                                    </div>
                                    <div class="cost-pill fw-bold text-primary">{{ __('cities.toulouse_cost') }}</div>
                                    <div class="progress mt-1" style="height:4px;">
                                        <div class="progress-bar bg-success" style="width:50%"></div>
                                    </div>
                                </div>
                                <ul class="list-unstyled small text-muted mt-2 mb-3 flex-grow-1">
                                    <li class="mb-1"><i class="bx bx-check text-success me-1"></i>{{ __('cities.toulouse_feature_1') }}</li>
                                    <li class="mb-1"><i class="bx bx-group text-primary me-1"></i>{{ __('cities.toulouse_feature_2') }}</li>
                                    <li><i class="bx bx-buildings text-info me-1"></i>{{ __('cities.toulouse_feature_3') }}</li>
                                </ul>
                                <a href="{{ url($currentLocale . '/cities/toulouse') }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto">{{ __('cities.comparison_more_info') }}</a>
                            </div>
                        </div>

                        {{-- استراسبورگ --}}
                        <div class="col">
                            <div class="city-compare-card h-100 rounded-4 p-4 bg-white shadow-sm border-0 d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="city-icon fs-2">🏛️</span>
                                    <div>
                                        <h4 class="h6 fw-bold mb-0">{{ __('cities.strasbourg') }}</h4>
                                        <span class="badge bg-info-subtle text-info small">{{ __('cities.strasbourg_tag') }}</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>💸 {{ __('cities.comparison_monthly_cost') }}</span>
                                    </div>
                                    <div class="cost-pill fw-bold text-primary">{{ __('cities.strasbourg_cost') }}</div>
                                    <div class="progress mt-1" style="height:4px;">
                                        <div class="progress-bar bg-info" style="width:40%"></div>
                                    </div>
                                </div>
                                <ul class="list-unstyled small text-muted mt-2 mb-3 flex-grow-1">
                                    <li class="mb-1"><i class="bx bx-check text-success me-1"></i>{{ __('cities.strasbourg_feature_1') }}</li>
                                    <li class="mb-1"><i class="bx bx-group text-primary me-1"></i>{{ __('cities.strasbourg_feature_2') }}</li>
                                    <li><i class="bx bx-globe text-success me-1"></i>{{ __('cities.strasbourg_feature_3') }}</li>
                                </ul>
                                <a href="{{ url($currentLocale . '/cities/strasbourg') }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto">{{ __('cities.comparison_more_info') }}</a>
                            </div>
                        </div>

                        {{-- نیس --}}
                        <div class="col">
                            <div class="city-compare-card h-100 rounded-4 p-4 bg-white shadow-sm border-0 d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="city-icon fs-2">☀️</span>
                                    <div>
                                        <h4 class="h6 fw-bold mb-0">{{ __('cities.nice') }}</h4>
                                        <span class="badge bg-danger-subtle text-danger small">{{ __('cities.nice_tag') }}</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>💸 {{ __('cities.comparison_monthly_cost') }}</span>
                                    </div>
                                    <div class="cost-pill fw-bold text-primary">{{ __('cities.nice_cost') }}</div>
                                    <div class="progress mt-1" style="height:4px;">
                                        <div class="progress-bar bg-warning" style="width:65%"></div>
                                    </div>
                                </div>
                                <ul class="list-unstyled small text-muted mt-2 mb-3 flex-grow-1">
                                    <li class="mb-1"><i class="bx bx-check text-success me-1"></i>{{ __('cities.nice_feature_1') }}</li>
                                    <li class="mb-1"><i class="bx bx-group text-primary me-1"></i>{{ __('cities.nice_feature_2') }}</li>
                                    <li><i class="bx bx-sun text-warning me-1"></i>{{ __('cities.nice_feature_3') }}</li>
                                </ul>
                                <a href="{{ url($currentLocale . '/cities/nice') }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto">{{ __('cities.comparison_more_info') }}</a>
                            </div>
                        </div>

                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ url($currentLocale . '/consult') }}"
                           class="btn btn-primary rounded-pill px-5 py-2 shadow-sm">
                            <i class="bx bx-chat me-2"></i>
                            {{ __('cities.comparison_cta') }}
                        </a>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach ($cities as $city)
                        <div class="col-lg-6">
                            <article
                                class="exclusive-wrap rounded-5 shadow-sm overflow-hidden h-100 bg-white border-0 transition-all hover-lift">
                                <div class="row g-0 align-items-stretch h-100">
                                    <div class="col-md-5">
                                        <div class="exclusive-img h-100 overflow-hidden position-relative">
                                            <img src="{{ asset($city['img']) }}" alt="{{ __('cities.' . $city['alt_key']) }}"
                                                class="w-100 h-100 transition-all img-zoom"
                                                style="object-fit: cover; min-height: 280px;">
                                            <div class="image-overlay"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-7 d-flex align-items-center">
                                        <div class="exclusive-content p-4 p-md-4 w-100">
                                            <h3 class="h4 fw-bold mb-2 text-dark">{{ __('cities.' . $city['title_key']) }}</h3>
                                            <p class="text-muted mb-4 lead-sm" style="font-size: 0.95rem; line-height: 1.6;">
                                                {{ __('cities.' . $city['desc_key']) }}
                                            </p>
                                            <div class="mt-auto">
                                                <a href="{{ url($currentLocale . '/cities/' . $city['slug']) }}"
                                                    class="btn btn-primary rounded-pill px-4 py-2 transition-all d-inline-flex align-items-center gap-2 shadow-sm">
                                                    <span>{{ __('cities.read_more') }}</span>
                                                    <i class="{{ $arrowClass }} fs-5"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <style>
                    .hover-lift {
                        transition: transform 0.3s ease, box-shadow 0.3s ease;
                    }

                    .hover-lift:hover {
                        transform: translateY(-8px);
                        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .1) !important;
                    }

                    .exclusive-img .img-zoom {
                        transition: transform 0.5s ease;
                    }

                    .exclusive-wrap:hover .img-zoom {
                        transform: scale(1.1);
                    }

                    .image-overlay {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: linear-gradient(to right, rgba(0, 0, 0, 0.1), transparent);
                        pointer-events: none;
                    }

                    .lead-sm {
                        display: -webkit-box;
                        -webkit-line-clamp: 3;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                    }

                    /* City Comparison Cards */
                    .city-compare-card {
                        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
                        border: 1.5px solid transparent !important;
                    }

                    .city-compare-card:hover {
                        transform: translateY(-6px);
                        box-shadow: 0 0.75rem 2rem rgba(0, 0, 0, 0.1) !important;
                        border-color: var(--bs-primary) !important;
                    }

                    .city-compare-card .cost-pill {
                        font-size: 1.1rem;
                        letter-spacing: -0.02em;
                    }

                    .city-compare-card .city-icon {
                        line-height: 1;
                        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
                    }

                    .city-compare-card .progress {
                        background-color: #f0f0f0;
                        border-radius: 10px;
                    }
                </style>
            </div>
        </section>
    </div>
@endsection

@push('json')
    @php
        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('cities.breadcrumb_home'), 'url' => url($currentLocale . '/')],
            ['name' => __('cities.breadcrumb_cities'), 'url' => url($currentLocale . '/cities')],
        ]);

        $collectionPage = new \App\Services\StructuredData\CollectionPageSchema(
            url()->current(),
            $pageTitle,
            $pageDescription,
            $currentLocale
        );

        $list = new \App\Services\StructuredData\ItemListSchema($pageTitle);
        $position = 1;
        foreach ($cities as $city) {
            $list->addItem(
                $position,
                url($currentLocale . '/cities/' . $city['slug']),
                __('cities.' . $city['title_key']),
                asset($city['img'])
            );
            $position++;
        }
    @endphp

    <x-seo.structured-data :schema="$collectionPage" />
    <x-seo.structured-data :schema="$breadcrumb" />
    <x-seo.structured-data :schema="$list" />
@endpush
