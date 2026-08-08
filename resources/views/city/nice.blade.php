@extends('layouts.city')

@php
    $currentLocale = app()->getLocale();
    $cityName = 'nice';
@endphp

@section('title', __('city/nice.title'))
@section('keywords', __('city/nice.keywords'))
@section('description', __('city/nice.description'))

@section('header_class', 'bg-nice-city')
@section('breadcrumb_current', __('city/nice.breadcrumb_nice'))
@section('page_title_heading', __('city/nice.main_heading'))

@section('toc_title', __('city/nice.table_of_contents'))
@section('contact_title', __('city/nice.contact_us'))
@section('consultation_text', __('city/nice.consultation_request'))
@section('ask_question_title', __('city/nice.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">{{ __('city/nice.useful_links') }}</h4>
        <ul class="list-unstyled mb-0">
            <li>
                <a href="https://en.wikipedia.org/wiki/Nice" target="_blank"
                    class="d-flex align-items-center text-decoration-none">
                    <i class="bx bxl-internet-explorer me-2 fs-5 text-primary"></i>
                    <span>{{ __('city/nice.nice_wikipedia') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@push('json')
    @php
        $pageUrl = url($currentLocale . '/cities/nice');
        $cityId = $pageUrl . '#city';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('city/nice.main_heading'),
            __('city/nice.description'),
            $currentLocale,
            $cityId,
            asset('assets/img/cities/Nice/nice3.webp')
        );

        $city = new \App\Services\StructuredData\CityGuideSchema(
            $cityId,
            __('city/nice.breadcrumb_nice'),
            __('city/nice.intro_paragraph'),
            asset('assets/img/cities/Nice/nice3.webp'),
            ['https://en.wikipedia.org/wiki/Nice'],
            ['lat' => 43.7009, 'lng' => 7.2684]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('cities.breadcrumb_cities'), 'url' => url($currentLocale . '/cities')],
            ['name' => __('city/nice.breadcrumb_nice'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$city" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush




@section('city_content')
    <section class="mb-5">
        <h2 class="h3 fw-bold mb-4">{{ __('city/nice.intro_heading') }}</h2>
        <div class="single-services-imgs mb-4">
            <img src="{{ asset('assets/img/cities/Nice/nice1.webp') }}" alt="{{ __('city/nice.breadcrumb_nice') }}"
                class="img-fluid rounded-4 shadow-sm w-100">
        </div>
        <p class="lead">{!! __('city/nice.intro_paragraph') !!}</p>
    </section>

    @if(is_array(__('city/nice.quick_facts')))
        <div class="card border-0 shadow-sm rounded-4 mb-5 bg-light">
            <div class="card-body p-4">
                <h3 class="h5 fw-bold text-primary mb-3 d-flex align-items-center">
                    <i class='bx bxs-check-shield me-2 fs-4'></i>
                    {{ __('city/nice.quick_facts_title') }}
                </h3>
                <div class="row g-3">
                    @foreach (__('city/nice.quick_facts') as $item)
                        @if(is_array($item) && isset($item['label'], $item['value']))
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded-3 shadow-xs border h-100">
                                    <div class="text-muted small mb-1 fw-semibold">{{ $item['label'] }}</div>
                                    <div class="fw-bold text-dark fs-6">{{ $item['value'] }}</div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/nice.student_life_heading') }}</h3>
        <p>{{ __('city/nice.student_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/nice.family_life_heading') }}</h3>
        <p>{{ __('city/nice.family_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/nice.lifestyle_heading') }}</h3>
        <p>{{ __('city/nice.lifestyle_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/nice.history_heading') }}</h3>
        <p>{{ __('city/nice.history_paragraph') }}</p>
    </section>

    <div class="rounded-4 overflow-hidden shadow-sm mb-5">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d11534.613317424905!2d7.2619532!3d43.7032932!3m2!i1024!2i768!4f13.1!3m3!1m2!1s0x12cdd0106a852d31%3A0x40819a5fd979a70!2sNice!5e0!3m2!1sfr!2sfr!4v1691147427806!5m2!1sfr!2sfr"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/nice.study_heading') }}</h3>
        <p>{{ __('city/nice.study_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/nice.universities_heading') }}</h3>
        <p>{{ __('city/nice.universities_intro') }}</p>
        <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item bg-transparent border-0 ps-0">
                <i class="bx bx-right-arrow-alt text-primary me-2"></i>
                <a href="{{ url($currentLocale . '/universities/cote-d-azure') }}">{{ __('city/nice.university_nice') }}</a>
            </li>
            <li class="list-group-item bg-transparent border-0 ps-0">
                <i class="bx bx-right-arrow-alt text-primary me-2"></i>
                <span>{{ __('city/nice.university_skema') }}</span>
            </li>
            <li class="list-group-item bg-transparent border-0 ps-0">
                <i class="bx bx-right-arrow-alt text-primary me-2"></i>
                <span>{{ __('city/nice.university_edhec') }}</span>
            </li>
        </ul>
    </section>

    <div class="mb-5">
        <img src="{{ asset('assets/img/cities/Nice/nice.webp') }}" alt="{{ __('city/nice.breadcrumb_nice') }}"
            class="img-fluid rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/nice.living_costs_heading') }}</h3>
        <p>{!! __('city/nice.living_costs_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/nice.climate_heading') }}</h3>
        <p>{{ __('city/nice.climate_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/nice.tourism_heading') }}</h3>
        <p>{{ __('city/nice.tourism_paragraph') }}</p>
        <ul class="list-group list-group-flush mb-4">
            @foreach (__('city/nice.tourism_items') as $item)
                <li class="list-group-item bg-transparent border-0 ps-0">
                    <i class="bx bx-check-circle text-primary me-2"></i>
                    {{ $item }}
                </li>
            @endforeach
        </ul>
        <p>{{ __('city/nice.tourism_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/nice.economy_heading') }}</h3>
        <p>{{ __('city/nice.economy_paragraph_1') }}</p>
        <ul class="list-group list-group-flush mb-4">
            @foreach (__('city/nice.economy_companies') as $company)
                <li class="list-group-item bg-transparent border-0 ps-0">
                    <i class="bx bx-buildings text-primary me-2"></i>
                    {{ $company }}
                </li>
            @endforeach
        </ul>
        <p>{{ __('city/nice.economy_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/nice.job_heading') }}</h3>
        <p>{{ __('city/nice.job_paragraph_1') }}</p>
        <p>{{ __('city/nice.job_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/nice.conclusion_heading') }}</h3>
        <p>{!! __('city/nice.conclusion_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-light border-0 mt-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <i class='bx bxs-city text-primary' style="font-size: 5rem;"></i>
                <h4 class="h5 fw-bold mt-3">{{ __('city/nice.breadcrumb_nice') }}</h4>
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/nice.student_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/nice.family_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/nice.lifestyle_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/nice.study_heading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cta-consult-banner my-5 py-4 px-4 px-md-5 rounded-5 shadow-sm text-center text-md-start bg-primary text-white position-relative overflow-hidden">
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <h3 class="h4 fw-bold text-white mb-2">{{ __('city/nice.cta_banner_title') }}</h3>
                <p class="mb-0 text-white-50 fs-6">{{ __('city/nice.cta_banner_subtitle') }}</p>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <a href="{{ url($currentLocale . '/consult') }}" class="btn btn-light btn-lg rounded-pill fw-bold px-4 py-3 text-primary shadow-sm">
                    <i class='bx bx-calendar-event me-2'></i>
                    {{ __('city/nice.cta_banner_button') }}
                </a>
            </div>
        </div>
    </div>

    <div class="my-5">
        <x-sections.faq :title="__('city/nice.faq_title')" :subtitle="__('city/nice.faq_subtitle')"
            :items="__('city/nice.faq_items')" id="nice-faq" />
    </div>
@endsection
