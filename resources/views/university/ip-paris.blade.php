@extends('layouts.university')

@php
    $universityName = 'ip-paris';
@endphp

@section('title', __('university/ip-paris.title'))
@section('keywords', __('university/ip-paris.keywords'))
@section('description', __('university/ip-paris.description'))

@section('header_class', 'bg-ip-paris')
@section('breadcrumb_current', __('university/ip-paris.breadcrumb_current'))
@section('page_title_heading', __('university/ip-paris.main_heading'))

@section('toc', true)
@section('toc_title', __('university/ip-paris.table_of_contents'))
@section('contact_title', __('university/ip-paris.contact_us'))
@section('consultation_text', __('university/ip-paris.consultation_request'))
@section('ask_question_title', __('university/ip-paris.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
            {{ __('university/ip-paris.useful_links') }}
        </h4>
        <ul class="list-unstyled mb-0">
            <li class="mb-2">
                <a href="https://www.ip-paris.fr/" target="_blank" rel="noopener noreferrer" class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-globe me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/ip-paris.official_website') }}</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="https://fr.wikipedia.org/wiki/Institut_polytechnique_de_Paris" target="_blank" rel="noopener noreferrer"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bxl-wikipedia me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/ip-paris.wikipedia_link') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ url(app()->getLocale() . '/cities/paris') }}"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-map-alt me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/ip-paris.paris_city_guide') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('university_content')
    <h2 class="h3 fw-bold mb-4">{{ __('university/ip-paris.page_title') }}</h2>

    <div class="single-services-imgs mb-4">
        <img src="{{asset("assets/img/universities/IP_Paris/ip_paris_university_1.webp")}}"
            alt="{{ __('university/ip-paris.page_title') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <p class="lead text-muted mb-4">{{ __('university/ip-paris.intro_content') }}</p>

    <div class="map-container mb-5 rounded-4 overflow-hidden shadow-sm">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d21051.785055415712!2d2.2045615714777553!3d48.71260846067039!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e678cd82d02c83%3A0xe5f9b40097a8cb42!2sInstitut%20Polytechnique%20de%20Paris!5e0!3m2!1sen!2sfr!4v1691234567891!5m2!1sen!2sfr"
            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/ip-paris.global_leader_title') }}</h3>
        <p class="text-muted">{{ __('university/ip-paris.global_leader_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/ip-paris.programs_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/ip-paris.programs_content') }}</p>
        <ul class="list-group list-group-flush mb-4">
            @foreach(__('university/ip-paris.subjects') as $subject)
                <li class="list-group-item bg-transparent px-0 py-2 border-0 d-flex align-items-center">
                    <i class="bx bx-check-circle text-primary me-2"></i>
                    <span>{{ $subject }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/ip-paris.research_title') }}</h3>
        <p class="text-muted">{{ __('university/ip-paris.research_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/ip-paris.admission_title') }}</h3>
        <p class="text-muted">{{ __('university/ip-paris.admission_content') }}</p>
    </section>

    <div class="single-services-imgs mb-4">
        <img src="{{asset("assets/img/universities/IP_Paris/ip_paris_university.webp")}}"
            alt="{{ __('university/ip-paris.page_title') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/ip-paris.career_title') }}</h3>
        <p class="text-muted">{{ __('university/ip-paris.career_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/ip-paris.environment_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/ip-paris.environment_content') }}</p>
        <div class="row g-3">
            @foreach(__('university/ip-paris.facilities') as $facility)
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
        <h3 class="h4 fw-bold mb-3">{{ __('university/ip-paris.conclusion_title') }}</h3>
        <p class="text-muted">{{ __('university/ip-paris.conclusion_content') }}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-primary-subtle border-0 mb-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <img src="{{asset("assets/img/universities/IP_Paris/ip_paris_logo.webp")}}"
                    alt="{{ __('university/ip-paris.page_title') }}" style="max-width: 150px;" class="img-fluid">
            </div>
            <div class="col-lg-8">
                <div class="row g-2">
                    @foreach(__('university/ip-paris.features') as $index => $feature)
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
    <x-sections.faq :title="__('university/ip-paris.faq_title')" :subtitle="__('university/ip-paris.faq_subtitle')"
        :items="__('university/ip-paris.faq_items')" id="ip-paris-faq" />
@endsection

@push("json")
    @php
        $currentLocale = app()->getLocale();
        $pageUrl = url($currentLocale . '/universities/ip-paris');
        $universityId = $pageUrl . '#university';
        $officialUrl = 'https://www.ip-paris.fr/';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('university/ip-paris.main_heading'),
            __('university/ip-paris.description'),
            $currentLocale,
            $universityId,
            asset('assets/img/universities/IP_Paris/ip_paris_logo.webp')
        );

        $university = new \App\Services\StructuredData\UniversitySchema(
            $universityId,
            __('university/ip-paris.breadcrumb_current'),
            $officialUrl,
            __('university/ip-paris.schema_description'),
            asset('assets/img/universities/IP_Paris/ip_paris_logo.webp'),
            [
                $officialUrl,
                'https://fr.wikipedia.org/wiki/Institut_polytechnique_de_Paris',
            ]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('universities.breadcrumb_universities') ?? 'Universities', 'url' => url($currentLocale . '/universities')],
            ['name' => __('university/ip-paris.breadcrumb_current'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$university" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush