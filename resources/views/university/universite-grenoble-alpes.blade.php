@extends('layouts.university')

@php
    $universityName = 'universite-grenoble-alpes';
@endphp

@section('title', __('university/universite-grenoble-alpes.title'))
@section('keywords', __('university/universite-grenoble-alpes.keywords'))
@section('description', __('university/universite-grenoble-alpes.description'))

@section('header_class', 'bg-grenoble-alpes')
@section('breadcrumb_current', __('university/universite-grenoble-alpes.breadcrumb_current'))
@section('page_title_heading', __('university/universite-grenoble-alpes.main_heading'))

@section('toc', true)
@section('toc_title', __('university/universite-grenoble-alpes.table_of_contents'))
@section('contact_title', __('university/universite-grenoble-alpes.contact_us'))
@section('consultation_text', __('university/universite-grenoble-alpes.consultation_request'))
@section('ask_question_title', __('university/universite-grenoble-alpes.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
            {{ __('university/universite-grenoble-alpes.useful_links') }}
        </h4>
        <ul class="list-unstyled mb-0">
            <li class="mb-2">
                <a href="https://www.univ-grenoble-alpes.fr/" target="_blank"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-globe me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/universite-grenoble-alpes.official_website') }}</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="https://fr.wikipedia.org/wiki/Universit%C3%A9_Grenoble-Alpes" target="_blank"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bxl-wikipedia me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/universite-grenoble-alpes.wikipedia_link') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ url(app()->getLocale() . '/cities/grenoble') }}" target="_blank"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-map-alt me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/universite-grenoble-alpes.grenoble_city_guide') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('university_content')
    <h2 class="h3 fw-bold mb-4">{{ __('university/universite-grenoble-alpes.page_title') }}</h2>

    <div class="single-services-imgs mb-4">
        <img src="{{asset("assets/img/universities/Grenoble_Alpes/grenoble_alpes_university.webp")}}"
            alt="{{ __('university/universite-grenoble-alpes.page_title') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <p class="lead text-muted mb-4">{{ __('university/universite-grenoble-alpes.intro_content') }}</p>

    <div class="map-container mb-5 rounded-4 overflow-hidden shadow-sm">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2812.185675862283!2d5.766327315545564!3d45.193231979098696!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x478af4e5baaa30e9%3A0xc3f58a7bf1329a24!2sUniversit%C3%A9%20Grenoble%20Alpes!5e0!3m2!1sen!2sfr!4v1691234567891!5m2!1sen!2sfr"
            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-grenoble-alpes.global_leader_title') }}</h3>
        <p class="text-muted">{{ __('university/universite-grenoble-alpes.global_leader_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-grenoble-alpes.programs_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/universite-grenoble-alpes.programs_content') }}</p>
        <ul class="list-group list-group-flush mb-4">
            @foreach(__('university/universite-grenoble-alpes.subjects') as $subject)
                <li class="list-group-item bg-transparent px-0 py-2 border-0 d-flex align-items-center">
                    <i class="bx bx-check-circle text-primary me-2"></i>
                    <span>{{ $subject }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-grenoble-alpes.research_title') }}</h3>
        <p class="text-muted">{{ __('university/universite-grenoble-alpes.research_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-grenoble-alpes.admission_title') }}</h3>
        <p class="text-muted">{{ __('university/universite-grenoble-alpes.admission_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-grenoble-alpes.career_title') }}</h3>
        <p class="text-muted">{{ __('university/universite-grenoble-alpes.career_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-grenoble-alpes.environment_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/universite-grenoble-alpes.environment_content') }}</p>
        <div class="row g-3">
            @foreach(__('university/universite-grenoble-alpes.facilities') as $facility)
                <div class="col-md-6">
                    <div class="p-3 rounded-4 bg-light border-0 h-100 d-flex align-items-center">
                        <i class="bx bx-buildings text-primary me-2 fs-5"></i>
                        <span class="small fw-medium">{{ $facility }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-grenoble-alpes.conclusion_title') }}</h3>
        <p class="text-muted">{{ __('university/universite-grenoble-alpes.conclusion_content') }}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-primary-subtle border-0 mb-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <img src="{{asset("assets/img/universities/Grenoble_Alpes/grenoble_alpes_logo.webp")}}"
                    alt="{{ __('university/universite-grenoble-alpes.page_title') }}" style="max-width: 150px;"
                    class="img-fluid">
            </div>
            <div class="col-lg-8">
                <div class="row g-2">
                    @foreach(__('university/universite-grenoble-alpes.features') as $index => $feature)
                        <div class="col-md-6">
                            <div class="d-flex align-items-start small text-primary-emphasis">
                                <i class='bx bx-check-double me-2 mt-1'></i>
                                <span>{{ $feature }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <x-sections.faq :title="__('university/universite-grenoble-alpes.faq_title')"
        :subtitle="__('university/universite-grenoble-alpes.faq_subtitle')"
        :items="__('university/universite-grenoble-alpes.faq_items')" id="grenoble-alpes-faq" />
@endsection

@push("json")
    @php
        $currentLocale = app()->getLocale();
        $pageUrl = url($currentLocale . '/universities/universite-grenoble-alpes');
        $universityId = $pageUrl . '#university';
        $officialUrl = 'https://www.univ-grenoble-alpes.fr/';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('university/universite-grenoble-alpes.main_heading'),
            __('university/universite-grenoble-alpes.description'),
            $currentLocale,
            $universityId,
            asset('assets/img/universities/Grenoble_Alpes/grenoble_alpes_logo.webp')
        );

        $university = new \App\Services\StructuredData\UniversitySchema(
            $universityId,
            __('university/universite-grenoble-alpes.breadcrumb_current'),
            $officialUrl,
            __('university/universite-grenoble-alpes.schema_description'),
            asset('assets/img/universities/Grenoble_Alpes/grenoble_alpes_logo.webp'),
            [
                $officialUrl,
                'https://fr.wikipedia.org/wiki/Universit%C3%A9_Grenoble-Alpes',
            ]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('universities.breadcrumb_universities') ?? 'Universities', 'url' => url($currentLocale . '/universities')],
            ['name' => __('university/universite-grenoble-alpes.breadcrumb_current'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$university" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush