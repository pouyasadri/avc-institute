@extends('layouts.university')

@php
    $universityName = 'sorbonne-paris-nord';
@endphp

@section('title', __('university/sorbonne-paris-nord.title'))
@section('keywords', __('university/sorbonne-paris-nord.keywords'))
@section('description', __('university/sorbonne-paris-nord.description'))

@section('header_class', 'bg-paris-nord')
@section('breadcrumb_current', __('university/sorbonne-paris-nord.breadcrumb_current'))
@section('page_title_heading', __('university/sorbonne-paris-nord.main_heading'))

@section('toc', true)
@section('toc_title', __('university/sorbonne-paris-nord.table_of_contents'))
@section('contact_title', __('university/sorbonne-paris-nord.contact_us'))
@section('consultation_text', __('university/sorbonne-paris-nord.consultation_request'))
@section('ask_question_title', __('university/sorbonne-paris-nord.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">{{ __('university/sorbonne-paris-nord.useful_links') }}
        </h4>
        <ul class="list-unstyled mb-0">
            <li class="mb-2">
                <a href="https://www.univ-spn.fr/" target="_blank" rel="noopener noreferrer" class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-globe me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/sorbonne-paris-nord.official_website') }}</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="https://fa.wikipedia.org/wiki/%D8%AF%D8%A7%D9%86%D8%B4%DA%AF%D8%A7%D9%87_%D9%BE%D8%A7%D8%B1%DB%8C%D8%B3_%DB%B1%DB%B3"
                    target="_blank" rel="noopener noreferrer" class="d-flex align-items-center text-decoration-none">
                    <i class='bx bxl-wikipedia me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/sorbonne-paris-nord.wikipedia_link') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ url(app()->getLocale() . '/cities/paris') }}"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-map-alt me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/sorbonne-paris-nord.paris_city_guide') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('university_content')
    <h2 class="h3 fw-bold mb-4">{{ __('university/sorbonne-paris-nord.page_title') }}</h2>

    <div class="single-services-imgs mb-4">
        <img src="{{asset("assets/img/universities/Paris_Nord/paris_nord_university.webp")}}"
            alt="{{ __('university/sorbonne-paris-nord.main_heading') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/sorbonne-paris-nord.history_title') }}</h3>
        <p class="text-muted">{{ __('university/sorbonne-paris-nord.history_content') }}</p>
    </section>

    <div class="map-container mb-5 rounded-4 overflow-hidden shadow-sm">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d41918.64710230801!2d2.3334787!3d48.9550959!3m2!i1024!2i768!4f13.1!3m3!1m2!1s0x47e66922af4c2e23%3A0xfe3113a781a13fca!2sUniversit%C3%A9%20Sorbonne%20Paris%20Nord!5e0!3m2!1sfr!2sfr!4v1690991649424!5m2!1sfr!2sfr"
            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/sorbonne-paris-nord.location_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/sorbonne-paris-nord.location_content') }}</p>
        <div class="p-4 rounded-4 bg-light">
            <h5 class="fw-bold mb-3">{{ __('university/sorbonne-paris-nord.campus_title') }}</h5>
            <ul class="list-unstyled mb-0">
                @foreach(__('university/sorbonne-paris-nord.campuses') as $campus)
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bx bx-map-pin text-primary me-2"></i>
                        <span>{{ $campus }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/sorbonne-paris-nord.ranking_title') }}</h3>
        <p class="text-muted">{{ __('university/sorbonne-paris-nord.ranking_content') }}</p>
    </section>

    <div class="rooms-details mb-5">
        <img src="{{asset("assets/img/universities/Paris_Nord/paris_nord_university_1.webp")}}"
            alt="{{ __('university/sorbonne-paris-nord.main_heading') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/sorbonne-paris-nord.facilities_title') }}</h3>
        <p class="text-muted">{{ __('university/sorbonne-paris-nord.facilities_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/sorbonne-paris-nord.programs_title') }}</h3>
        <div class="p-4 rounded-4 bg-primary-subtle border-0">
            <p class="fw-bold text-primary-emphasis mb-3">{{ __('university/sorbonne-paris-nord.programs_intro') }}</p>
            <div class="row g-2">
                @foreach(__('university/sorbonne-paris-nord.programs') as $program)
                    <div class="col-md-6">
                        <div class="d-flex align-items-center small">
                            <i class="bx bx-check text-primary me-2"></i>
                            <span>{{ $program }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/sorbonne-paris-nord.iranian_students_title') }}</h3>
        <p class="text-muted">{{ __('university/sorbonne-paris-nord.iranian_students_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/sorbonne-paris-nord.tuition_title') }}</h3>
        <p class="text-muted">{{ __('university/sorbonne-paris-nord.tuition_content') }}</p>
    </section>

    <section class="mb-4">
        <h3 class="h4 fw-bold mb-3">{{ __('university/sorbonne-paris-nord.career_title') }}</h3>
        <p class="text-muted mb-4">{{ __('university/sorbonne-paris-nord.career_content') }}</p>

        <h3 class="h4 fw-bold mb-3">{{ __('university/sorbonne-paris-nord.environment_title') }}</h3>
        <p class="text-muted mb-4">{{ __('university/sorbonne-paris-nord.environment_content') }}</p>

        <h3 class="h4 fw-bold mb-3">{{ __('university/sorbonne-paris-nord.conclusion_title') }}</h3>
        <p class="text-muted mb-5">{{ __('university/sorbonne-paris-nord.conclusion_content') }}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-light border-0">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <img src="{{asset("assets/img/universities/Paris_Nord/paris_nord_logo.webp")}}"
                    alt="{{ __('university/sorbonne-paris-nord.main_heading') }}" style="max-width: 150px;"
                    class="img-fluid">
            </div>
            <div class="col-lg-8">
                <div class="row g-2">
                    @foreach(__('university/sorbonne-paris-nord.features') as $feature)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center small text-muted">
                                <i class='bx bx-check-double text-primary me-2'></i>
                                <span>{{ $feature }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <x-sections.faq :title="__('university/sorbonne-paris-nord.faq_title')"
        :subtitle="__('university/sorbonne-paris-nord.faq_subtitle')"
        :items="__('university/sorbonne-paris-nord.faq_items')" id="nord-faq" />
@endsection

@push("json")
    @php
        $currentLocale = app()->getLocale();
        $pageUrl = url($currentLocale . '/universities/sorbonne-paris-nord');
        $universityId = $pageUrl . '#university';
        $officialUrl = 'https://www.univ-spn.fr/';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('university/sorbonne-paris-nord.main_heading'),
            __('university/sorbonne-paris-nord.description'),
            $currentLocale,
            $universityId,
            asset('assets/img/universities/Paris_Nord/paris_nord_logo.webp')
        );

        $university = new \App\Services\StructuredData\UniversitySchema(
            $universityId,
            __('university/sorbonne-paris-nord.breadcrumb_current'),
            $officialUrl,
            __('university/sorbonne-paris-nord.description'),
            asset('assets/img/universities/Paris_Nord/paris_nord_logo.webp'),
            [
                $officialUrl,
                'https://fa.wikipedia.org/wiki/%D8%AF%D8%A7%D9%86%D8%B4%DA%AF%D8%A7%D9%87_%D9%BE%D8%A7%D8%B1%DB%8C%D8%B3_%DB%B1%DB%B3',
            ]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('universities.breadcrumb_universities') ?? 'Universities', 'url' => url($currentLocale . '/universities')],
            ['name' => __('university/sorbonne-paris-nord.breadcrumb_current'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$university" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush