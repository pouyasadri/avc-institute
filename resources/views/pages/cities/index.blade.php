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