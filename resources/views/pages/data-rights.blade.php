@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
    $arrowIcon = $isRtl ? 'flaticon-left-arrow' : 'flaticon-right-arrow';

    $pageTitle       = __('privacy.data_rights.meta.title');
    $pageKeywords    = __('privacy.meta.keywords');
    $pageDescription = __('privacy.data_rights.subtitle');
@endphp

@section('title', $pageTitle)
@section('keywords', $pageKeywords)
@section('description', $pageDescription)

@push('styles')
<style>
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

    /* Rights Mini Cards */
    .rights-summary-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        text-align: center;
        height: 100%;
        transition: all 0.25s ease;
        cursor: pointer;
        user-select: none;
    }
    .rights-summary-card:hover {
        border-color: #0F3A80;
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(15, 58, 128, 0.1);
    }
    .rights-summary-card .icon-wrap {
        width: 44px;
        height: 44px;
        margin: 0 auto 10px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 58, 128, 0.08);
        color: #0F3A80;
        font-size: 1.4rem;
        transition: all 0.25s ease;
    }
    .rights-summary-card:hover .icon-wrap {
        background: #0F3A80;
        color: #fff;
    }

    /* Request Type Radio Cards — Generous padding & modern interactive UX */
    .request-type-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 20px;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        background: #fff;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        position: relative;
        height: 100%;
        margin-bottom: 0;
    }
    .request-type-card:hover {
        border-color: #0F3A80;
        background: #fbfdff;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(15, 58, 128, 0.07);
    }
    .request-type-card.is-active,
    .request-type-card:has(.request-type-radio:checked) {
        border-color: #0F3A80 !important;
        background-color: #f4f8ff !important;
        box-shadow: 0 0 0 1px #0F3A80, 0 6px 18px rgba(15, 58, 128, 0.09) !important;
    }
    .request-type-radio-wrap {
        padding-top: 2px;
        flex-shrink: 0;
    }
    .request-type-radio {
        width: 20px;
        height: 20px;
        margin: 0 !important;
        cursor: pointer;
        accent-color: #0F3A80;
    }
    .request-type-content {
        flex-grow: 1;
    }
    .request-type-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 4px;
    }
    .request-type-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 50px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .request-type-card.is-active .request-type-badge,
    .request-type-card:has(.request-type-radio:checked) .request-type-badge {
        background: #0F3A80;
        color: #ffffff;
        border-color: #0F3A80;
    }
    .request-type-desc {
        font-size: 0.84rem;
        color: #64748b;
        line-height: 1.5;
        margin: 0;
    }

    /* Consent Checkbox Card */
    .consent-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        transition: all 0.2s ease;
    }
    .consent-card:hover {
        background: #f1f5f9;
    }
    .consent-card .form-check-input {
        width: 18px;
        height: 18px;
        margin: 0 !important;
        margin-top: 2px !important;
        accent-color: #0F3A80;
        flex-shrink: 0;
        cursor: pointer;
    }

    /* Input Focus Styling */
    .form-control:focus {
        border-color: #0F3A80;
        box-shadow: 0 0 0 3px rgba(15, 58, 128, 0.12);
    }

    /* High contrast default-btn */
    .default-btn {
        color: #ffffff !important;
        background-color: #0F3A80;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
    }
    .default-btn:hover {
        color: #ffffff !important;
        background-color: #ff8c00 !important;
        text-decoration: none;
    }
    .default-btn i.btn-icon-prefix {
        width: auto !important;
        height: auto !important;
        line-height: 1 !important;
        background: transparent !important;
        color: #ffffff !important;
        border-radius: 0 !important;
        position: static !important;
        font-size: 1.15rem !important;
        margin-right: 8px;
    }
    [dir="rtl"] .default-btn i.btn-icon-prefix {
        margin-right: 0;
        margin-left: 8px;
    }

    /* Response Time Alert Box */
    .response-notice-box {
        background: #f8faff;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #0F3A80;
        border-radius: 0 16px 16px 0;
        padding: 20px 24px;
    }
    [dir="rtl"] .response-notice-box {
        border-left: 1px solid #e2e8f0;
        border-right: 4px solid #0F3A80;
        border-radius: 16px 0 0 16px;
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
                        ['url' => route('privacy', ['locale' => $currentLocale]), 'label' => __('privacy.breadcrumb.privacy')],
                        ['label' => __('privacy.data_rights.title')]
                    ]" />
                    <h1>{{ __('privacy.data_rights.title') }}</h1>
                </div>
            </div>
        </header>
        <!-- End Page Title Area -->

        <!-- Start Details Area -->
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
                                        GDPR · Art. 15–22
                                    </span>
                                </div>
                                <h2 class="h4 fw-bold text-dark mb-2">{{ __('privacy.data_rights.title') }}</h2>
                                <p class="mb-0 text-muted" style="line-height: 1.8;">{{ __('privacy.data_rights.subtitle') }}</p>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success border-0 rounded-4 d-flex align-items-center gap-2 mb-4 shadow-sm py-3 px-4">
                                    <i class="bx bx-check-circle fs-3 text-success"></i>
                                    <div class="fw-semibold">{{ session('success') }}</div>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm py-3 px-4">
                                    <i class="bx bx-error-circle fs-4 me-2"></i> {{ session('error') }}
                                </div>
                            @endif

                            <!-- Rights overview interactive cards -->
                            <div class="row g-3 mb-4">
                                @foreach([
                                    ['type'=>'access',        'icon'=>'bx-search-alt'],
                                    ['type'=>'rectification', 'icon'=>'bx-edit'],
                                    ['type'=>'erasure',       'icon'=>'bx-trash-alt'],
                                    ['type'=>'portability',   'icon'=>'bx-export'],
                                    ['type'=>'objection',     'icon'=>'bx-block'],
                                    ['type'=>'restriction',   'icon'=>'bx-pause'],
                                ] as $r)
                                    <div class="col-md-4 col-6">
                                        <div class="rights-summary-card shadow-sm" data-type="{{ $r['type'] }}" title="Click to select {{ __('privacy.data_rights.types.' . $r['type'] . '.label') }}">
                                            <div class="icon-wrap">
                                                <i class="bx {{ $r['icon'] }}"></i>
                                            </div>
                                            <div class="small fw-bold text-dark mb-1">{{ __('privacy.data_rights.types.' . $r['type'] . '.label') }}</div>
                                            <div class="small text-muted">{{ __('privacy.data_rights.types.' . $r['type'] . '.article') }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Request Form Card -->
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" id="rights-form-card">
                                <div class="card-header py-3 px-4" style="background: #0F3A80; color: #fff;">
                                    <h5 class="mb-0 text-white d-flex align-items-center gap-2">
                                        <i class="bx bx-send"></i>
                                        <span>{{ __('privacy.data_rights.form.heading') }}</span>
                                    </h5>
                                </div>
                                <div class="card-body p-4 p-md-5 bg-white">
                                    <form action="{{ route('data-rights.submit', ['locale' => $currentLocale]) }}" method="POST">
                                        @csrf

                                        <!-- Email -->
                                        <div class="mb-4">
                                            <label for="dr_email" class="form-label fw-bold text-dark d-flex align-items-center justify-content-between flex-wrap gap-1">
                                                <span>{{ __('privacy.data_rights.form.email') }} <span class="text-danger">*</span></span>
                                                <span class="text-muted small fw-normal">{{ __('privacy.data_rights.form.email_hint') }}</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-primary border-end-0 px-3">
                                                    <i class="bx bx-envelope fs-5"></i>
                                                </span>
                                                <input type="email" name="email" id="dr_email"
                                                    class="form-control py-2 px-3 @error('email') is-invalid @enderror"
                                                    value="{{ old('email') }}" required
                                                    placeholder="your@email.com">
                                            </div>
                                            @error('email')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <!-- Request type with spacious interactive cards -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold text-dark mb-2">{{ __('privacy.data_rights.form.request_type') }} <span class="text-danger">*</span></label>
                                            <div class="@error('request_type') is-invalid @enderror">
                                                <div class="row g-3">
                                                    @foreach([
                                                        'access'        => ['icon' => 'bx-search-alt', 'color' => '#0F3A80'],
                                                        'rectification' => ['icon' => 'bx-edit',       'color' => '#0284c7'],
                                                        'erasure'       => ['icon' => 'bx-trash-alt',  'color' => '#dc2626'],
                                                        'portability'   => ['icon' => 'bx-export',     'color' => '#16a34a'],
                                                        'objection'     => ['icon' => 'bx-block',      'color' => '#d97706'],
                                                        'restriction'   => ['icon' => 'bx-pause',      'color' => '#64748b'],
                                                    ] as $type => $meta)
                                                        <div class="col-md-6 col-12">
                                                            <label class="request-type-card {{ old('request_type') === $type ? 'is-active' : '' }}" for="rt_{{ $type }}" id="card_rt_{{ $type }}">
                                                                <div class="request-type-radio-wrap">
                                                                    <input class="request-type-radio" type="radio" name="request_type"
                                                                        id="rt_{{ $type }}" value="{{ $type }}"
                                                                        {{ old('request_type') === $type ? 'checked' : '' }} required>
                                                                </div>
                                                                <div class="request-type-content">
                                                                    <div class="request-type-title-row">
                                                                        <span class="d-flex align-items-center gap-2">
                                                                            <i class="bx {{ $meta['icon'] }} fs-5" style="color: {{ $meta['color'] }};"></i>
                                                                            <strong class="text-dark">{{ __('privacy.data_rights.types.' . $type . '.label') }}</strong>
                                                                        </span>
                                                                        <span class="request-type-badge">{{ __('privacy.data_rights.types.' . $type . '.article') }}</span>
                                                                    </div>
                                                                    <p class="request-type-desc">{{ __('privacy.data_rights.types.' . $type . '.desc') }}</p>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @error('request_type')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                                        </div>

                                        <!-- Optional notes -->
                                        <div class="mb-4">
                                            <label for="dr_notes" class="form-label fw-bold text-dark">{{ __('privacy.data_rights.form.notes') }}</label>
                                            <textarea name="notes_requester" id="dr_notes" rows="3"
                                                class="form-control rounded-3 py-2 px-3 @error('notes_requester') is-invalid @enderror"
                                                placeholder="{{ __('privacy.data_rights.form.notes_placeholder') }}">{{ old('notes_requester') }}</textarea>
                                            @error('notes_requester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <!-- Consent checkbox inside clean card -->
                                        <div class="mb-4">
                                            <div class="consent-card">
                                                <input class="form-check-input @error('gdpr_consent') is-invalid @enderror"
                                                    type="checkbox" name="gdpr_consent" id="dr_consent" value="1"
                                                    required {{ old('gdpr_consent') ? 'checked' : '' }}>
                                                <label class="form-check-label small text-muted mb-0" for="dr_consent" style="cursor: pointer; line-height: 1.6;">
                                                    {!! __('privacy.form.gdpr_consent_label', ['url' => route('privacy', ['locale' => $currentLocale])]) !!}
                                                </label>
                                            </div>
                                            @error('gdpr_consent')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <button type="submit" class="default-btn rounded-pill px-5 py-3">
                                            <i class="bx bx-send btn-icon-prefix"></i>
                                            {{ __('privacy.data_rights.form.submit') }}
                                            <i class="{{ $arrowIcon }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Response time notice -->
                            <div class="response-notice-box shadow-sm mt-4">
                                <p class="mb-1 fw-bold text-dark d-flex align-items-center gap-2">
                                    <i class="bx bx-time fs-5 text-primary"></i>
                                    <span>{{ __('privacy.data_rights.response_time_heading') }}</span>
                                </p>
                                <p class="mb-0 small text-muted">{!! __('privacy.data_rights.response_time_body') !!}</p>
                            </div>

                        </div>
                    </div>

                    <!-- Sidebar Column -->
                    <div class="col-lg-4 col-md-12">
                        <!-- Sidebar Contact Widget -->
                        <div class="service-sidebar-widget rounded-4 p-4 bg-light-subtle shadow-sm mb-4">
                            <h3 class="h5 fw-bold mb-3">{{ __('index.video.button') ?? 'Contact Us' }}</h3>
                            <p class="text-muted small mb-4">{{ __('index.video.p2') ?? 'Contact us for more details.' }}</p>
                            <a href="{{ url($currentLocale . '/consult') }}" class="default-btn w-100 text-center">
                                {{ __('index.video.button') ?? 'Book Consultation' }}
                                <i class="{{ $arrowIcon }}"></i>
                            </a>
                        </div>

                        <!-- Sidebar Quick Links Widget -->
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
                                    <a href="{{ route('privacy', ['locale' => $currentLocale]) }}" class="text-decoration-none text-dark d-flex align-items-center">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left ms-2' : 'bx-chevron-right me-2' }} text-primary"></i>
                                        <span>{{ __('privacy.title') ?? 'Privacy Policy' }}</span>
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
        <!-- End Details Area -->
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('.request-type-radio');

        function updateSelected() {
            radios.forEach(radio => {
                const card = radio.closest('.request-type-card');
                if (card) {
                    if (radio.checked) {
                        card.classList.add('is-active');
                    } else {
                        card.classList.remove('is-active');
                    }
                }
            });
        }

        radios.forEach(radio => {
            radio.addEventListener('change', updateSelected);
        });

        // Interactive mini-cards at top scroll and select
        document.querySelectorAll('.rights-summary-card[data-type]').forEach(card => {
            card.addEventListener('click', function() {
                const type = this.getAttribute('data-type');
                const targetRadio = document.getElementById('rt_' + type);
                if (targetRadio) {
                    targetRadio.checked = true;
                    updateSelected();
                    targetRadio.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });

        updateSelected();
    });
</script>
@endpush
