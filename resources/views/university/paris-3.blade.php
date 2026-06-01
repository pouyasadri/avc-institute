@extends('layouts.university')

@php
    $universityName = 'paris-3';
@endphp

@section('title', __('university/paris-3.title'))
@section('keywords', __('university/paris-3.keywords'))
@section('description', __('university/paris-3.description'))

@section('header_class', 'bg-paris-3')
@section('breadcrumb_current', __('university/paris-3.breadcrumb_current'))
@section('page_title_heading', __('university/paris-3.main_heading'))

@section('toc', true)
@section('toc_title', __('university/paris-3.table_of_contents'))
@section('contact_title', __('university/paris-3.contact_us'))
@section('consultation_text', __('university/paris-3.consultation_request'))
@section('ask_question_title', __('university/paris-3.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
            {{ __('university/paris-3.useful_links') }}
        </h4>
        <ul class="list-unstyled mb-0">
            <li class="mb-2">
                <a href="https://www.univ-paris3.fr/" target="_blank" rel="noopener noreferrer"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-globe me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/paris-3.official_website') }}</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="https://fa.wikipedia.org/wiki/%D8%AF%D8%A7%D9%86%D8%B4%DA%AF%D8%A7%D9%87_%D9%BE%D8%A7%D8%B1%DB%8C%D8%B3_%DB%B3:_%D8%B3%D9%88%D8%B1%D8%A8%D9%86_%D8%AC%D8%AF%DB%8C%D8%AF"
                    target="_blank" rel="noopener noreferrer" class="d-flex align-items-center text-decoration-none">
                    <i class='bx bxl-wikipedia me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/paris-3.wikipedia_link') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ url(app()->getLocale() . '/cities/paris') }}" target="_blank" rel="noopener noreferrer"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-map-alt me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/paris-3.paris_city_guide') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('university_content')
    <h2 class="h3 fw-bold mb-4">{{ __('university/paris-3.page_title') }}</h2>

    <div class="single-services-imgs mb-4">
        <img src="{{asset("assets/img/universities/Paris3/paris_3_university.webp")}}"
            alt="{{ __('university/paris-3.page_title') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <p class="lead text-muted mb-4">{{ __('university/paris-3.intro_content') }}</p>

    <div class="map-container mb-5 rounded-4 overflow-hidden shadow-sm">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2625.965529168461!2d2.351692!3d48.8397962!3m2!i1024!2i768!4f13.1!3m3!1m2!1s0x47e6739464bab6b1%3A0xf88b91f36aab3e5!2sUniversit%C3%A9%20Sorbonne%20Nouvelle!5e0!3m2!1sfr!2sfr!4v1690996762229!5m2!1sfr!2sfr"
            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-3.global_leader_title') }}</h3>
        <p class="text-muted">{{ __('university/paris-3.global_leader_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-3.programs_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/paris-3.programs_content') }}</p>
        <ul class="list-group list-group-flush mb-4">
            @foreach(__('university/paris-3.subjects') as $subject)
                <li class="list-group-item bg-transparent px-0 py-2 border-0 d-flex align-items-center">
                    <i class="bx bx-check-circle text-primary me-2"></i>
                    <span>{{ $subject }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <div class="rooms-details mb-5">
        <img src="{{asset("assets/img/universities/Paris3/paris_3_university_1.webp")}}"
            alt="{{ __('university/paris-3.page_title') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-3.research_title') }}</h3>
        <p class="text-muted">{{ __('university/paris-3.research_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-3.admission_title') }}</h3>
        <p class="text-muted">{{ __('university/paris-3.admission_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-3.career_title') }}</h3>
        <p class="text-muted">{{ __('university/paris-3.career_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-3.environment_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/paris-3.environment_content') }}</p>
        <div class="row g-3">
            @foreach(__('university/paris-3.facilities') as $facility)
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
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-3.conclusion_title') }}</h3>
        <p class="text-muted">{{ __('university/paris-3.conclusion_content') }}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-primary-subtle border-0 mb-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <img src="{{asset("assets/img/universities/Paris3/paris_3_logo.webp")}}"
                    alt="{{ __('university/paris-3.page_title') }}" style="max-width: 150px;" class="img-fluid">
            </div>
            <div class="col-lg-8">
                <div class="row g-2">
                    @foreach(__('university/paris-3.features') as $index => $feature)
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
    <x-sections.faq :title="__('university/paris-3.faq_title')" :subtitle="__('university/paris-3.faq_subtitle')"
        :items="__('university/paris-3.faq_items')" id="paris3-faq" />
@endsection

@push("json")
    @php
        $currentLocale = app()->getLocale();
        $pageUrl = url($currentLocale . '/universities/paris-3');
        $universityId = $pageUrl . '#university';
        $officialUrl = 'https://www.univ-paris3.fr/';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('university/paris-3.main_heading'),
            __('university/paris-3.description'),
            $currentLocale,
            $universityId,
            asset('assets/img/universities/Paris3/paris_3_logo.webp')
        );

        $university = new \App\Services\StructuredData\UniversitySchema(
            $universityId,
            __('university/paris-3.breadcrumb_current'),
            $officialUrl,
            __('university/paris-3.schema_description'),
            asset('assets/img/universities/Paris3/paris_3_logo.webp'),
            [
                $officialUrl,
                'https://fa.wikipedia.org/wiki/%D8%AF%D8%A7%D9%86%D8%B4%DA%AF%D8%A7%D9%87_%D9%BE%D8%A7%D8%B1%DB%8C%D8%B3_%DB%B3:_%D8%B3%D9%88%D8%B1%D8%A8%D9%86_%D8%AC%D8%AF%DB%8C%D8%AF',
            ]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('universities.breadcrumb_universities') ?? 'Universities', 'url' => url($currentLocale . '/universities')],
            ['name' => __('university/paris-3.breadcrumb_current'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$university" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush