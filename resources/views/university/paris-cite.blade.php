@extends('layouts.university')

@php
    $universityName = 'paris-cite';
@endphp

@section('title', __('university/paris-cite.title'))
@section('keywords', __('university/paris-cite.keywords'))
@section('description', __('university/paris-cite.description'))

@section('header_class', 'bg-paris-7')
@section('breadcrumb_current', __('university/paris-cite.breadcrumb_current'))
@section('page_title_heading', __('university/paris-cite.main_heading'))

@section('toc', true)
@section('toc_title', __('university/paris-cite.table_of_contents'))
@section('contact_title', __('university/paris-cite.contact_us'))
@section('consultation_text', __('university/paris-cite.consultation_request'))
@section('ask_question_title', __('university/paris-cite.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">{{ __('university/paris-cite.useful_links') }}</h4>
        <ul class="list-unstyled mb-0">
            <li class="mb-2">
                <a href="https://u-paris.fr/" target="_blank" rel="noopener noreferrer" class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-globe me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/paris-cite.official_website') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ url(app()->getLocale() . '/cities/paris') }}" target="_blank" rel="noopener noreferrer"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-map-alt me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/paris-cite.paris_city_guide') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('university_content')
    <h2 class="h3 fw-bold mb-4">{{ __('university/paris-cite.page_title') }}</h2>

    <div class="single-services-imgs mb-4">
        <img src="{{asset("assets/img/universities/Paris_cite/Paris_cite_university.jpg")}}"
            alt="{{ __('university/paris-cite.main_heading') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-cite.introduction_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/paris-cite.introduction_content') }}</p>
        <div class="row g-2">
            @foreach(__('university/paris-cite.study_areas') as $area)
                <div class="col-md-6">
                    <div class="d-flex align-items-center small text-muted">
                        <i class="bx bx-chevron-right text-primary"></i>
                        <span>{{ $area }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-cite.global_excellence_title') }}</h3>
        <p class="text-muted">{{ __('university/paris-cite.global_excellence_content') }}</p>
    </section>

    <div class="map-container mb-5 rounded-4 overflow-hidden shadow-sm">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.140775385137!2d2.3334787!3d48.855525899999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6716d7d0fd943%3A0xd9bdd83ca3691659!2sUniversit%C3%A9%20Paris%20Cit%C3%A9!5e0!3m2!1sfr!2sfr!4v1709732228531!5m2!1sfr!2sfr"
            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-cite.facilities_title') }}</h3>
        <p class="text-muted">{{ __('university/paris-cite.facilities_content') }}</p>
    </section>

    <div class="rooms-details mb-5">
        <img src="{{asset("assets/img/universities/Paris_cite/Paris_cite_university_1.webp")}}"
            alt="{{ __('university/paris-cite.main_heading') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-cite.iranian_students_title') }}</h3>
        <p class="text-muted">{{ __('university/paris-cite.iranian_students_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-cite.career_opportunities_title') }}</h3>
        <p class="text-muted">{{ __('university/paris-cite.career_opportunities_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-cite.multicultural_environment_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/paris-cite.multicultural_environment_content') }}</p>
        <div class="row g-3">
            @foreach(__('university/paris-cite.facilities_list') as $facility)
                <div class="col-md-6">
                    <div class="p-3 rounded-4 bg-light h-100 d-flex align-items-center">
                        <i class="bx bx-check text-primary me-2"></i>
                        <span class="small">{{ $facility }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/paris-cite.conclusion_title') }}</h3>
        <p class="text-muted">{{ __('university/paris-cite.conclusion_content') }}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-primary-subtle border-0">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <img src="{{asset("assets/img/universities/Paris_cite/Paris_cite_university_logo.webp")}}"
                    alt="{{ __('university/paris-cite.main_heading') }}" style="max-width: 150px;" class="img-fluid">
            </div>
            <div class="col-lg-8">
                <div class="row g-2">
                    @foreach(__('university/paris-cite.features') as $feature)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center small text-primary-emphasis">
                                <i class='bx bx-check-double me-2'></i>
                                <span>{{ $feature }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <x-sections.faq :title="__('university/paris-cite.faq_title')" :subtitle="__('university/paris-cite.faq_subtitle')"
        :items="__('university/paris-cite.faq_items')" id="cite-faq" />
@endsection

@push("json")
    @php
        $currentLocale = app()->getLocale();
        $pageUrl = url($currentLocale . '/universities/paris-cite');
        $universityId = $pageUrl . '#university';
        $officialUrl = 'https://u-paris.fr/';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('university/paris-cite.main_heading'),
            __('university/paris-cite.description'),
            $currentLocale,
            $universityId,
            asset('assets/img/universities/Paris_cite/Paris_cite_university_logo.webp')
        );

        $university = new \App\Services\StructuredData\UniversitySchema(
            $universityId,
            __('university/paris-cite.breadcrumb_current'),
            $officialUrl,
            __('university/paris-cite.description'),
            asset('assets/img/universities/Paris_cite/Paris_cite_university_logo.webp'),
            [$officialUrl]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('universities.breadcrumb_universities') ?? 'Universities', 'url' => url($currentLocale . '/universities')],
            ['name' => __('university/paris-cite.breadcrumb_current'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$university" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush