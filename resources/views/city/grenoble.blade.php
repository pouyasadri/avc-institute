@extends('layouts.city')

@php
    $currentLocale = app()->getLocale();
    $cityName = 'grenoble';
@endphp

@section('title', __('city/grenoble.title'))
@section('keywords', __('city/grenoble.keywords'))
@section('description', __('city/grenoble.description'))

@section('header_class', 'bg-grenoble-city')
@section('breadcrumb_current', __('city/grenoble.breadcrumb_grenoble'))
@section('page_title_heading', __('city/grenoble.main_heading'))

@section('toc_title', __('city/grenoble.table_of_contents'))
@section('contact_title', __('city/grenoble.contact_us'))
@section('consultation_text', __('city/grenoble.consultation_request'))
@section('ask_question_title', __('city/grenoble.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">{{ __('city/grenoble.useful_links') }}</h4>
        <ul class="list-unstyled mb-0">
            <li>
                <a href="https://en.wikipedia.org/wiki/Grenoble" target="_blank"
                    class="d-flex align-items-center text-decoration-none">
                    <i class="bx bxl-internet-explorer me-2 fs-5 text-primary"></i>
                    <span>{{ __('city/grenoble.grenoble_wikipedia') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@push('json')
    @php
        $pageUrl = url($currentLocale . '/cities/grenoble');
        $cityId = $pageUrl . '#city';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('city/grenoble.main_heading'),
            __('city/grenoble.description'),
            $currentLocale,
            $cityId,
            asset('assets/img/cities/Grenoble/grenoble.webp')
        );

        $city = new \App\Services\StructuredData\CityGuideSchema(
            $cityId,
            __('city/grenoble.breadcrumb_grenoble'),
            __('city/grenoble.intro_paragraph'),
            asset('assets/img/cities/Grenoble/grenoble.webp'),
            ['https://en.wikipedia.org/wiki/Grenoble'],
            ['lat' => 45.1885, 'lng' => 5.7245]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('cities.breadcrumb_cities'), 'url' => url($currentLocale . '/cities')],
            ['name' => __('city/grenoble.breadcrumb_grenoble'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$city" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush

@section('city_content')
    <section class="mb-5">
        <h2 class="h3 fw-bold mb-4">{{ __('city/grenoble.intro_heading') }}</h2>
        <div class="single-services-imgs mb-4">
            <img src="{{ asset('assets/img/cities/Grenoble/grenoble1.webp') }}" alt="{{ __('city/grenoble.breadcrumb_grenoble') }}"
                class="img-fluid rounded-4 shadow-sm w-100">
        </div>
        <p class="lead">{!! __('city/grenoble.intro_paragraph') !!}</p>
    </section>

    <div class="rounded-4 overflow-hidden shadow-sm mb-5">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d178167.27644170693!2d4.8262037!3d45.7538785!3m2!i1024!2i768!4f13.1!3m3!1m2!1s0x47f4ea516ae88797%3A0x408ab2ae4bb21f0!2sGrenoble!5e0!3m2!1sfr!2sfr!4v1691146753003!5m2!1sfr!2sfr"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/grenoble.student_life_heading') }}</h3>
        <p>{{ __('city/grenoble.student_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/grenoble.family_life_heading') }}</h3>
        <p>{{ __('city/grenoble.family_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/grenoble.lifestyle_heading') }}</h3>
        <p>{{ __('city/grenoble.lifestyle_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/grenoble.history_heading') }}</h3>
        <p>{{ __('city/grenoble.history_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/grenoble.climate_heading') }}</h3>
        <p>{{ __('city/grenoble.climate_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/grenoble.study_heading') }}</h3>
        <p>{{ __('city/grenoble.study_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/grenoble.universities_heading') }}</h3>
        <p>{{ __('city/grenoble.universities_intro') }}</p>
        <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item bg-transparent border-0 ps-0">
                <i class="bx bx-right-arrow-alt text-primary me-2"></i>
                <a href="{{ url($currentLocale . '/universities/universite-grenoble-alpes') }}"
                    target="_blank">{{ __('city/grenoble.university_grenoble_alpes') }}</a>
            </li>
        </ul>
    </section>

    <div class="mb-5">
        <img src="{{ asset('assets/img/cities/Grenoble/grenoble.webp') }}" alt="{{ __('city/grenoble.breadcrumb_grenoble') }}"
            class="img-fluid rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/grenoble.tourism_heading') }}</h3>
        <p>{{ __('city/grenoble.tourism_paragraph_1') }}</p>
        @if(is_array(__('city/grenoble.tourism_items')))
            <ul class="list-group list-group-flush mb-4">
                @foreach (__('city/grenoble.tourism_items') as $item)
                    <li class="list-group-item bg-transparent border-0 ps-0">
                        <i class="bx bx-camera text-primary me-2"></i>
                        {{ $item }}
                    </li>
                @endforeach
            </ul>
        @endif
        <p>{{ __('city/grenoble.tourism_paragraph_2') }}</p>
        <p>{{ __('city/grenoble.tourism_paragraph_3') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/grenoble.economy_heading') }}</h3>
        <p>{{ __('city/grenoble.economy_paragraph_1') }}</p>
        @if(is_array(__('city/grenoble.economy_companies')))
            <ul class="list-group list-group-flush mb-4">
                @foreach (__('city/grenoble.economy_companies') as $company)
                    <li class="list-group-item bg-transparent border-0 ps-0">
                        <i class="bx bx-buildings text-primary me-2"></i>
                        {{ $company }}
                    </li>
                @endforeach
            </ul>
        @endif
        <p>{{ __('city/grenoble.economy_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/grenoble.living_costs_heading') }}</h3>
        <p>{!! __('city/grenoble.living_costs_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/grenoble.job_heading') }}</h3>
        <p>{{ __('city/grenoble.job_paragraph_1') }}</p>
        <p>{{ __('city/grenoble.job_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/grenoble.visa_heading') }}</h3>
        <p>{{ __('city/grenoble.visa_paragraph') }}</p>
    </section>

    <div class="mb-5">
        <img src="{{ asset('assets/img/cities/Grenoble/grenoble2.webp') }}" alt="{{ __('city/grenoble.breadcrumb_grenoble') }}"
            class="img-fluid rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/grenoble.conclusion_heading') }}</h3>
        <p>{!! __('city/grenoble.conclusion_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-light border-0 mt-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <i class='bx bxs-city text-primary' style="font-size: 5rem;"></i>
                <h4 class="h5 fw-bold mt-3">{{ __('city/grenoble.breadcrumb_grenoble') }}</h4>
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/grenoble.student_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/grenoble.family_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/grenoble.lifestyle_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/grenoble.study_heading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-sections.faq :title="__('city/grenoble.faq_title')" :subtitle="__('city/grenoble.faq_subtitle')"
        :items="__('city/grenoble.faq_items')" id="grenoble-faq" />
@endsection