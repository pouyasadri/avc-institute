@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
    $arrowIcon = $isRtl ? 'flaticon-left-arrow' : 'flaticon-right-arrow';

    $pageTitle       = __('privacy.meta.title');
    $pageKeywords    = __('privacy.meta.keywords');
    $pageDescription = __('privacy.meta.description');
@endphp

@section('title', $pageTitle)
@section('keywords', $pageKeywords)
@section('description', $pageDescription)

@push('styles')
<style>
    /* Data row styles matching legal.blade.php */
    .data-row {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 0.85rem 0;
        border-bottom: 1px solid #edf2f7;
    }
    .data-row:last-child { border-bottom: none; }
    .data-row .data-label {
        font-weight: 600;
        color: #6b7a9a;
        min-width: 200px;
        flex-shrink: 0;
    }
    .data-row .data-value {
        font-weight: 500;
        color: #0d1b3e;
        word-break: break-word;
    }
    .badge-code {
        background: #f0f4ff;
        border: 1px solid #d0e0ff;
        border-radius: 6px;
        padding: 0.2rem 0.6rem;
        font-family: 'Courier New', monospace;
        color: #1a6ef5;
        font-size: 0.9rem;
    }
    
    [dir="rtl"] .data-row { flex-direction: row-reverse; }
    [dir="rtl"] .data-row .data-label { text-align: right; }
    
    @media (max-width: 767px) {
        .data-row { flex-direction: column; gap: 0.3rem; }
        .data-row .data-label { min-width: unset; }
    }

    /* Metadata pills */
    .badge-gdpr-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(15, 58, 128, 0.08);
        border: 1px solid rgba(15, 58, 128, 0.2);
        color: #0F3A80;
        border-radius: 50px;
        padding: 5px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .version-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 50px;
        padding: 5px 14px;
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Table of contents styled with site brand colors */
    .privacy-toc {
        background: #f8faff;
        border-left: 4px solid #0F3A80;
        border-radius: 0 16px 16px 0;
        padding: 22px 26px;
        margin-bottom: 2.5rem;
        border-top: 1px solid #e9edf5;
        border-right: 1px solid #e9edf5;
        border-bottom: 1px solid #e9edf5;
    }
    [dir="rtl"] .privacy-toc {
        border-left: 1px solid #e9edf5;
        border-right: 4px solid #0F3A80;
        border-radius: 16px 0 0 16px;
    }
    .privacy-toc h2 {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 0.9rem;
        color: #0F3A80;
    }
    .privacy-toc ol {
        margin: 0;
        padding-inline-start: 1.25rem;
    }
    .privacy-toc li {
        margin-bottom: 6px;
    }
    .privacy-toc a {
        color: #3b5998;
        text-decoration: none;
        font-size: 0.92rem;
        font-weight: 500;
        transition: color 0.2s ease;
    }
    .privacy-toc a:hover {
        color: #ff8c00;
        text-decoration: underline;
    }

    /* Privacy sections */
    .privacy-section {
        scroll-margin-top: 95px;
        margin-bottom: 2.75rem;
    }
    .privacy-section h2 {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0F3A80;
        border-bottom: 2px solid #eef2f6;
        padding-bottom: 0.6rem;
        margin-bottom: 1.25rem;
    }
    .privacy-section p, .privacy-section li {
        color: #4a5568;
        line-height: 1.75;
    }
    .privacy-section a:not(.default-btn):not(.btn) {
        color: #0F3A80;
        font-weight: 500;
    }
    .privacy-section a:not(.default-btn):not(.btn):hover {
        color: #ff8c00;
    }

    /* Button contrast fix inside privacy sections */
    .privacy-section .default-btn,
    .default-btn {
        color: #ffffff !important;
        background-color: #0F3A80;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .privacy-section .default-btn:hover,
    .default-btn:hover {
        color: #ffffff !important;
        background-color: #ff8c00 !important;
        text-decoration: none;
    }
    .privacy-section .default-btn i.btn-icon-prefix {
        width: auto !important;
        height: auto !important;
        line-height: 1 !important;
        background: transparent !important;
        color: #ffffff !important;
        border-radius: 0 !important;
        position: static !important;
        font-size: 1.15rem !important;
        margin-right: 6px;
    }
    [dir="rtl"] .privacy-section .default-btn i.btn-icon-prefix {
        margin-right: 0;
        margin-left: 6px;
    }

    /* GDPR Table */
    .gdpr-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }
    .gdpr-table th {
        background: #0F3A80;
        color: #fff;
        padding: 12px 16px;
        font-weight: 600;
        text-align: inherit;
    }
    .gdpr-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #eef2f6;
        vertical-align: top;
        color: #4a5568;
    }
    .gdpr-table tr:last-child td {
        border-bottom: none;
    }
    .gdpr-table tr:nth-child(even) td {
        background: #f8fafc;
    }

    /* Rights Cards */
    .right-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 10px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
        height: calc(100% - 10px);
        transition: all 0.25s ease;
    }
    .right-card:hover {
        border-color: #0F3A80;
        box-shadow: 0 4px 14px rgba(15, 58, 128, 0.08);
        transform: translateY(-2px);
    }
    .right-card .right-icon {
        width: 36px;
        height: 36px;
        background: rgba(15, 58, 128, 0.08);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #0F3A80;
        font-size: 1.15rem;
    }
    .right-card strong {
        display: block;
        font-size: 0.92rem;
        color: #0d1b3e;
        margin-bottom: 2px;
    }
    .right-card span {
        font-size: 0.84rem;
        color: #6c757d;
        line-height: 1.5;
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
                        ['url' => url($currentLocale . '/'), 'label' => __('privacy.breadcrumb.home')],
                        ['label' => __('privacy.breadcrumb.privacy')]
                    ]" />
                    <h1>{{ __('privacy.title') }}</h1>
                </div>
            </div>
        </header>
        <!-- End Page Title Area -->

        <!-- Start Privacy Details Area -->
        <section class="service-details-area ptb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <div class="service-details-desc">

                            <!-- Intro Card -->
                            <div class="rounded-4 p-4 bg-light shadow-sm mb-4 border">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                    <span class="badge-gdpr-pill">
                                        <i class="bx bx-shield-quarter"></i>
                                        GDPR · EU 2016/679
                                    </span>
                                    <span class="version-pill">
                                        <i class="bx bx-history"></i>
                                        {{ __('privacy.version') }} &nbsp;·&nbsp; {{ __('privacy.updated') }}
                                    </span>
                                </div>
                                <p class="mb-0 text-muted" style="line-height: 1.8;">{!! __('privacy.intro') !!}</p>
                            </div>

                            <!-- Table of contents -->
                            <div class="privacy-toc">
                                <h2><i class="bx bx-list-ul me-1"></i> {{ app()->getLocale() === 'fa' ? 'فهرست مطالب' : (app()->getLocale() === 'fr' ? 'Table des matières' : 'Table of Contents') }}</h2>
                                <ol>
                                    <li><a href="#section-controller">{{ __('privacy.controller.heading') }}</a></li>
                                    <li><a href="#section-data">{{ __('privacy.data_collected.heading') }}</a></li>
                                    <li><a href="#section-basis">{{ __('privacy.legal_basis.heading') }}</a></li>
                                    <li><a href="#section-purposes">{{ __('privacy.purposes.heading') }}</a></li>
                                    <li><a href="#section-cookies">{{ __('privacy.cookies.heading') }}</a></li>
                                    <li><a href="#section-sharing">{{ __('privacy.sharing.heading') }}</a></li>
                                    <li><a href="#section-retention">{{ __('privacy.retention.heading') }}</a></li>
                                    <li><a href="#section-rights">{{ __('privacy.rights.heading') }}</a></li>
                                    <li><a href="#section-security">{{ __('privacy.security.heading') }}</a></li>
                                    <li><a href="#section-transfers">{{ __('privacy.transfers.heading') }}</a></li>
                                    <li><a href="#section-children">{{ __('privacy.children.heading') }}</a></li>
                                    <li><a href="#section-updates">{{ __('privacy.updates.heading') }}</a></li>
                                    <li><a href="#section-contact">{{ __('privacy.contact_us.heading') }}</a></li>
                                </ol>
                            </div>

                            <!-- § 1 Controller -->
                            <div id="section-controller" class="privacy-section">
                                <h2>{{ __('privacy.controller.heading') }}</h2>
                                <p>{{ __('privacy.controller.body') }}</p>

                                <div class="rounded-4 p-4 bg-light shadow-sm mb-3 border">
                                    <div class="data-row">
                                        <span class="data-label">{{ $currentLocale === 'fa' ? 'نام سازمان' : ($currentLocale === 'fr' ? 'Entité responsable' : 'Entity / Organization') }}</span>
                                        <span class="data-value fw-bold text-dark">{{ __('privacy.controller.name') }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">{{ $currentLocale === 'fa' ? 'نشانی' : ($currentLocale === 'fr' ? 'Adresse' : 'Address') }}</span>
                                        <span class="data-value"><i class="bx bx-map-pin me-1 text-primary"></i> {{ __('privacy.controller.address') }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Email</span>
                                        <span class="data-value"><i class="bx bx-envelope me-1 text-primary"></i> <a href="mailto:{{ __('privacy.controller.email') }}">{{ __('privacy.controller.email') }}</a></span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">{{ $currentLocale === 'fa' ? 'تلفن تماس' : ($currentLocale === 'fr' ? 'Téléphone' : 'Phone') }}</span>
                                        <span class="data-value"><i class="bx bx-phone me-1 text-primary"></i> <a href="tel:+33768688326" dir="ltr">{{ __('privacy.controller.phone') }}</a></span>
                                    </div>
                                </div>
                                <p class="text-muted small mb-0">{!! __('privacy.controller.note') !!}</p>
                            </div>

                            <!-- § 2 Data collected -->
                            <div id="section-data" class="privacy-section">
                                <h2>{{ __('privacy.data_collected.heading') }}</h2>
                                <p>{{ __('privacy.data_collected.intro') }}</p>
                                @foreach(__('privacy.data_collected.categories') as $cat)
                                    <div class="rounded-4 p-3 bg-light shadow-sm border mb-3">
                                        <h6 class="fw-bold mb-2 text-dark d-flex align-items-center">
                                            <i class="bx bx-check-circle text-primary me-2"></i> {{ $cat['name'] }}
                                        </h6>
                                        <ul class="mb-0 {{ $isRtl ? 'pe-3' : 'ps-3' }}">
                                            @foreach($cat['items'] as $item)
                                                <li>{!! $item !!}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                                <div class="alert alert-info border-0 rounded-4 mt-3 py-3 px-3 small d-flex align-items-start gap-2 shadow-sm">
                                    <i class="bx bx-info-circle fs-5 mt-1 flex-shrink-0 text-primary"></i>
                                    <div>{!! __('privacy.data_collected.not_collected') !!}</div>
                                </div>
                            </div>

                            <!-- § 3 Legal basis -->
                            <div id="section-basis" class="privacy-section">
                                <h2>{{ __('privacy.legal_basis.heading') }}</h2>
                                <p>{{ __('privacy.legal_basis.intro') }}</p>
                                <div class="table-responsive rounded-4 overflow-hidden border shadow-sm mb-3">
                                    <table class="gdpr-table mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width:38%">{{ app()->getLocale() === 'fa' ? 'مبنای قانونی' : (app()->getLocale() === 'fr' ? 'Base légale' : 'Legal basis') }}</th>
                                                <th>{{ app()->getLocale() === 'fa' ? 'کاربرد' : (app()->getLocale() === 'fr' ? 'Utilisation' : 'Use case') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(__('privacy.legal_basis.bases') as $base)
                                                <tr>
                                                    <td class="fw-semibold text-dark">{{ $base['basis'] }}</td>
                                                    <td>{{ $base['use'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- § 4 Purposes -->
                            <div id="section-purposes" class="privacy-section">
                                <h2>{{ __('privacy.purposes.heading') }}</h2>
                                <div class="rounded-4 p-4 bg-light shadow-sm border">
                                    <ul class="list-unstyled mb-0">
                                        @foreach(__('privacy.purposes.items') as $item)
                                            <li class="mb-2 d-flex align-items-start gap-2">
                                                <i class="bx bx-check-circle text-primary mt-1 flex-shrink-0"></i>
                                                <span>{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- § 5 Cookies -->
                            <div id="section-cookies" class="privacy-section">
                                <h2>{{ __('privacy.cookies.heading') }}</h2>
                                <p>{{ __('privacy.cookies.intro') }}</p>
                                <div class="table-responsive rounded-4 overflow-hidden border shadow-sm mb-3">
                                    <table class="gdpr-table mb-0">
                                        <thead>
                                            <tr>
                                                @foreach(__('privacy.cookies.table_headers') as $h)
                                                    <th>{{ $h }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(__('privacy.cookies.items') as $cookie)
                                                <tr>
                                                    <td><span class="badge-code">{{ $cookie['name'] }}</span></td>
                                                    <td>{!! $cookie['purpose'] !!}</td>
                                                    <td>{{ $cookie['expiry'] }}</td>
                                                    <td class="small">{!! $cookie['type'] !!}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <p class="small text-muted mt-2 d-flex align-items-center gap-1">
                                    <i class="bx bx-info-circle text-primary"></i>
                                    <span>{!! __('privacy.cookies.change_mind') !!}</span>
                                </p>
                            </div>

                            <!-- § 6 Sharing -->
                            <div id="section-sharing" class="privacy-section">
                                <h2>{{ __('privacy.sharing.heading') }}</h2>
                                <p>{{ __('privacy.sharing.intro') }}</p>
                                <div class="table-responsive rounded-4 overflow-hidden border shadow-sm mb-3">
                                    <table class="gdpr-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>{{ app()->getLocale() === 'fa' ? 'گیرنده' : (app()->getLocale() === 'fr' ? 'Destinataire' : 'Recipient') }}</th>
                                                <th>{{ app()->getLocale() === 'fa' ? 'هدف' : (app()->getLocale() === 'fr' ? 'Finalité' : 'Purpose') }}</th>
                                                <th>{{ app()->getLocale() === 'fa' ? 'کشور' : (app()->getLocale() === 'fr' ? 'Pays' : 'Country') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(__('privacy.sharing.recipients') as $r)
                                                <tr>
                                                    <td class="fw-semibold text-dark">{{ $r['name'] }}</td>
                                                    <td>{!! $r['purpose'] !!}</td>
                                                    <td><span class="badge-code">{{ $r['country'] }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- § 7 Retention -->
                            <div id="section-retention" class="privacy-section">
                                <h2>{{ __('privacy.retention.heading') }}</h2>
                                <div class="rounded-4 p-4 bg-light shadow-sm border">
                                    <p class="mb-0">{!! __('privacy.retention.body') !!}</p>
                                </div>
                            </div>

                            <!-- § 8 Rights -->
                            <div id="section-rights" class="privacy-section">
                                <h2>{{ __('privacy.rights.heading') }}</h2>
                                <p>{{ __('privacy.rights.intro') }}</p>
                                <div class="row g-3 mb-4">
                                    @foreach(__('privacy.rights.items') as $right)
                                        <div class="col-md-6">
                                            <div class="right-card">
                                                <div class="right-icon"><i class="bx bx-check-shield"></i></div>
                                                <div>
                                                    <strong>{{ $right['right'] }}</strong>
                                                    <span>{{ $right['desc'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mb-3">{!! __('privacy.rights.exercise') !!}</p>
                                <div class="mb-4">
                                    <a href="{{ route('data-rights', ['locale' => $currentLocale]) }}" class="default-btn">
                                        <i class="bx bx-shield-quarter btn-icon-prefix"></i>
                                        {{ __('privacy.data_rights.title') }}
                                        <i class="{{ $arrowIcon }}"></i>
                                    </a>
                                </div>
                                <div class="alert alert-warning border-0 rounded-4 py-3 px-3 small d-flex align-items-start gap-2 shadow-sm">
                                    <i class="bx bx-building fs-5 mt-1 flex-shrink-0 text-warning"></i>
                                    <div>{!! __('privacy.rights.supervisory') !!}</div>
                                </div>
                            </div>

                            <!-- § 9 Security -->
                            <div id="section-security" class="privacy-section">
                                <h2>{{ __('privacy.security.heading') }}</h2>
                                <div class="rounded-4 p-4 bg-light shadow-sm border">
                                    <p class="mb-0">{{ __('privacy.security.body') }}</p>
                                </div>
                            </div>

                            <!-- § 10 Transfers -->
                            <div id="section-transfers" class="privacy-section">
                                <h2>{{ __('privacy.transfers.heading') }}</h2>
                                <div class="rounded-4 p-4 bg-light shadow-sm border">
                                    <p class="mb-0">{{ __('privacy.transfers.body') }}</p>
                                </div>
                            </div>

                            <!-- § 11 Children -->
                            <div id="section-children" class="privacy-section">
                                <h2>{{ __('privacy.children.heading') }}</h2>
                                <div class="rounded-4 p-4 bg-light shadow-sm border">
                                    <p class="mb-0">{{ __('privacy.children.body') }}</p>
                                </div>
                            </div>

                            <!-- § 12 Updates -->
                            <div id="section-updates" class="privacy-section">
                                <h2>{{ __('privacy.updates.heading') }}</h2>
                                <div class="rounded-4 p-4 bg-light shadow-sm border">
                                    <p class="mb-0">{{ __('privacy.updates.body') }}</p>
                                </div>
                            </div>

                            <!-- § 13 Contact -->
                            <div id="section-contact" class="privacy-section">
                                <h2>{{ __('privacy.contact_us.heading') }}</h2>
                                <p>{{ __('privacy.contact_us.body') }}</p>

                                <div class="rounded-4 p-4 bg-light shadow-sm mb-4 border">
                                    <div class="data-row">
                                        <span class="data-label">{{ $currentLocale === 'fa' ? 'نام سازمان' : ($currentLocale === 'fr' ? 'Entité responsable' : 'Entity / Organization') }}</span>
                                        <span class="data-value fw-bold text-dark">{{ __('privacy.controller.name') }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">{{ $currentLocale === 'fa' ? 'نشانی' : ($currentLocale === 'fr' ? 'Adresse' : 'Address') }}</span>
                                        <span class="data-value"><i class="bx bx-map-pin me-1 text-primary"></i> {{ __('privacy.controller.address') }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Email</span>
                                        <span class="data-value"><i class="bx bx-envelope me-1 text-primary"></i> <a href="mailto:{{ __('privacy.controller.email') }}">{{ __('privacy.controller.email') }}</a></span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">{{ $currentLocale === 'fa' ? 'مسئول حفاظت داده (DPO)' : ($currentLocale === 'fr' ? 'Délégué à la protection des données (DPO)' : 'Data Protection Officer (DPO)') }}</span>
                                        <span class="data-value"><i class="bx bx-shield-quarter me-1 text-primary"></i> <a href="mailto:dpo@applyvipconseil.com">dpo@applyvipconseil.com</a></span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">{{ $currentLocale === 'fa' ? 'تلفن تماس' : ($currentLocale === 'fr' ? 'Téléphone' : 'Phone') }}</span>
                                        <span class="data-value"><i class="bx bx-phone me-1 text-primary"></i> <a href="tel:+33768688326" dir="ltr">+33 7 68 68 83 26</a></span>
                                    </div>
                                </div>

                                <!-- Official DPO Gradient Highlight Banner matching legal.blade.php -->
                                <div class="mt-4 p-4 rounded-4 shadow-sm d-flex align-items-center flex-wrap gap-3" style="background: linear-gradient(135deg, #0F3A80, #1d4b8f); color: white; border: 1px solid rgba(255,255,255,0.15);">
                                    <div>
                                        <i class="bx bx-check-shield text-warning" style="font-size: 2.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong class="d-block text-white" style="font-size: 1.1rem;">{{ __('privacy.controller.name') }} — {{ $currentLocale === 'fa' ? 'مسئول حفاظت داده' : ($currentLocale === 'fr' ? 'Délégué Protection Données' : 'Data Protection Officer') }}</strong>
                                        <span class="text-white-50 small">dpo@applyvipconseil.com &nbsp;·&nbsp; {{ __('privacy.controller.address') }}</span>
                                    </div>
                                    <a href="mailto:dpo@applyvipconseil.com"
                                       class="btn btn-light rounded-pill px-4 py-2 fw-bold text-primary shadow-sm"
                                       style="transition: all 0.3s ease;"
                                       onmouseover="this.style.transform='translateY(-2px)';"
                                       onmouseout="this.style.transform='none';">
                                        <i class="bx bx-envelope me-1"></i> dpo@applyvipconseil.com
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4 col-md-12">
                        <!-- Sidebar Contact / Consultation Widget -->
                        <div class="service-sidebar-widget rounded-4 p-4 bg-light-subtle shadow-sm mb-4">
                            <h3 class="h5 fw-bold mb-3">{{ __('index.video.button') ?? 'Contact Us' }}</h3>
                            <p class="text-muted small mb-4">{{ __('index.video.p2') ?? 'Contact us for more details.' }}</p>
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
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('layout.footer.links.home') ?? 'Home' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('services.index', ['locale' => $currentLocale]) }}" class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('layout.footer.links.services') ?? 'Services' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('legal', ['locale' => $currentLocale]) }}" class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('legal.breadcrumb.legal') ?? 'Legal Identity' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('data-rights', ['locale' => $currentLocale]) }}" class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('privacy.data_rights.title') ?? 'Exercise GDPR Rights' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ url($currentLocale . '/contactUs') }}" class="text-decoration-none text-dark d-flex align-items-center">
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
        <!-- End Privacy Details Area -->
    </div>
@endsection
