@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
    $arrowIcon = $isRtl ? 'flaticon-left-arrow' : 'flaticon-right-arrow';

    // Metadata for SEO
    $pageTitle = __('consult.title');
    $pageKeywords = __('consult.keywords');
    $pageDescription = __('consult.description');
@endphp

@section('title', $pageTitle)
@section('keywords', $pageKeywords)
@section('description', $pageDescription)

@section('content')
    <div>
        <!-- Start Page Title Area -->
        <header class="page-title-area" role="banner">
            <div class="container">
                <div class="page-title-content">
                    <x-premium-breadcrumb :items="[
            ['url' => url($currentLocale . '/'), 'label' => __('consult.breadcrumb_home')],
            ['label' => __('consult.breadcrumb_consult')]
        ]" />
                    <h1>{{ __('consult.main_heading') }}</h1>
                </div>
            </div>
        </header>
        <!-- End Page Title Area -->

        {{-- Trust Signal Bar — targeting "موسسه مهاجرتی فرانسه" and "مشاوره مهاجرت تحصیلی" keywords --}}
        <section class="py-4 bg-primary text-white" id="consult-trust-bar">
            <div class="container">
                <div class="row g-3 text-center justify-content-center">
                    <div class="col-6 col-md-3">
                        <div class="px-3">
                            <div class="display-6 fw-bold">{{ __('consult.stats_years_value') }}</div>
                            <div class="small opacity-75">{{ __('consult.stats_years_label') }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="px-3">
                            <div class="display-6 fw-bold">{{ __('consult.stats_success_value') }}</div>
                            <div class="small opacity-75">{{ __('consult.stats_success_label') }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="px-3">
                            <div class="display-6 fw-bold">{{ __('consult.stats_languages_value') }}</div>
                            <div class="small opacity-75">{{ __('consult.stats_languages_label') }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="px-3">
                            <div class="display-6 fw-bold">{{ __('consult.stats_price_value') }}</div>
                            <div class="small opacity-75">{{ __('consult.stats_price_label') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <x-sections.features :subtitle="__('consult.facilities_section_title')"
            :title="__('consult.facilities_section_heading')" :items="[
            ['icon' => 'globe', 'label' => __('consult.residence_france_subtitle'), 'title' => __('consult.residence_france_title'), 'description' => __('consult.residence_france_description')],
            ['icon' => 'book', 'label' => __('consult.educational_consultation_subtitle'), 'title' => __('consult.educational_consultation_title'), 'description' => __('consult.educational_consultation_desc')],
            ['icon' => 'home', 'label' => __('consult.dormitory_booking_subtitle'), 'title' => __('consult.dormitory_booking_title'), 'description' => __('consult.dormitory_booking_description')],
            ['icon' => 'chat', 'label' => __('consult.immigration_psychology_subtitle'), 'title' => __('consult.immigration_psychology_title'), 'description' => __('consult.immigration_psychology_description')],
        ]" />

        <!-- Start Consultation Request Area -->
        <section class="book-table-area-three pt-70 pb-100">
            <div class="container">
                <div class="row g-5 align-items-stretch">
                    <!-- Consultation Form -->
                    <div class="col-lg-7">
                        <div class="contact-wrap rounded-5 shadow-sm p-4 p-md-5 bg-white border-0 transition-all">
                            <div class="contact-form">
                                <h3 class="fw-bold mb-4">{{ __('consult.consultation_form_heading') }}</h3>
                                <form action="{{ route('consult.submit') }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="user_name"
                                                    class="form-label small fw-bold text-muted">{{ __('consult.form.name.label' ?? 'Full Name') }}</label>
                                                <input class="form-control rounded-pill px-4 py-2"
                                                    placeholder="{{ __('consult.form_name_placeholder') }}" type="text"
                                                    name="user_name" id="user_name" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="user_email"
                                                    class="form-label small fw-bold text-muted">{{ __('consult.form.email.label' ?? 'Email Address') }}</label>
                                                <input type="email" name="user_email" id="user_email"
                                                    class="form-control rounded-pill px-4 py-2"
                                                    placeholder="{{ __('consult.form_email_placeholder') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="user_phone_number"
                                                    class="form-label small fw-bold text-muted">{{ __('consult.form.whatsapp.label' ?? 'WhatsApp/Phone') }}</label>
                                                <input type="tel" name="user_phone_number" id="user_phone_number"
                                                    class="form-control rounded-pill px-4 py-2"
                                                    placeholder="{{ __('consult.form_whatsapp_placeholder') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="user_service"
                                                    class="form-label small fw-bold text-muted">{{ __('consult.form.service.label' ?? 'Service Needed') }}</label>
                                                @php
                                                    $paramService = request('service');
                                                    $serviceMapping = [
                                                        'residence-permit' => __('consult.form_service_residence'),
                                                        'student-visa' => __('consult.form_service_immigration'),
                                                        'investment-visa' => __('consult.form_service_immigration'),
                                                        'educational-counseling' => __('consult.form_service_education'),
                                                        'university-application' => __('consult.form_service_education'),
                                                        'resume-lettre-motivation' => __('consult.form_service_education'),
                                                        'housing-assistance' => __('consult.form_service_immigration'),
                                                        'arrival-support' => __('consult.form_service_immigration'),
                                                        'certified-translation' => __('consult.form_service_legal'),
                                                        'administrative-advocacy' => __('consult.form_service_legal'),
                                                        'legal-support' => __('consult.form_service_legal'),
                                                    ];
                                                    $preselected = $serviceMapping[$paramService] ?? (in_array($paramService, [
                                                        __('consult.form_service_residence'),
                                                        __('consult.form_service_education'),
                                                        __('consult.form_service_immigration'),
                                                        __('consult.form_service_legal')
                                                    ], true) ? $paramService : null);
                                                @endphp
                                                <select name="user_service" id="user_service"
                                                    class="form-control rounded-pill px-4 py-2">
                                                    <option value="default" {{ !$preselected ? 'selected' : '' }}>{{ __('consult.form_service_default') }}
                                                    </option>
                                                    <option value="{{ __('consult.form_service_residence') }}" {{ $preselected === __('consult.form_service_residence') ? 'selected' : '' }}>
                                                        {{ __('consult.form_service_residence') }}
                                                    </option>
                                                    <option value="{{ __('consult.form_service_education') }}" {{ $preselected === __('consult.form_service_education') ? 'selected' : '' }}>
                                                        {{ __('consult.form_service_education') }}
                                                    </option>
                                                    <option value="{{ __('consult.form_service_immigration') }}" {{ $preselected === __('consult.form_service_immigration') ? 'selected' : '' }}>
                                                        {{ __('consult.form_service_immigration') }}
                                                    </option>
                                                    <option value="{{ __('consult.form_service_legal') }}" {{ $preselected === __('consult.form_service_legal') ? 'selected' : '' }}>
                                                        {{ __('consult.form_service_legal') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group mb-4">
                                                <label for="user_details"
                                                    class="form-label small fw-bold text-muted">{{ __('consult.form.details.label' ?? 'Details') }}</label>
                                                <textarea class="form-control rounded-4 px-4 py-3" name="user_details"
                                                    id="user_details" cols="30" rows="5"
                                                    placeholder="{{ __('consult.form_details_placeholder') }}"
                                                    required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="default-btn rounded-pill px-5 transition-all">
                                                {{ __('consult.form_submit_button') }}
                                                <i class="{{ $arrowIcon }}"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Sidebar -->
                    <div class="col-lg-5">
                        <div class="row g-4 h-100">
                            <!-- Email Info -->
                            <div class="col-12">
                                <div
                                    class="single-contact-info rounded-5 shadow-sm p-4 bg-white border-0 transition-all hover-lift">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-pill p-3 me-3">
                                            <i class='bx bx-envelope fs-3'></i>
                                        </div>
                                        <h3 class="h5 fw-bold mb-0">{{ __('consult.info.email.title' ?? 'Our Email') }}</h3>
                                    </div>
                                    <a href="mailto:info@applyvipconseil.com"
                                        class="text-muted text-decoration-none">info@applyvipconseil.com</a>
                                </div>
                            </div>
                            <!-- Phone France -->
                            <div class="col-12">
                                <div
                                    class="single-contact-info rounded-5 shadow-sm p-4 bg-white border-0 transition-all hover-lift">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-pill p-3 me-3">
                                            <i class='bx bx-phone-call fs-3'></i>
                                        </div>
                                        <h3 class="h5 fw-bold mb-0">{{ __('consult.info.phone.france_label' ?? 'France') }}
                                        </h3>
                                    </div>
                                    <a href="tel:+33768688326" class="text-muted text-decoration-none"><span dir="ltr">+33 7 68 68 83 26</span></a>
                                </div>
                            </div>
                            <!-- Phone Iran -->
                            <div class="col-12">
                                <div
                                    class="single-contact-info rounded-5 shadow-sm p-4 bg-white border-0 transition-all hover-lift">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 bg-success-subtle text-success rounded-pill p-3 me-3">
                                            <i class='bx bx-phone-call fs-3'></i>
                                        </div>
                                        <h3 class="h5 fw-bold mb-0">{{ __('consult.info.phone.iran_label' ?? 'Iran') }}</h3>
                                    </div>
                                    <a href="tel:+989120087194"
                                        class="text-muted text-decoration-none"><span dir="ltr">+98 912 008 7194</span></a>
                                </div>
                            </div>
                            <!-- Description (Optional/Bottom) -->
                            <div class="col-12">
                                <div class="p-4 rounded-5 bg-light-subtle">
                                    <h4 class="h6 fw-bold mb-2">{{ __('consult.contact_info_heading') }}</h4>
                                    <p class="text-muted small mb-0">{{ __('consult.contact_info_description') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <x-sections.services :subtitle="__('index.services.subtitle')"
            :title="__('index.services.title')"
            :items="__('index.services.items')" />

        <x-sections.faq :title="__('faq.title')" :subtitle="__('faq.subtitle')" :items="__('faq.items')" />
    </div>
@endsection

@push('json')
    @php
        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('consult.breadcrumb_home'), 'url' => url($currentLocale . '/')],
            ['name' => __('consult.breadcrumb_consult'), 'url' => url($currentLocale . '/consult')],
        ]);

        $webPageSchema = new \App\Services\StructuredData\WebPageSchema(
            url()->current(),
            $pageTitle,
            $pageDescription,
            $currentLocale
        );

        $serviceSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $pageTitle,
            'description' => $pageDescription,
            'provider' => ['@id' => rtrim(config('app.url'), '/') . '/' . '#organization'],
            'areaServed' => ['FR', 'IR'],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Immigration Services',
                'itemListElement' => [
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => __('consult.residence_consultation'),
                        ],
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => __('consult.educational_consultation'),
                        ],
                    ],
                ],
            ],
        ];
    @endphp

    <x-seo.structured-data :schema="$webPageSchema" />
    <x-seo.structured-data :schema="$breadcrumb" />
    <x-seo.structured-data :schema="$serviceSchema" />
@endpush