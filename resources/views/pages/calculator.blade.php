@extends('layouts.main')

@php
    $arrowIcon = $isRtl ? 'flaticon-left-arrow' : 'flaticon-right-arrow';

    $seoService = app(\App\Services\SeoService::class);
    $seoService->setTitle($pageTitle . ' - A.V.C Institute', false)
               ->setDescription($pageDescription)
               ->setLocale($locale);
@endphp

@section('title', $pageTitle . ' - A.V.C Institute')
@section('description', $pageDescription)

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

        <!-- Start Dedicated Calculator Section -->
        <section class="calculator-page-area ptb-70 bg-light-subtle">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-11">
                        {{-- Interactive Student Budget & Visa Proof Calculator Component --}}
                        <x-calculator.student-budget :initialCity="$initialCity ?? 'paris'" />
                    </div>
                </div>
            </div>
        </section>
        <!-- End Dedicated Calculator Section -->

        <!-- Start City Comparison Table & Explanatory Guide Section (Crawlable SEO & AI Agents) -->
        <section class="calculator-guide-area py-5 bg-white border-top border-bottom">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-11">
                        
                        {{-- Section Header --}}
                        <div class="text-center mb-5">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold small mb-2">
                                <i class="bx bx-bar-chart-alt-2 align-middle me-1"></i>
                                {{ $locale === 'fa' ? 'داده‌های واقعی شهرهای فرانسه ۲۰۲۶' : 'French Academic Hubs Benchmarks 2026' }}
                            </span>
                            <h2 class="h3 fw-bold text-dark mt-2">
                                {{ __('calculator.table_heading') }}
                            </h2>
                            <p class="text-muted small mx-auto" style="max-width: 680px; line-height: 1.7;">
                                {{ $locale === 'fa' 
                                    ? 'مقایسه شفاف میانگین اجاره خوابگاه دولتی کروس، هم‌خانگی و استودیو شخصی به همراه سوبسید مسکن CAF و حداقل تمکن پیشنهادی به تفکیک شهرهای پرتقاضا.'
                                    : 'Real-world cost benchmarks across major student cities in France, including CROUS dorms, shared apartments, private studios, and monthly CAF housing subsidies.' }}
                            </p>
                        </div>

                        {{-- Benchmark Comparison Table --}}
                        <div class="table-responsive rounded-4 border shadow-xs mb-5 overflow-hidden">
                            <table class="table table-hover align-middle mb-0 bg-white" style="font-size: 0.9rem;">
                                <thead class="table-light">
                                    <tr class="text-secondary small fw-bold">
                                        <th class="py-3 px-3 px-md-4">{{ __('calculator.table_city') }}</th>
                                        <th class="py-3 px-2 text-center">{{ __('calculator.table_crous') }}</th>
                                        <th class="py-3 px-2 text-center">{{ __('calculator.table_colocation') }}</th>
                                        <th class="py-3 px-2 text-center">{{ __('calculator.table_studio') }}</th>
                                        <th class="py-3 px-2 text-center text-success">{{ __('calculator.table_caf') }}</th>
                                        <th class="py-3 px-3 text-end">{{ __('calculator.table_action') }}</th>
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
                                        <tr class="{{ $isSelected ? 'table-warning-subtle' : '' }}">
                                            <td class="py-3 px-3 px-md-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bx bxs-map text-brand fs-5"></i>
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
                                            <td class="py-3 px-3 text-end">
                                                <a href="{{ route('calculator', ['locale' => $locale, 'city' => $key]) }}" 
                                                   class="btn btn-sm btn-outline-brand rounded-pill px-3 py-1 fw-semibold transition-all">
                                                    <span>{{ __('calculator.table_action') }}</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Interactive FAQ Section --}}
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
            </div>
        </section>
        <!-- End City Comparison Table Section -->

        <!-- Start Quick Consultation Banner -->
        <section class="py-5 bg-light-subtle">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-11">
                        <div class="p-4 p-md-5 rounded-4 bg-white border shadow-xs d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
                            <div>
                                <h3 class="h4 fw-bold text-dark mb-2">
                                    {{ __('calculator.cta_title') }}
                                </h3>
                                <p class="text-secondary small mb-0" style="max-width: 650px; line-height: 1.7;">
                                    {{ __('calculator.cta_desc') }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-center text-md-end">
                                <a href="{{ url($locale . '/consult?service=student-visa') }}" class="btn btn-brand rounded-pill px-4 py-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                                    <span>{{ __('calculator.cta_button') }}</span>
                                    <i class="{{ $arrowIcon }} fs-5"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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

<style>
    .btn-outline-brand {
        color: #ff5d22;
        border: 1.5px solid #ff5d22;
        background-color: transparent;
    }
    .btn-outline-brand:hover {
        background-color: #ff5d22;
        color: #ffffff;
    }
    .table-warning-subtle {
        background-color: #fff9f5 !important;
    }
</style>

