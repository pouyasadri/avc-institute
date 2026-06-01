@extends('layouts.university')

@php
    $universityName = 'cote-d-azure';
@endphp

@section('title', __('university/cote-d-azure.title'))
@section('keywords', __('university/cote-d-azure.keywords'))
@section('description', __('university/cote-d-azure.description'))

@section('header_class', 'bg-nice')
@section('breadcrumb_current', __('university/cote-d-azure.breadcrumb_current'))
@section('page_title_heading', __('university/cote-d-azure.main_heading'))

@section('toc', true)
@section('toc_title', __('university/cote-d-azure.table_of_contents'))
@section('contact_title', __('university/cote-d-azure.contact_us'))
@section('consultation_text', __('university/cote-d-azure.consultation_request'))
@section('ask_question_title', __('university/cote-d-azure.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
            {{ __('university/cote-d-azure.useful_links') }}
        </h4>
        <ul class="list-unstyled mb-0">
            <li class="mb-2">
                <a href="https://univ-cotedazur.eu/" target="_blank" rel="noopener noreferrer" class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-globe me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/cote-d-azure.official_website') }}</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="https://fa.wikipedia.org/wiki/%D8%AF%D8%A7%D9%86%D8%B4%DA%AF%D8%A7%D9%87_%D9%BE%D8%A7%D9%86%D8%AA%D8%A6%D9%88%D9%86-%D7%B3%D9%88%D8%B1%D8%A8%D9%86"
                    target="_blank" rel="noopener noreferrer" class="d-flex align-items-center text-decoration-none">
                    <i class='bx bxl-wikipedia me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/cote-d-azure.wikipedia_link') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ url(app()->getLocale() . '/cities/nice') }}" target="_blank" rel="noopener noreferrer"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-map-alt me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/cote-d-azure.nice_city_guide') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('university_content')
    <h2 class="h3 fw-bold mb-4">{{ __('university/cote-d-azure.page_title') }}</h2>

    <div class="single-services-imgs mb-4">
        <img src="{{asset("assets/img/universities/Nice/nice_university.webp")}}"
            alt="{{ __('university/cote-d-azure.page_title') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <p class="lead text-muted mb-4">{{ __('university/cote-d-azure.intro_content') }}</p>

    <div class="map-container mb-5 rounded-4 overflow-hidden shadow-sm">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d46142.33925769736!2d7.2274!3d43.7009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12cddab6847890f7%3A0xc39955734a78103!2sUniversit%C3%A9%20C%C3%B4te%20d'Azur!5e0!3m2!1sfr!2sfr!4v1700000000000!5m2!1sfr!2sfr"
            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/cote-d-azure.global_leader_title') }}</h3>
        <p class="text-muted">{{ __('university/cote-d-azure.global_leader_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/cote-d-azure.programs_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/cote-d-azure.programs_content') }}</p>
        <ul class="list-group list-group-flush mb-4">
            @foreach(__('university/cote-d-azure.subjects') as $subject)
                <li class="list-group-item bg-transparent px-0 py-2 border-0 d-flex align-items-center">
                    <i class="bx bx-badge-check text-primary me-2"></i>
                    <span>{{ $subject }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <div class="rooms-details mb-5">
        <img src="{{asset("assets/img/universities/Nice/nice_university_1.webp")}}"
            alt="{{ __('university/cote-d-azure.page_title') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/cote-d-azure.research_title') }}</h3>
        <p class="text-muted">{{ __('university/cote-d-azure.research_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/cote-d-azure.admission_title') }}</h3>
        <p class="text-muted">{{ __('university/cote-d-azure.admission_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/cote-d-azure.career_title') }}</h3>
        <p class="text-muted">{{ __('university/cote-d-azure.career_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/cote-d-azure.environment_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/cote-d-azure.environment_content') }}</p>
        <div class="row g-3">
            @foreach(__('university/cote-d-azure.campuses') as $campus)
                <div class="col-md-6">
                    <div class="p-3 rounded-4 bg-light border-0 h-100 d-flex align-items-center">
                        <i class="bx bx-map-pin text-primary me-2 fs-5"></i>
                        <span class="small fw-medium">{{ $campus }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/cote-d-azure.conclusion_title') }}</h3>
        <p class="text-muted">{{ __('university/cote-d-azure.conclusion_content') }}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-primary-subtle border-0 mb-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <img src="{{asset("assets/img/universities/Nice/nice_logo.webp")}}"
                    alt="{{ __('university/cote-d-azure.page_title') }}" style="max-width: 150px;" class="img-fluid">
            </div>
            <div class="col-lg-8">
                <div class="row g-2">
                    @foreach(__('university/cote-d-azure.features') as $feature)
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
    <x-sections.faq :title="__('university/cote-d-azure.faq_title')" :subtitle="__('university/cote-d-azure.faq_subtitle')"
        :items="__('university/cote-d-azure.faq_items')" id="nice-faq" />
@endsection

@push("json")
    @php
        $currentLocale = app()->getLocale();
        $pageUrl = url($currentLocale . '/universities/cote-d-azure');
        $universityId = $pageUrl . '#university';
        $officialUrl = 'https://univ-cotedazur.eu/';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('university/cote-d-azure.main_heading'),
            __('university/cote-d-azure.description'),
            $currentLocale,
            $universityId,
            asset('assets/img/universities/Nice/cote_d_azure_logo.webp')
        );

        $university = new \App\Services\StructuredData\UniversitySchema(
            $universityId,
            __('university/cote-d-azure.breadcrumb_current'),
            $officialUrl,
            __('university/cote-d-azure.schema_description'),
            asset('assets/img/universities/Nice/cote_d_azure_logo.webp'),
            [
                $officialUrl,
                'https://fa.wikipedia.org/wiki/%D8%AF%D8%A7%D9%86%D8%B4%DA%AF%D8%A7%D9%87_%D9%BE%D8%A7%D9%86%D8%AA%D8%A6%D9%88%D9%86-%D7%B3%D9%88%D8%B1%D8%A8%D9%86',
            ]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('universities.breadcrumb_universities') ?? 'Universities', 'url' => url($currentLocale . '/universities')],
            ['name' => __('university/cote-d-azure.breadcrumb_current'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$university" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush