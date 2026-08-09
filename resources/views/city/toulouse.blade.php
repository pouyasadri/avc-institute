@extends('layouts.city')

@php
    $currentLocale = app()->getLocale();
    $cityName = 'toulouse';
@endphp

@section('title', __('city/toulouse.title'))
@section('keywords', __('city/toulouse.keywords'))
@section('description', __('city/toulouse.description'))

@section('header_class', 'bg-toulouse-city')
@section('breadcrumb_current', __('city/toulouse.breadcrumb_toulouse'))
@section('page_title_heading', __('city/toulouse.main_heading'))

@section('toc_title', __('city/toulouse.table_of_contents'))
@section('contact_title', __('city/toulouse.contact_us'))
@section('consultation_text', __('city/toulouse.consultation_request'))
@section('ask_question_title', __('city/toulouse.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">{{ __('city/toulouse.useful_links') }}</h4>
        <ul class="list-unstyled mb-0">
            <li>
                <a href="https://en.wikipedia.org/wiki/Toulouse" target="_blank"
                    class="d-flex align-items-center text-decoration-none">
                    <i class="bx bxl-internet-explorer me-2 fs-5 text-primary"></i>
                    <span>{{ __('city/toulouse.toulouse_wikipedia') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@push('json')
    @php
        $pageUrl = url($currentLocale . '/cities/toulouse');
        $cityId = $pageUrl . '#city';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('city/toulouse.main_heading'),
            __('city/toulouse.description'),
            $currentLocale,
            $cityId,
            asset('assets/img/cities/Toulouse/toulouse.webp')
        );

        $city = new \App\Services\StructuredData\CityGuideSchema(
            $cityId,
            __('city/toulouse.breadcrumb_toulouse'),
            __('city/toulouse.intro_paragraph'),
            asset('assets/img/cities/Toulouse/toulouse.webp'),
            ['https://en.wikipedia.org/wiki/Toulouse'],
            ['lat' => 43.6047, 'lng' => 1.4442]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('cities.breadcrumb_cities'), 'url' => url($currentLocale . '/cities')],
            ['name' => __('city/toulouse.breadcrumb_toulouse'), 'url' => $pageUrl],
        ]);
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$city" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush

@section('city_content')
    <section class="mb-5">
        <h2 class="h3 fw-bold mb-4">{{ __('city/toulouse.intro_heading') }}</h2>
        <div class="single-services-imgs mb-4">
            <img src="{{ asset('assets/img/cities/Toulouse/toulouse1.webp') }}"
                alt="{{ __('city/toulouse.breadcrumb_toulouse') }}" class="img-fluid rounded-4 shadow-sm w-100">
        </div>
        <p class="lead">{!! __('city/toulouse.intro_paragraph') !!}</p>
    </section>

    @if(is_array(__('city/toulouse.quick_facts')))
        <div class="card border-0 shadow-sm rounded-4 mb-5 bg-light">
            <div class="card-body p-4">
                <h3 class="h5 fw-bold text-primary mb-3 d-flex align-items-center">
                    <i class='bx bxs-check-shield me-2 fs-4'></i>
                    {{ __('city/toulouse.quick_facts_title') }}
                </h3>
                <div class="row g-3">
                    @foreach (__('city/toulouse.quick_facts') as $item)
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
        <h3 class="h4 fw-bold mb-3">{{ __('city/toulouse.student_life_heading') }}</h3>
        <p>{{ __('city/toulouse.student_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/toulouse.family_life_heading') }}</h3>
        <p>{{ __('city/toulouse.family_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/toulouse.lifestyle_heading') }}</h3>
        <p>{{ __('city/toulouse.lifestyle_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/toulouse.history_heading') }}</h3>
        <p>{{ __('city/toulouse.history_paragraph') }}</p>
    </section>

    <div class="rounded-4 overflow-hidden shadow-sm mb-5">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d184889.5585011091!2d1.4451994!3d43.6086372!3m2!i1024!2i768!4f13.1!3m3!1m2!1s0x12aebb6fec7552ff%3A0x406f69c2f411030!2sToulouse!5e0!3m2!1sfr!2sfr!4v1691147641647!5m2!1sfr!2sfr"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/toulouse.study_heading') }}</h3>
        <p>{{ __('city/toulouse.study_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/toulouse.universities_heading') }}</h3>
        <p>{{ __('city/toulouse.universities_intro') }}</p>
        <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item bg-transparent border-0 ps-0">
                <i class="bx bx-right-arrow-alt text-primary me-2"></i>
                <span>{{ __('city/toulouse.university_capitole') }}</span>
            </li>
            <li class="list-group-item bg-transparent border-0 ps-0">
                <i class="bx bx-right-arrow-alt text-primary me-2"></i>
                <span>{{ __('city/toulouse.university_jaures') }}</span>
            </li>
            <li class="list-group-item bg-transparent border-0 ps-0">
                <i class="bx bx-right-arrow-alt text-primary me-2"></i>
                <span>{{ __('city/toulouse.university_sabatier') }}</span>
            </li>
        </ul>
    </section>

    <div class="mb-5">
        <img src="{{ asset('assets/img/cities/Toulouse/toulouse2.webp') }}"
            alt="{{ __('city/toulouse.breadcrumb_toulouse') }}" class="img-fluid rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/toulouse.living_costs_heading') }}</h3>
        <p>{!! __('city/toulouse.living_costs_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/toulouse.climate_heading') }}</h3>
        <p>{{ __('city/toulouse.climate_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/toulouse.tourism_heading') }}</h3>
        <p>{{ __('city/toulouse.tourism_paragraph') }}</p>
        <ul class="list-group list-group-flush mb-4">
            @foreach (__('city/toulouse.tourism_items') as $item)
                <li class="list-group-item bg-transparent border-0 ps-0">
                    <i class="bx bx-check-circle text-primary me-2"></i>
                    {{ $item }}
                </li>
            @endforeach
        </ul>
        <p>{{ __('city/toulouse.tourism_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/toulouse.economy_heading') }}</h3>
        <p>{{ __('city/toulouse.economy_paragraph_1') }}</p>
        <ul class="list-group list-group-flush mb-4">
            @foreach (__('city/toulouse.economy_companies') as $company)
                <li class="list-group-item bg-transparent border-0 ps-0">
                    <i class="bx bx-buildings text-primary me-2"></i>
                    {{ $company }}
                </li>
            @endforeach
        </ul>
        <p>{{ __('city/toulouse.economy_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/toulouse.job_heading') }}</h3>
        <p>{{ __('city/toulouse.job_paragraph_1') }}</p>
        <p>{{ __('city/toulouse.job_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/toulouse.conclusion_heading') }}</h3>
        <p>{!! __('city/toulouse.conclusion_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-light border-0 mt-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <i class='bx bxs-city text-primary' style="font-size: 5rem;"></i>
                <h4 class="h5 fw-bold mt-3">{{ __('city/toulouse.breadcrumb_toulouse') }}</h4>
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/toulouse.student_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/toulouse.family_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/toulouse.lifestyle_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/toulouse.study_heading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cta-consult-banner my-5 py-4 px-4 px-md-5 rounded-5 shadow-sm text-center text-md-start bg-primary text-white position-relative overflow-hidden">
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <h3 class="h4 fw-bold text-white mb-2">{{ __('city/toulouse.cta_banner_title') }}</h3>
                <p class="mb-0 text-white-50 fs-6">{{ __('city/toulouse.cta_banner_subtitle') }}</p>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <a href="{{ url($currentLocale . '/consult') }}" class="btn btn-light btn-lg rounded-pill fw-bold px-4 py-3 text-primary shadow-sm">
                    <i class='bx bx-calendar-event me-2'></i>
                    {{ __('city/toulouse.cta_banner_button') }}
                </a>
            </div>
        </div>
    </div>

    <div class="my-5">
        <x-sections.faq :title="__('city/toulouse.faq_title')" :subtitle="__('city/toulouse.faq_subtitle')"
            :items="__('city/toulouse.faq_items')" id="toulouse-faq" />
    </div>
@endsection