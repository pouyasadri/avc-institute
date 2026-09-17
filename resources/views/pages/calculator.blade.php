@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
    $arrowIcon = $isRtl ? 'flaticon-left-arrow' : 'flaticon-right-arrow';

    $seoService = app(\App\Services\SeoService::class);
    $seoService->setTitle($pageTitle . ' - A.V.C Institute', false)
               ->setDescription($pageDescription)
               ->setLocale($locale);
@endphp

@section('title', $pageTitle . ' - A.V.C Institute')
@section('description', $pageDescription)

@push('styles')
<style>
    /* Calculator Page Design System Integration */
    .calculator-intro-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 50px;
        background: #f0f5ff;
        color: #0F3A80;
        border: 1px solid #d0e0ff;
    }

    /* Comparison Table Styling */
    .calc-table-wrap {
        border-radius: 16px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        position: relative;
    }
    .calc-table {
        min-width: 680px;
    }
    .calc-table thead th {
        background: #0F3A80;
        color: #ffffff;
        font-weight: 600;
        padding: 14px 16px;
        font-size: 0.88rem;
        border: none;
        vertical-align: middle;
        white-space: nowrap;
    }
    .calc-table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #edf2f7;
        font-size: 0.9rem;
        white-space: nowrap;
    }
    .calc-table tbody tr:last-child td {
        border-bottom: none;
    }
    .calc-table tbody tr:hover {
        background-color: #f8faff;
    }
    .calc-table tbody tr.table-selected-city {
        background-color: #fff9f5 !important;
        font-weight: 600;
    }

    .btn-table-action {
        color: #0F3A80;
        border: 1.5px solid #0F3A80;
        background: transparent;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 4px 14px;
        border-radius: 30px;
        transition: all 0.25s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-table-action:hover {
        background: #0F3A80;
        color: #ffffff;
    }

    /* Gradient Consultation Box in Main Content */
    .calc-cta-gradient-box {
        background: linear-gradient(135deg, #0F3A80 0%, #1d4b8f 100%);
        color: #ffffff;
        border-radius: 20px;
        padding: 32px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 0 10px 30px rgba(15, 58, 128, 0.12);
    }
    .calc-cta-gradient-box h3 {
        color: #ffffff;
        font-weight: 700;
    }
    .calc-cta-gradient-box p {
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.7;
    }
    .calc-cta-gradient-box .badge {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.35) !important;
    }

    /* Advisory Box */
    .calc-advisory-box {
        background: #fffdfa;
        border: 1px solid #fed7aa;
        border-{{ $isRtl ? 'right' : 'left' }}: 4px solid #f5a623;
        border-radius: {{ $isRtl ? '16px 0 0 16px' : '0 16px 16px 0' }};
        padding: 20px 24px;
    }

    /* High contrast default-btn in sidebar */
    .service-sidebar-widget .default-btn {
        background-color: #0F3A80 !important;
        color: #ffffff !important;
        border: none !important;
    }
    .service-sidebar-widget .default-btn:hover {
        background-color: #ff8c00 !important;
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
    <div>
        <!-- Start Page Title Area -->
        <header class="page-title-area" role="banner">
            <div class="container">
                <div class="page-title-content">
                    <x-premium-breadcrumb :items="[
                        ['url' => url($locale . '/'), 'label' => __('consult.breadcrumb_home') ?? 'Home'],
                        ['label' => $pageTitle]
                    ]" />
                    <h1>{{ $pageTitle }}</h1>
                </div>
            </div>
        </header>
        <!-- End Page Title Area -->

        <!-- Start Calculator & Content Area -->
        <section class="service-details-area ptb-100">
            <div class="container">
                <div class="row">
                    <!-- Main Content (8 Cols) -->
                    <div class="col-lg-8 col-md-12">
                        <div class="service-details-desc">

                            <!-- Intro Card -->
                            <div class="rounded-4 p-4 bg-light shadow-sm mb-4 border">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                    <span class="calculator-intro-badge">
                                        <i class="bx bxs-calculator fs-5 text-primary"></i>
                                        {{ __('calculator.badge') }}
                                    </span>
                                    <span class="badge bg-white text-secondary border rounded-pill py-1 px-3 small fw-semibold">
                                        <i class="bx bx-check-shield text-success"></i>
                                        {{ $locale === 'fa' ? 'محاسبات مصوب ۲۰۲۶' : 'Official 2026 Formulas' }}
                                    </span>
                                </div>
                                <h2 class="h4 fw-bold text-dark mb-2">{{ $pageTitle }}</h2>
                                <p class="text-muted mb-0" style="line-height: 1.8;">
                                    {{ $pageDescription }}
                                </p>
                            </div>

                            <!-- Interactive Student Budget Calculator -->
                            <div class="calculator-page-area mb-5">
                                <x-calculator.student-budget :initialCity="$initialCity ?? 'paris'" />
                            </div>

                            <!-- City Benchmarks Comparison Table Section -->
                            <div class="calculator-guide-area mt-5 pt-3">
                                <div class="mb-4">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold small mb-2">
                                        <i class="bx bx-bar-chart-alt-2 align-middle me-1"></i>
                                        {{ $locale === 'fa' ? 'داده‌های واقعی شهرهای فرانسه ۲۰۲۶' : 'French Academic Hubs Benchmarks 2026' }}
                                    </span>
                                    <h3 class="h4 fw-bold text-dark mt-2 mb-2">
                                        {{ __('calculator.table_heading') }}
                                    </h3>
                                    <p class="text-muted small mb-0" style="line-height: 1.7;">
                                        {{ $locale === 'fa' 
                                            ? 'مقایسه شفاف میانگین اجاره خوابگاه دولتی کروس، هم‌خانگی و استودیو شخصی به همراه سوبسید مسکن CAF و حداقل تمکن پیشنهادی به تفکیک شهرهای پرتقاضا.'
                                            : 'Real-world cost benchmarks across major student cities in France, including CROUS dorms, shared apartments, private studios, and monthly CAF housing subsidies.' }}
                                    </p>
                                    <div class="d-md-none text-muted small mt-2 d-flex align-items-center gap-1">
                                        <i class="bx bx-transfer-alt text-primary"></i>
                                        <span>{{ $locale === 'fa' ? 'برای مشاهده کامل جدول به چپ و راست بکشید' : 'Swipe horizontally to view full table' }}</span>
                                    </div>
                                </div>

                                <div class="table-responsive calc-table-wrap shadow-sm mb-4">
                                    <table class="table calc-table align-middle mb-0" style="font-size: 0.9rem;">
                                        <thead>
                                            <tr>
                                                <th class="py-3 px-3 px-md-4">{{ __('calculator.table_city') }}</th>
                                                <th class="py-3 px-2 text-center">{{ __('calculator.table_crous') }}</th>
                                                <th class="py-3 px-2 text-center">{{ __('calculator.table_colocation') }}</th>
                                                <th class="py-3 px-2 text-center">{{ __('calculator.table_studio') }}</th>
                                                <th class="py-3 px-2 text-center" style="background:#0c2e66;">{{ __('calculator.table_caf') }}</th>
                                                <th class="py-3 px-3 text-center">{{ __('calculator.table_action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $citiesList = $availableCities ?? config('calculator.cities', []);
                                                $cafAllowances = config('calculator.caf_allowances', []);
                                            @endphp
                                            @foreach($citiesList as $key => $city)
                                                @php
                                                    $cityName = match($locale) {
                                                        'fa' => $city['name_fa'] ?? $key,
                                                        'fr' => $city['name_fr'] ?? $key,
                                                        default => $city['name_en'] ?? $key,
                                                    };
                                                    $isSelected = strtolower($initialCity ?? '') === strtolower($key);
                                                @endphp
                                                <tr class="{{ $isSelected ? 'table-selected-city' : '' }}">
                                                    <td class="py-3 px-3 px-md-4">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="bx bxs-map text-primary fs-5"></i>
                                                            <strong class="text-dark">{{ $cityName }}</strong>
                                                            @if($isSelected)
                                                                <span class="badge bg-warning text-dark small rounded-pill py-0.5 px-2">{{ $locale === 'fa' ? 'انتخاب فعلی' : 'Selected' }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="py-3 px-2 text-center text-muted">
                                                        {{ $city['rents']['crous'] ?? 250 }} €
                                                    </td>
                                                    <td class="py-3 px-2 text-center fw-bold text-dark">
                                                        {{ $city['rents']['colocation'] ?? 390 }} €
                                                    </td>
                                                    <td class="py-3 px-2 text-center text-muted">
                                                        {{ $city['rents']['private_studio'] ?? 500 }} €
                                                    </td>
                                                    <td class="py-3 px-2 text-center text-success fw-bold">
                                                        +{{ $cafAllowances['colocation'] ?? 180 }} €
                                                    </td>
                                                    <td class="py-3 px-3 text-center">
                                                        <a href="{{ route('calculator', ['locale' => $locale, 'city' => $key]) }}" 
                                                           class="btn-table-action">
                                                            <span>{{ __('calculator.table_action') }}</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Advisory & Legal Disclosure Box -->
                            <div class="calc-advisory-box shadow-sm my-5">
                                <p class="mb-1 fw-bold text-dark d-flex align-items-center gap-2">
                                    <i class="bx bx-shield-quarter fs-5 text-warning"></i>
                                    <span>{{ __('calculator.advisory_title') }}</span>
                                </p>
                                <p class="mb-0 small text-muted" style="line-height: 1.7;">{{ __('calculator.advisory_text') }}</p>
                            </div>

                            <!-- Mid-content Contextual CTA Banner matching services and legal pages -->
                            <div class="calc-cta-gradient-box my-5">
                                <div class="row align-items-center g-4">
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="bx bx-check-shield text-warning fs-3"></i>
                                            <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 small fw-bold">
                                                {{ $locale === 'fa' ? 'ارزیابی تخصصی پرونده' : 'Personalized Evaluation' }}
                                            </span>
                                        </div>
                                        <h3 class="h4 mb-2">{{ __('calculator.cta_title') }}</h3>
                                        <p class="small mb-0">{{ __('calculator.cta_desc') }}</p>
                                    </div>
                                    <div class="col-lg-4 text-lg-end text-center">
                                        <a href="{{ url($locale . '/consult?service=student-visa') }}" class="btn btn-light rounded-pill px-4 py-3 fw-bold text-primary shadow-sm w-100 w-lg-auto">
                                            <span>{{ __('calculator.cta_button') }}</span>
                                            <i class="{{ $arrowIcon }} ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Interactive FAQ Section -->
                            @if(!empty($faqs))
                                <div class="mt-5 pt-3">
                                    <x-sections.faq 
                                        :items="$faqs" 
                                        :title="__('calculator.faq_heading')" 
                                        id="calculator-faqs-accordion" 
                                        :inline="true" 
                                    />
                                </div>
                            @endif

                        </div>
                    </div>

                    <!-- Sidebar Column (4 Cols) -->
                    <div class="col-lg-4 col-md-12">
                        <!-- Sidebar Contact / Consultation Widget -->
                        <div class="service-sidebar-widget rounded-4 p-4 bg-light-subtle shadow-sm mb-4">
                            <h3 class="h5 fw-bold mb-3">{{ __('index.video.button') ?? 'Contact Us' }}</h3>
                            <p class="text-muted small mb-4">{{ __('index.video.p2') ?? 'Contact us for more details.' }}</p>
                            <a href="{{ url($locale . '/consult?service=student-visa') }}" class="default-btn w-100 text-center">
                                {{ __('index.video.button') ?? 'Book Consultation' }}
                                <i class="{{ $arrowIcon }}"></i>
                            </a>
                        </div>

                        <!-- Sidebar Related Services Widget -->
                        <div class="service-sidebar-widget rounded-4 p-4 bg-white shadow-sm mb-4">
                            <h3 class="h5 fw-bold mb-3">{{ __('services.other_services') ?? 'Related Services' }}</h3>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <a href="{{ route('services.show', ['locale' => $locale, 'slug' => 'student-visa']) }}" 
                                       class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('services.student-visa.title') ?? 'Student Visa France' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('services.show', ['locale' => $locale, 'slug' => 'university-application']) }}" 
                                       class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('services.university-application.title') ?? 'University Application' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('services.show', ['locale' => $locale, 'slug' => 'housing-assistance']) }}" 
                                       class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('services.housing-assistance.title') ?? 'Housing & CAF Assistance' }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('services.show', ['locale' => $locale, 'slug' => 'arrival-support']) }}" 
                                       class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('services.arrival-support.title') ?? 'Arrival Support in France' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Sidebar Guides & Exploration Widget -->
                        <div class="service-sidebar-widget rounded-4 p-4 bg-white shadow-sm">
                            <h3 class="h5 fw-bold mb-3">{{ __('services.more_learning') ?? 'Explore Guides' }}</h3>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <a href="{{ route('universities.index', ['locale' => $locale]) }}" class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('layout.footer.links.universities') ?? 'Universities in France' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('cities.index', ['locale' => $locale]) }}" class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('layout.footer.links.cities') ?? 'City Living Guides' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('blog.index', ['locale' => $locale]) }}" class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('services.related_blog') ?? 'Immigration & Visa Blog' }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url($locale . '/contactUs') }}" class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('layout.footer.links.contact') ?? 'Contact Us' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- End Calculator & Content Area -->
    </div>
@endsection

@push('json')
    @php
        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('consult.breadcrumb_home') ?? 'Home', 'url' => url($locale . '/')],
            ['name' => $pageTitle, 'url' => request()->url()],
        ]);
    @endphp

    @if(isset($schema))
        <x-seo.structured-data :schema="$schema" />
    @endif
    @if(isset($faqSchema))
        <x-seo.structured-data :schema="$faqSchema" />
    @endif
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush


