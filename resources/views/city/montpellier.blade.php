@extends('layouts.city')

@php
    $currentLocale = app()->getLocale();
    $cityName = 'montpellier';
@endphp

@section('title', __('city/montpellier.title'))
@section('keywords', __('city/montpellier.keywords'))
@section('description', __('city/montpellier.description'))

@section('header_class', 'bg-montpellier-city')
@section('breadcrumb_current', __('city/montpellier.breadcrumb_montpellier'))
@section('page_title_heading', __('city/montpellier.main_heading'))

@section('toc_title', __('city/montpellier.table_of_contents'))
@section('contact_title', __('city/montpellier.contact_us'))
@section('consultation_text', __('city/montpellier.consultation_request'))
@section('ask_question_title', __('city/montpellier.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">{{ __('city/montpellier.useful_links') }}</h4>
        <ul class="list-unstyled mb-0">
            <li>
                <a href="https://en.wikipedia.org/wiki/Montpellier" target="_blank"
                    class="d-flex align-items-center text-decoration-none">
                    <i class="bx bxl-internet-explorer me-2 fs-5 text-primary"></i>
                    <span>{{ __('city/montpellier.montpellier_wikipedia') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@push('json')
    @php
        $pageUrl = url($currentLocale . '/cities/montpellier');
        $cityId = $pageUrl . '#city';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('city/montpellier.main_heading'),
            __('city/montpellier.description'),
            $currentLocale,
            $cityId,
            asset('assets/img/cities/montpellier/montpellier.webp')
        );

        $city = new \App\Services\StructuredData\CityGuideSchema(
            $cityId,
            __('city/montpellier.breadcrumb_montpellier'),
            __('city/montpellier.intro_paragraph'),
            asset('assets/img/cities/Montpellier/montpellier.webp'),
            ['https://en.wikipedia.org/wiki/Montpellier'],
            ['lat' => 43.6112, 'lng' => 3.8767]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('cities.breadcrumb_cities'), 'url' => url($currentLocale . '/cities')],
            ['name' => __('city/montpellier.breadcrumb_montpellier'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$city" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush

@section('city_content')
    <section class="mb-5">
        <h2 class="h3 fw-bold mb-4">{{ __('city/montpellier.intro_heading') }}</h2>
        <div class="single-services-imgs mb-4">
            <img src="{{ asset('assets/img/cities/montpellier/montpellier1.webp') }}" alt="{{ __('city/montpellier.breadcrumb_montpellier') }}"
                class="img-fluid rounded-4 shadow-sm w-100">
        </div>
        <p class="lead">{!! __('city/montpellier.intro_paragraph') !!}</p>
    </section>

    <div class="rounded-4 overflow-hidden shadow-sm mb-5">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d11342.348602206772!2d3.8767!3d43.6112!3m2!i1024!2i768!4f13.1!3m3!1m2!1s0x478af48bd6893637%3A0x408ab2ae4ba2120!2sMontpellier!5e0!3m2!1sfr!2sfr!4v1691146753003!5m2!1sfr!2sfr"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/montpellier.student_life_heading') }}</h3>
        <p>{{ __('city/montpellier.student_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/montpellier.family_life_heading') }}</h3>
        <p>{{ __('city/montpellier.family_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/montpellier.lifestyle_heading') }}</h3>
        <p>{{ __('city/montpellier.lifestyle_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/montpellier.history_heading') }}</h3>
        <p>{{ __('city/montpellier.history_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/montpellier.climate_heading') }}</h3>
        <p>{{ __('city/montpellier.climate_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/montpellier.study_heading') }}</h3>
        <p>{{ __('city/montpellier.study_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/montpellier.universities_heading') }}</h3>
        <p>{{ __('city/montpellier.universities_intro') }}</p>
        <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item bg-transparent border-0 ps-0">
                <i class="bx bx-right-arrow-alt text-primary me-2"></i>
                <a href="{{ url($currentLocale . '/universities/universite-de-montpellier') }}">{{ __('city/montpellier.university_montpellier') }}</a>
            </li>
        </ul>
    </section>

    <div class="mb-5">
        <img src="{{ asset('assets/img/cities/montpellier/montpellier.webp') }}" alt="{{ __('city/montpellier.breadcrumb_montpellier') }}"
            class="img-fluid rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/montpellier.tourism_heading') }}</h3>
        <p>{{ __('city/montpellier.tourism_paragraph_1') }}</p>
        @if(is_array(__('city/montpellier.tourism_items')))
            <ul class="list-group list-group-flush mb-4">
                @foreach (__('city/montpellier.tourism_items') as $item)
                    <li class="list-group-item bg-transparent border-0 ps-0">
                        <i class="bx bx-camera text-primary me-2"></i>
                        {{ $item }}
                    </li>
                @endforeach
            </ul>
        @endif
        <p>{{ __('city/montpellier.tourism_paragraph_2') }}</p>
        <p>{{ __('city/montpellier.tourism_paragraph_3') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/montpellier.economy_heading') }}</h3>
        <p>{{ __('city/montpellier.economy_paragraph_1') }}</p>
        @if(is_array(__('city/montpellier.economy_companies')))
            <ul class="list-group list-group-flush mb-4">
                @foreach (__('city/montpellier.economy_companies') as $company)
                    <li class="list-group-item bg-transparent border-0 ps-0">
                        <i class="bx bx-buildings text-primary me-2"></i>
                        {{ $company }}
                    </li>
                @endforeach
            </ul>
        @endif
        <p>{{ __('city/montpellier.economy_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/montpellier.living_costs_heading') }}</h3>
        <p>{!! __('city/montpellier.living_costs_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/montpellier.job_heading') }}</h3>
        <p>{{ __('city/montpellier.job_paragraph_1') }}</p>
        <p>{{ __('city/montpellier.job_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/montpellier.visa_heading') }}</h3>
        <p>{{ __('city/montpellier.visa_paragraph') }}</p>
    </section>

    <div class="mb-5">
        <img src="{{ asset('assets/img/cities/montpellier/montpellier2.webp') }}" alt="{{ __('city/montpellier.breadcrumb_montpellier') }}"
            class="img-fluid rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/montpellier.conclusion_heading') }}</h3>
        <p>{!! __('city/montpellier.conclusion_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-light border-0 mt-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <i class='bx bxs-city text-primary' style="font-size: 5rem;"></i>
                <h4 class="h5 fw-bold mt-3">{{ __('city/montpellier.breadcrumb_montpellier') }}</h4>
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/montpellier.student_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/montpellier.family_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/montpellier.lifestyle_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/montpellier.study_heading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-sections.faq :title="__('city/montpellier.faq_title')" :subtitle="__('city/montpellier.faq_subtitle')"
        :items="__('city/montpellier.faq_items')" id="montpellier-faq" />
@endsection
