@extends('layouts.university')

@php
    $universityName = 'toulouse';
@endphp

@section('title', __('university/toulouse.title'))
@section('keywords', __('university/toulouse.keywords'))
@section('description', __('university/toulouse.description'))

@section('header_class', 'bg-toulouse')
@section('breadcrumb_current', __('university/toulouse.breadcrumb_current'))
@section('page_title_heading', __('university/toulouse.main_heading'))

@section('toc', true)
@section('toc_title', __('university/toulouse.table_of_contents'))
@section('contact_title', __('university/toulouse.contact_us'))
@section('consultation_text', __('university/toulouse.consultation_request'))
@section('ask_question_title', __('university/toulouse.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
            {{ __('university/toulouse.useful_links') }}
        </h4>
        <ul class="list-unstyled mb-0">
            <li class="mb-2">
                <a href="https://www.univ-toulouse.fr/" target="_blank" rel="noopener noreferrer"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-globe me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/toulouse.official_website') }}</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="https://fa.wikipedia.org/wiki/%D8%AF%D8%A7%D9%86%D8%B4%DA%AF%D8%A7%D9%87_%D8%AA%D9%88%D9%84%D9%88%D8%B2"
                    target="_blank" rel="noopener noreferrer" class="d-flex align-items-center text-decoration-none">
                    <i class='bx bxl-wikipedia me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/toulouse.wikipedia_link') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ url(app()->getLocale() . '/cities/toulouse') }}" target="_blank" rel="noopener noreferrer"
                    class="d-flex align-items-center text-decoration-none">
                    <i class='bx bx-map-alt me-2 fs-5 text-primary'></i>
                    <span>{{ __('university/toulouse.toulouse_city_guide') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('university_content')
    <h2 class="h3 fw-bold mb-4">{{ __('university/toulouse.page_title') }}</h2>

    <div class="single-services-imgs mb-4">
        <img src="{{asset("assets/img/universities/Toulouse/toulouse_university.webp")}}"
            alt="{{ __('university/toulouse.page_title') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <p class="lead text-muted mb-4">{{ __('university/toulouse.intro_content') }}</p>

    <div class="map-container mb-5 rounded-4 overflow-hidden shadow-sm">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d184924.66022423725!2d1.4668153!3d43.5972167!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12aebb61a0b5834d%3A0xedaa16152a9f760b!2sUniversit%C3%A9%20de%20Toulouse!5e0!3m2!1sfr!2sfr!4v1700000000000!5m2!1sfr!2sfr"
            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/toulouse.global_leader_title') }}</h3>
        <p class="text-muted">{{ __('university/toulouse.global_leader_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/toulouse.programs_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/toulouse.programs_content') }}</p>
        <ul class="list-group list-group-flush mb-4">
            @foreach(__('university/toulouse.subjects') as $subject)
                <li class="list-group-item bg-transparent px-0 py-2 border-0 d-flex align-items-center">
                    <i class="bx bx-badge-check text-primary me-2"></i>
                    <span>{{ $subject }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <div class="rooms-details mb-5">
        <img src="{{asset("assets/img/universities/Toulouse/toulouse_university_1.webp")}}"
            alt="{{ __('university/toulouse.page_title') }}" class="rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/toulouse.research_title') }}</h3>
        <p class="text-muted">{{ __('university/toulouse.research_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/toulouse.admission_title') }}</h3>
        <p class="text-muted">{{ __('university/toulouse.admission_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/toulouse.career_title') }}</h3>
        <p class="text-muted">{{ __('university/toulouse.career_content') }}</p>
    </section>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('university/toulouse.environment_title') }}</h3>
        <p class="text-muted mb-3">{{ __('university/toulouse.environment_content') }}</p>
        <div class="row g-3">
            @foreach(__('university/toulouse.campuses') as $campus)
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
        <h3 class="h4 fw-bold mb-3">{{ __('university/toulouse.conclusion_title') }}</h3>
        <p class="text-muted">{{ __('university/toulouse.conclusion_content') }}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-primary-subtle border-0 mb-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <img src="{{asset("assets/img/universities/Toulouse/toulouse_logo.webp")}}"
                    alt="{{ __('university/toulouse.page_title') }}" style="max-width: 150px;" class="img-fluid">
            </div>
            <div class="col-lg-8">
                <div class="row g-2">
                    @foreach(__('university/toulouse.features') as $feature)
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
    <x-sections.faq :title="__('university/toulouse.faq_title')" :subtitle="__('university/toulouse.faq_subtitle')"
        :items="__('university/toulouse.faq_items')" id="toulouse-faq" />
@endsection

@push("json")
    @php
        $currentLocale = app()->getLocale();
        $pageUrl = url($currentLocale . '/universities/toulouse');
        $universityId = $pageUrl . '#university';
        $officialUrl = 'https://www.univ-toulouse.fr/';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('university/toulouse.main_heading'),
            __('university/toulouse.description'),
            $currentLocale,
            $universityId,
            asset('assets/img/universities/Toulouse/toulouse_logo.webp')
        );

        $university = new \App\Services\StructuredData\UniversitySchema(
            $universityId,
            __('university/toulouse.breadcrumb_current'),
            $officialUrl,
            __('university/toulouse.schema_description'),
            asset('assets/img/universities/Toulouse/toulouse_logo.webp'),
            [
                $officialUrl,
                'https://fa.wikipedia.org/wiki/%D8%AF%D8%A7%D9%86%D8%B4%DA%AF%D8%A7%D9%87_%D8%AA%D9%88%D9%84%D9%88%D8%B2',
            ]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('universities.breadcrumb_universities') ?? 'Universities', 'url' => url($currentLocale . '/universities')],
            ['name' => __('university/toulouse.breadcrumb_current'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$university" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush