@extends('layouts.city')

@php
    $currentLocale = app()->getLocale();
    $cityName = 'paris';
@endphp

@section('title', __('city/paris.title'))
@section('keywords', __('city/paris.keywords'))
@section('description', __('city/paris.description'))

@section('header_class', 'bg-paris-city')
@section('breadcrumb_current', __('city/paris.breadcrumb_paris'))
@section('page_title_heading', __('city/paris.main_heading'))

@section('toc_title', __('city/paris.table_of_contents'))
@section('contact_title', __('city/paris.contact_us'))
@section('consultation_text', __('city/paris.consultation_request'))
@section('ask_question_title', __('city/paris.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">{{ __('city/paris.useful_links') }}</h4>
        <ul class="list-unstyled mb-0">
            <li>
                <a href="https://en.wikipedia.org/wiki/Paris" target="_blank"
                    class="d-flex align-items-center text-decoration-none">
                    <i class="bx bxl-internet-explorer me-2 fs-5 text-primary"></i>
                    <span>{{ __('city/paris.paris_wikipedia') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('city_content')
    <section class="mb-5">
        <h2 class="h3 fw-bold mb-4">{{ __('city/paris.intro_heading') }}</h2>
        <div class="single-services-imgs mb-4">
            <img src="{{ asset('assets/img/cities/Paris/paris5.webp') }}"
                alt="{{ __('city/paris.breadcrumb_paris') }}" class="img-fluid rounded-4 shadow-sm w-100">
        </div>
        <p class="lead">{!! __('city/paris.intro_paragraph') !!}</p>
    </section>

    <div class="rounded-4 overflow-hidden shadow-sm mb-5">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d84004.81432415127!2d2.3499021!3d48.8553214!3m2!i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1f06e2b70f%3A0x40b82c3688c9460!2sParis!5e0!3m2!1sfr!2sfr!4v1691146313133!5m2!1sfr!2sfr"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/paris.student_life_heading') }}</h3>
        <p>{{ __('city/paris.student_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/paris.family_life_heading') }}</h3>
        <p>{{ __('city/paris.family_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/paris.lifestyle_heading') }}</h3>
        <p>{{ __('city/paris.lifestyle_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/paris.history_heading') }}</h3>
        <p>{{ __('city/paris.history_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/paris.climate_heading') }}</h3>
        <p>{{ __('city/paris.climate_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/paris.study_heading') }}</h3>
        <p>{{ __('city/paris.study_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/paris.universities_heading') }}</h3>
        <p>{{ __('city/paris.universities_intro') }}</p>
        <ul class="list-group list-group-flush mb-4">
            @foreach ([
                'pantheon-sorbonne' => 'university_pantheon_sorbonne',
                'paris-cite' => 'university_paris_cite',
                'paris-2' => 'university_pantheon_assas',
                'paris-3' => 'university_sorbonne_nouvelle',
                'paris-4-sorbonne' => 'university_sorbonne',
                'paris-saclay-university' => 'university_paris_saclay',
                'sorbonne-paris-nord' => 'university_sorbonne_paris_nord'
            ] as $slug => $key)
                <li class="list-group-item bg-transparent border-0 ps-0">
                    <i class="bx bx-right-arrow-alt text-primary me-2"></i>
                    <a href="{{ url($currentLocale . '/universities/' . $slug) }}">{{ __('city/paris.' . $key) }}</a>
                </li>
            @endforeach
        </ul>
    </section>

    <div class="mb-5">
        <img src="{{ asset('assets/img/cities/Paris/paris.webp') }}"
            alt="{{ __('city/paris.breadcrumb_paris') }}" class="img-fluid rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/paris.tourism_heading') }}</h3>
        <p>{{ __('city/paris.tourism_paragraph_1') }}</p>
        @if(is_array(__('city/paris.tourism_items')))
            <ul class="list-group list-group-flush mb-4">
                @foreach (__('city/paris.tourism_items') as $item)
                    <li class="list-group-item bg-transparent border-0 ps-0">
                        <i class="bx bx-camera text-primary me-2"></i>
                        {{ $item }}
                    </li>
                @endforeach
            </ul>
        @endif
        <p>{{ __('city/paris.tourism_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/paris.economy_heading') }}</h3>
        <p>{{ __('city/paris.economy_paragraph_1') }}</p>
        @if(is_array(__('city/paris.economy_companies')))
            <ul class="list-group list-group-flush mb-4">
                @foreach (__('city/paris.economy_companies') as $company)
                    <li class="list-group-item bg-transparent border-0 ps-0">
                        <i class="bx bx-buildings text-primary me-2"></i>
                        {{ $company }}
                    </li>
                @endforeach
            </ul>
        @endif
        <p>{{ __('city/paris.economy_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/paris.living_costs_heading') }}</h3>
        <p>{!! __('city/paris.living_costs_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/paris.job_heading') }}</h3>
        <p>{{ __('city/paris.job_paragraph_1') }}</p>
        <p>{{ __('city/paris.job_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/paris.visa_heading') }}</h3>
        <p>{{ __('city/paris.visa_paragraph') }}</p>
    </section>

    <div class="mb-5">
        <img src="{{ asset('assets/img/cities/Paris/paris2.webp') }}"
            alt="{{ __('city/paris.breadcrumb_paris') }}" class="img-fluid rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/paris.conclusion_heading') }}</h3>
        <p>{!! __('city/paris.conclusion_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-light border-0 mt-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <i class='bx bxs-city text-primary' style="font-size: 5rem;"></i>
                <h4 class="h5 fw-bold mt-3">{{ __('city/paris.breadcrumb_paris') }}</h4>
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/paris.student_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/paris.family_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/paris.lifestyle_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/paris.study_heading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-sections.faq
        :title="__('city/paris.faq_title')"
        :subtitle="__('city/paris.faq_subtitle')"
        :items="__('city/paris.faq_items')"
        id="paris-faq"
    />
@endsection

@push('json')
    @php
        $pageUrl = url($currentLocale.'/cities/paris');
        $cityId = $pageUrl.'#city';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('city/paris.main_heading'),
            __('city/paris.description'),
            $currentLocale,
            $cityId,
            asset('assets/img/cities/Paris/paris.webp')
        );

        $city = new \App\Services\StructuredData\CityGuideSchema(
            $cityId,
            __('city/paris.breadcrumb_paris'),
            __('city/paris.intro_paragraph'),
            asset('assets/img/cities/Paris/paris.webp'),
            ['https://en.wikipedia.org/wiki/Paris'],
            ['lat' => 48.8567, 'lng' => 2.3510]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale.'/')],
            ['name' => __('cities.breadcrumb_cities'), 'url' => url($currentLocale.'/cities')],
            ['name' => __('city/paris.breadcrumb_paris'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$city" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush
