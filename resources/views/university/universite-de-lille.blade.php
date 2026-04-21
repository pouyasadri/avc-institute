@extends('layouts.university')

@php
    $universityName = 'universite-de-lille';
@endphp

@section('title', __('university/universite-de-lille.title'))
@section('keywords', __('university/universite-de-lille.keywords'))
@section('description', __('university/universite-de-lille.description'))

@section('header_class', 'bg-lille')
@section('breadcrumb_current', __('university/universite-de-lille.breadcrumb_current'))
@section('page_title_heading', __('university/universite-de-lille.main_heading'))

@section('toc', true)
@section('toc_title', __('university/universite-de-lille.table_of_contents'))
@section('contact_title', __('university/universite-de-lille.contact_us'))
@section('consultation_text', __('university/universite-de-lille.consultation_request'))
@section('ask_question_title', __('university/universite-de-lille.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
            {{ __('university/universite-de-lille.useful_links') }}
        </h4>
        <ul class="list-unstyled mb-0">
            <li class="mb-2">
                <a href="https://www.univ-lille.fr/" target="_blank"
                    class="d-flex align-items-center text-decoration-none transition-all hover-translate-x">
                    <i class='bx bx-globe me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/universite-de-lille.official_website') }}</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="https://en.wikipedia.org/wiki/University_of_Lille"
                    target="_blank" class="d-flex align-items-center text-decoration-none transition-all hover-translate-x">
                    <i class='bx bxl-wikipedia me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/universite-de-lille.wikipedia_link') }}</span>
                </a>
            </li>
            <li>
                <a href="#" class="d-flex align-items-center text-decoration-none transition-all hover-translate-x">
                    <i class='bx bx-map-alt me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/universite-de-lille.lille_city_guide') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('university_content')
    <h2 class="h3 fw-bold mb-4">{{ __('university/universite-de-lille.page_title') }}</h2>

    <div class="single-services-imgs mb-4">
        <img src="{{asset("assets/img/universities/Lille/lille_university.webp")}}"
            alt="{{ __('university/universite-de-lille.page_title') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <p class="lead text-muted mb-4">{{ __('university/universite-de-lille.intro_content') }}</p>

    <div class="map-container mb-5 rounded-4 overflow-hidden shadow-sm">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d161877.94025008511!2d2.9097722741916896!3d50.63110531548817!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c2d58a5e3a84ed%3A0x67ee65cfd3df767f!2sUniversity%20of%20Lille!5e0!3m2!1sen!2sfr!4v1691234567891!5m2!1sen!2sfr"
            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-de-lille.global_leader_title') }}</h3>
        <p class="text-muted">{{ __('university/universite-de-lille.global_leader_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-de-lille.programs_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/universite-de-lille.programs_content') }}</p>
        <ul class="list-group list-group-flush mb-4 shadow-sm rounded-4 border">
            @foreach(__('university/universite-de-lille.subjects') as $subject)
                <li class="list-group-item bg-white px-4 py-3 border-0 d-flex align-items-center">
                    <i class="bx bx-check-circle text-primary me-3 fs-5"></i>
                    <span class="fw-medium">{{ $subject }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-de-lille.research_title') }}</h3>
        <p class="text-muted">{{ __('university/universite-de-lille.research_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-de-lille.admission_title') }}</h3>
        <p class="text-muted">{{ __('university/universite-de-lille.admission_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-de-lille.career_title') }}</h3>
        <p class="text-muted">{{ __('university/universite-de-lille.career_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-de-lille.environment_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/universite-de-lille.environment_content') }}</p>
        <div class="row g-3">
            @foreach(__('university/universite-de-lille.facilities') as $facility)
                <div class="col-md-6">
                    <div class="p-3 rounded-4 bg-light border-0 h-100 d-flex align-items-center transition-all hover-lift shadow-sm">
                        <i class="bx bx-buildings text-primary me-2 fs-5"></i>
                        <span class="small fw-medium">{{ $facility }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/universite-de-lille.conclusion_title') }}</h3>
        <p class="text-muted">{{ __('university/universite-de-lille.conclusion_content') }}</p>
    </section>

    <div class="car-service-list-wrap p-5 rounded-5 bg-primary-subtle border-0 mb-5 shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <img src="{{asset("assets/img/universities/Lille/lille_logo.webp")}}"
                    alt="{{ __('university/universite-de-lille.page_title') }}" style="max-width: 180px;"
                    class="img-fluid drop-shadow">
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    @foreach(__('university/universite-de-lille.features') as $index => $feature)
                        <div class="col-md-6">
                            <div class="d-flex align-items-start small text-primary-emphasis fw-medium">
                                <i class='bx bx-check-double me-2 mt-1 fs-5'></i>
                                <span>{{ $feature }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <x-sections.faq :title="__('university/universite-de-lille.faq_title')" :subtitle="__('university/universite-de-lille.faq_subtitle')"
        :items="__('university/universite-de-lille.faq_items')" id="lille-faq" />
@endsection

@push("json")
    @php
        $currentLocale = app()->getLocale();
        $pageUrl = url($currentLocale . '/universities/universite-de-lille');
        $universityId = $pageUrl . '#university';
        $officialUrl = 'https://www.univ-lille.fr/';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('university/universite-de-lille.main_heading'),
            __('university/universite-de-lille.description'),
            $currentLocale,
            $universityId,
            asset('assets/img/universities/Lille/lille_logo.webp')
        );

        $university = new \App\Services\StructuredData\UniversitySchema(
            $universityId,
            __('university/universite-de-lille.breadcrumb_current'),
            $officialUrl,
            __('university/universite-de-lille.schema_description'),
            asset('assets/img/universities/Lille/lille_logo.webp'),
            [
                $officialUrl,
                'https://en.wikipedia.org/wiki/University_of_Lille',
            ]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('universities.breadcrumb_universities') ?? 'Universities', 'url' => url($currentLocale . '/universities')],
            ['name' => __('university/universite-de-lille.breadcrumb_current'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$university" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush
