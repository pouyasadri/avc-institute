@extends('layouts.university')

@php
    $universityName = 'pantheon-sorbonne';
@endphp

@section('title', __('university/pantheon-sorbonne.title'))
@section('keywords', __('university/pantheon-sorbonne.keywords'))
@section('description', __('university/pantheon-sorbonne.description'))

@section('header_class', 'bg-pantheon')
@section('breadcrumb_current', __('university/pantheon-sorbonne.breadcrumb_current'))
@section('page_title_heading', __('university/pantheon-sorbonne.main_heading'))

@section('toc', true)
@section('toc_title', __('university/pantheon-sorbonne.table_of_contents'))
@section('contact_title', __('university/pantheon-sorbonne.contact_us'))
@section('consultation_text', __('university/pantheon-sorbonne.consultation_request'))
@section('ask_question_title', __('university/pantheon-sorbonne.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
            {{ __('university/pantheon-sorbonne.useful_links') }}
        </h4>
        <ul class="list-unstyled mb-0">
            <li class="mb-2">
                <a href="https://www.pantheonsorbonne.fr/" target="_blank" rel="noopener noreferrer"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-globe me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/pantheon-sorbonne.official_website') }}</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="https://fa.wikipedia.org/wiki/%D8%AF%D8%A7%D9%86%D8%B4%DA%AF%D8%A7%D9%87_%D9%BE%D8%A7%D9%86%D8%AA%D8%A6%D9%88%D9%86-%D7%B3%D9%88%D8%B1%D8%A8%D9%86"
                    target="_blank" rel="noopener noreferrer" class="d-flex align-items-center text-decoration-none">
                    <i class='bx bxl-wikipedia me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/pantheon-sorbonne.wikipedia_link') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ url(app()->getLocale() . '/cities/paris') }}"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-map-alt me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/pantheon-sorbonne.paris_city_guide') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('university_content')
    <h2 class="h3 fw-bold mb-4">{{ __('university/pantheon-sorbonne.page_title') }}</h2>

    <div class="single-services-imgs mb-4">
        <img src="{{asset("assets/img/universities/Sorbonne/pantheon_university.webp")}}"
            alt="{{ __('university/pantheon-sorbonne.page_title') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <p class="lead text-muted mb-4">{{ __('university/pantheon-sorbonne.intro_content') }}</p>

    <div class="map-container mb-5 rounded-4 overflow-hidden shadow-sm">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d21003.212951968875!2d2.3073685!3d48.8505515!3m2!i1024!2i768!4f13.1!3m3!1m2!1s0x47e671dd98fffb41%3A0xfecd14d84c0a92b5!2sCampus%20des%20Cordeliers%20-%20Sorbonne%20Universit%C3%A9!5e0!3m2!1sfr!2sfr!4v1691002038922!5m2!1sfr!2sfr"
            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/pantheon-sorbonne.global_leader_title') }}</h3>
        <p class="text-muted">{{ __('university/pantheon-sorbonne.global_leader_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/pantheon-sorbonne.programs_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/pantheon-sorbonne.programs_content') }}</p>
        <ul class="list-group list-group-flush mb-4">
            @foreach(__('university/pantheon-sorbonne.subjects') as $subject)
                <li class="list-group-item bg-transparent px-0 py-2 border-0 d-flex align-items-center">
                    <i class="bx bx-check-circle text-primary me-2"></i>
                    <span>{{ $subject }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <div class="rooms-details mb-5">
        <img src="{{asset("assets/img/universities/Sorbonne/pantheon_university_1.webp")}}"
            alt="{{ __('university/pantheon-sorbonne.page_title') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/pantheon-sorbonne.research_title') }}</h3>
        <p class="text-muted">{{ __('university/pantheon-sorbonne.research_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/pantheon-sorbonne.admission_title') }}</h3>
        <p class="text-muted">{{ __('university/pantheon-sorbonne.admission_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/pantheon-sorbonne.career_title') }}</h3>
        <p class="text-muted">{{ __('university/pantheon-sorbonne.career_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/pantheon-sorbonne.environment_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/pantheon-sorbonne.environment_content') }}</p>
        <div class="row g-3">
            @foreach(__('university/pantheon-sorbonne.campuses') as $campus)
                <div class="col-md-6">
                    <div class="p-3 rounded-4 bg-light border-0 h-100 d-flex align-items-center">
                        <i class="bx bx-buildings text-primary me-2 fs-5"></i>
                        <span class="small fw-medium">{{ $campus }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/pantheon-sorbonne.conclusion_title') }}</h3>
        <p class="text-muted">{{ __('university/pantheon-sorbonne.conclusion_content') }}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-primary-subtle border-0 mb-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <img src="{{asset("assets/img/universities/Sorbonne/pantheon_logo.webp")}}"
                    alt="{{ __('university/pantheon-sorbonne.page_title') }}" style="max-width: 150px;" class="img-fluid">
            </div>
            <div class="col-lg-8">
                <div class="row g-2">
                    @foreach(__('university/pantheon-sorbonne.features') as $feature)
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
    <x-sections.faq :title="__('university/pantheon-sorbonne.faq_title')"
        :subtitle="__('university/pantheon-sorbonne.faq_subtitle')" :items="__('university/pantheon-sorbonne.faq_items')"
        id="paris1-faq" />
@endsection

@push("json")
    @php
        $currentLocale = app()->getLocale();
        $pageUrl = url($currentLocale . '/universities/pantheon-sorbonne');
        $universityId = $pageUrl . '#university';
        $officialUrl = 'https://www.pantheonsorbonne.fr/';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('university/pantheon-sorbonne.main_heading'),
            __('university/pantheon-sorbonne.description'),
            $currentLocale,
            $universityId,
            asset('assets/img/universities/Sorbonne/pantheon_logo.webp')
        );

        $university = new \App\Services\StructuredData\UniversitySchema(
            $universityId,
            __('university/pantheon-sorbonne.breadcrumb_current'),
            $officialUrl,
            __('university/pantheon-sorbonne.schema_description'),
            asset('assets/img/universities/Sorbonne/pantheon_logo.webp'),
            [
                $officialUrl,
                'https://fa.wikipedia.org/wiki/%D8%AF%D8%A7%D9%86%D8%B4%DA%AF%D8%A7%D9%87_%D9%BE%D8%A7%D9%86%D8%AA%D8%A6%D9%88%D9%86-%D7%B3%D9%88%D8%B1%D8%A8%D9%86',
            ]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('universities.breadcrumb_universities') ?? 'Universities', 'url' => url($currentLocale . '/universities')],
            ['name' => __('university/pantheon-sorbonne.breadcrumb_current'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$university" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush