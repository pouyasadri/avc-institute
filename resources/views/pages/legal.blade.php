@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
    $arrowIcon = $isRtl ? 'flaticon-left-arrow' : 'flaticon-right-arrow';

    $pageTitle       = __('legal.meta.title');
    $pageKeywords    = __('legal.meta.keywords');
    $pageDescription = __('legal.meta.description');

    $org = config('seo.organization');
@endphp

@section('title', $pageTitle)
@section('keywords', $pageKeywords)
@section('description', $pageDescription)

@push('styles')
<style>
    /* Minimal styles for the data rows, matching the theme */
    .data-row {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #eee;
    }
    .data-row:last-child { border-bottom: none; }
    .data-row .data-label {
        font-weight: 600;
        color: #6b7a9a;
        min-width: 220px;
        flex-shrink: 0;
    }
    .data-row .data-value {
        font-weight: 500;
        color: #0d1b3e;
        word-break: break-all;
    }
    .badge-code {
        background: #f0f4ff;
        border: 1px solid #d0e0ff;
        border-radius: 6px;
        padding: 0.2rem 0.6rem;
        font-family: 'Courier New', monospace;
        color: #1a6ef5;
        font-size: 0.95rem;
    }
    
    [dir="rtl"] .data-row { flex-direction: row-reverse; }
    [dir="rtl"] .data-row .data-label { text-align: right; }
    
    @media (max-width: 767px) {
        .data-row { flex-direction: column; gap: 0.3rem; }
        .data-row .data-label { min-width: unset; }
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
                        ['url' => url($currentLocale . '/'), 'label' => __('legal.breadcrumb.home')],
                        ['label' => __('legal.breadcrumb.legal')]
                    ]" />
                    <h1>{{ __('legal.hero.title') }}</h1>
                </div>
            </div>
        </header>
        <!-- End Page Title Area -->

        <!-- Start Legal Details Area -->
        <section class="service-details-area ptb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <div class="service-details-desc">
                            <h2 class="mb-4">{{ __('legal.hero.title') }}</h2>
                            
                            <p class="mb-4">{{ __('legal.hero.subtitle') }}</p>

                            <h3 class="mt-5 mb-4">{{ __('legal.trust.title') }}</h3>
                            <ul class="list-unstyled mb-5">
                                @foreach(__('legal.trust.items') as $item)
                                    <li class="mb-3 d-flex align-items-start">
                                        <i class="{{ $item['icon'] }} text-primary me-3 mt-1" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <h4 class="h6 fw-bold mb-1">{{ $item['title'] }}</h4>
                                            <span class="text-muted">{{ $item['description'] }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <h3 class="mt-5 mb-4">{{ __('legal.registration.title') }}</h3>
                            <p class="text-muted mb-4">{{ __('legal.registration.subtitle') }}</p>

                            <div class="rounded-4 p-4 bg-light shadow-sm mb-4 border">
                                @php
                                    $fields = __('legal.registration.fields');
                                    $values = __('legal.registration.values');
                                @endphp

                                <div class="data-row">
                                    <span class="data-label">{{ $fields['company_name'] }}</span>
                                    <span class="data-value">Apply Vip Conseil (A.V.C)</span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">{{ $fields['legal_name'] }}</span>
                                    <span class="data-value"><span class="badge-code">APPLY VIP CONSEIL</span></span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">{{ $fields['legal_form'] }}</span>
                                    <span class="data-value">{{ $values['legal_form'] }}</span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">{{ $fields['siren'] }}</span>
                                    <span class="data-value"><span class="badge-code" dir="ltr">{{ $org['siren'] ?? '983 675 331' }}</span></span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">{{ $fields['siret'] }}</span>
                                    <span class="data-value"><span class="badge-code" dir="ltr">{{ $org['siret'] ?? '983 675 331 00018' }}</span></span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">{{ $fields['vat'] }}</span>
                                    <span class="data-value"><span class="badge-code" dir="ltr">{{ $org['vat_id'] ?? 'FR49 983 675 331' }}</span></span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">{{ $fields['naf_code'] }}</span>
                                    <span class="data-value"><span class="badge-code" dir="ltr">{{ $org['naf_code'] ?? '68.10Z' }}</span></span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">{{ $fields['naf_label'] }}</span>
                                    <span class="data-value">{{ $values['naf_label'] }}</span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">{{ $fields['address'] }}</span>
                                    <span class="data-value">{{ $values['address'] }}</span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">{{ $fields['share_capital'] }}</span>
                                    <span class="data-value">{{ $values['share_capital'] }}</span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">{{ $fields['founding_date'] }}</span>
                                    <span class="data-value">{{ $values['founding_date'] }}</span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">{{ $fields['rne_date'] }}</span>
                                    <span class="data-value">{{ $values['rne_date'] }}</span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">{{ $fields['nature'] }}</span>
                                    <span class="data-value">{{ $values['nature'] }}</span>
                                </div>
                            </div>

                            <div class="mt-4 p-4 bg-primary bg-opacity-10 rounded-4 border border-primary border-opacity-25 d-flex align-items-center flex-wrap gap-3">
                                <div>
                                    <i class="bx bx-check-shield text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong class="d-block text-dark">{{ __('legal.registration.inpi_label') }}</strong>
                                    <span class="text-muted small">annuaire-entreprises.data.gouv.fr</span>
                                </div>
                                <a href="{{ $org['inpi_url'] ?? 'https://annuaire-entreprises.data.gouv.fr/entreprise/983675331' }}"
                                   class="default-btn"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    {{ __('legal.registration.inpi_button') }}
                                </a>
                            </div>

                            <div class="mt-5 pt-4 border-top">
                                <p class="text-muted small d-flex align-items-start gap-2">
                                    <i class="bx bx-info-circle mt-1"></i>
                                    <span>
                                        <strong>{{ __('legal.disclaimer.title') }}:</strong>
                                        {{ __('legal.disclaimer.text') }}
                                    </span>
                                </p>
                            </div>

                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-12">
                        <!-- Sidebar Contact Widget -->
                        <div class="service-sidebar-widget rounded-4 p-4 bg-light-subtle shadow-sm mb-4">
                            <h3 class="h5 fw-bold mb-3">{{ __('index.video.button') ?? 'Contact Us' }}</h3>
                            <p class="text-muted small mb-4">{{ __('index.video.p2') ?? 'Contact us for more details.' }}
                            </p>
                            <a href="{{ url($currentLocale . '/consult') }}" class="default-btn w-100 text-center">
                                {{ __('index.video.button') ?? 'Book Consultation' }}
                                <i class="{{ $arrowIcon }}"></i>
                            </a>
                        </div>

                        <!-- Sidebar Navigation Widget -->
                        <div class="service-sidebar-widget rounded-4 p-4 bg-white shadow-sm mt-4">
                            <h3 class="h5 fw-bold mb-3">{{ __('layout.footer.quick_links_title') ?? 'Quick Links' }}</h3>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <a href="{{ route('index', ['locale' => $currentLocale]) }}" class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx bx-chevron-left me-2 text-primary"></i>
                                        <span>{{ __('layout.footer.links.home') ?? 'Home' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('services.index', ['locale' => $currentLocale]) }}" class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx bx-chevron-left me-2 text-primary"></i>
                                        <span>{{ __('layout.footer.links.services') ?? 'Services' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ url($currentLocale . '/contactUs') }}" class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx bx-chevron-left me-2 text-primary"></i>
                                        <span>{{ __('layout.footer.links.contact') ?? 'Contact Us' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Legal Details Area -->
    </div>
@endsection
