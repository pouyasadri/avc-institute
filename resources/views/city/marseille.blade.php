@extends('layouts.city')

@php
    $currentLocale = app()->getLocale();
    $cityName = 'marseille';
@endphp

@section('title', __('city/marseille.title'))
@section('keywords', __('city/marseille.keywords'))
@section('description', __('city/marseille.description'))

@section('header_class', 'bg-marseille-city')
@section('breadcrumb_current', __('city/marseille.breadcrumb_marseille'))
@section('page_title_heading', __('city/marseille.main_heading'))

@section('toc_title', __('city/marseille.table_of_contents'))
@section('contact_title', __('city/marseille.contact_us'))
@section('consultation_text', __('city/marseille.consultation_request'))
@section('ask_question_title', __('city/marseille.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">{{ __('city/marseille.useful_links') }}</h4>
        <ul class="list-unstyled mb-0">
            <li>
                <a href="https://en.wikipedia.org/wiki/Marseille" target="_blank"
                    class="d-flex align-items-center text-decoration-none">
                    <i class="bx bxl-internet-explorer me-2 fs-5 text-primary"></i>
                    <span>{{ __('city/marseille.marseille_wikipedia') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@push('json')
    @php
        $pageUrl = url($currentLocale . '/cities/marseille');
        $cityId = $pageUrl . '#city';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('city/marseille.main_heading'),
            __('city/marseille.description'),
            $currentLocale,
            $cityId,
            asset('assets/img/cities/marseille/marseille.webp')
        );

        $city = new \App\Services\StructuredData\CityGuideSchema(
            $cityId,
            __('city/marseille.breadcrumb_marseille'),
            __('city/marseille.intro_paragraph'),
            asset('assets/img/cities/Marseille/marseille.webp'),
            ['https://en.wikipedia.org/wiki/Marseille'],
            ['lat' => 43.2965, 'lng' => 5.3698]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('cities.breadcrumb_cities'), 'url' => url($currentLocale . '/cities')],
            ['name' => __('city/marseille.breadcrumb_marseille'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$city" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush

@section('city_content')
    <section class="mb-5">
        <h2 class="h3 fw-bold mb-4">{{ __('city/marseille.intro_heading') }}</h2>
        <div class="single-services-imgs mb-4">
            <img src="{{ asset('assets/img/cities/marseille/marseille1.webp') }}" alt="{{ __('city/marseille.breadcrumb_marseille') }}"
                class="img-fluid rounded-4 shadow-sm w-100">
        </div>
        <p class="lead">{!! __('city/marseille.intro_paragraph') !!}</p>
    </section>

    <div class="rounded-4 overflow-hidden shadow-sm mb-5">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d11342.348602206772!2d5.3698!3d43.2965!3m2!i1024!2i768!4f13.1!3m3!1m2!1s0x478af48bd6893637%3A0x408ab2ae4ba2120!2sMarseille!5e0!3m2!1sfr!2sfr!4v1691146753003!5m2!1sfr!2sfr"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/marseille.student_life_heading') }}</h3>
        <p>{{ __('city/marseille.student_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.family_life_heading') }}</h3>
        <p>{{ __('city/marseille.family_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.lifestyle_heading') }}</h3>
        <p>{{ __('city/marseille.lifestyle_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.history_heading') }}</h3>
        <p>{{ __('city/marseille.history_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.climate_heading') }}</h3>
        <p>{{ __('city/marseille.climate_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.study_heading') }}</h3>
        <p>{{ __('city/marseille.study_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.universities_heading') }}</h3>
        <p>{{ __('city/marseille.universities_intro') }}</p>
        <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item bg-transparent border-0 ps-0">
                <i class="bx bx-right-arrow-alt text-primary me-2"></i>
                <a href="{{ url($currentLocale . '/universities/universite-de-marseille') }}">{{ __('city/marseille.university_marseille') }}</a>
            </li>
        </ul>
    </section>

    <div class="mb-5">
        <img src="{{ asset('assets/img/cities/marseille/marseille.webp') }}" alt="{{ __('city/marseille.breadcrumb_marseille') }}"
            class="img-fluid rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/marseille.tourism_heading') }}</h3>
        <p>{{ __('city/marseille.tourism_paragraph_1') }}</p>
        @if(is_array(__('city/marseille.tourism_items')))
            <ul class="list-group list-group-flush mb-4">
                @foreach (__('city/marseille.tourism_items') as $item)
                    <li class="list-group-item bg-transparent border-0 ps-0">
                        <i class="bx bx-camera text-primary me-2"></i>
                        {{ $item }}
                    </li>
                @endforeach
            </ul>
        @endif
        <p>{{ __('city/marseille.tourism_paragraph_2') }}</p>
        <p>{{ __('city/marseille.tourism_paragraph_3') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.economy_heading') }}</h3>
        <p>{{ __('city/marseille.economy_paragraph_1') }}</p>
        @if(is_array(__('city/marseille.economy_companies')))
            <ul class="list-group list-group-flush mb-4">
                @foreach (__('city/marseille.economy_companies') as $company)
                    <li class="list-group-item bg-transparent border-0 ps-0">
                        <i class="bx bx-buildings text-primary me-2"></i>
                        {{ $company }}
                    </li>
                @endforeach
            </ul>
        @endif
        <p>{{ __('city/marseille.economy_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.living_costs_heading') }}</h3>
        <p>{!! __('city/marseille.living_costs_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.job_heading') }}</h3>
        <p>{{ __('city/marseille.job_paragraph_1') }}</p>
        <p>{{ __('city/marseille.job_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.visa_heading') }}</h3>
        <p>{{ __('city/marseille.visa_paragraph') }}</p>
    </section>

    <div class="mb-5">
        <img src="{{ asset('assets/img/cities/marseille/marseille2.webp') }}" alt="{{ __('city/marseille.breadcrumb_marseille') }}"
            class="img-fluid rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/marseille.conclusion_heading') }}</h3>
        <p>{!! __('city/marseille.conclusion_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-light border-0 mt-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <i class='bx bxs-city text-primary' style="font-size: 5rem;"></i>
                <h4 class="h5 fw-bold mt-3">{{ __('city/marseille.breadcrumb_marseille') }}</h4>
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/marseille.student_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/marseille.family_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/marseille.lifestyle_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/marseille.study_heading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-sections.faq :title="__('city/marseille.faq_title')" :subtitle="__('city/marseille.faq_subtitle')"
        :items="__('city/marseille.faq_items')" id="marseille-faq" />
@endsection
