@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = $currentLocale === 'fa';

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
    /* ===================================================
       Legal Identity Page — Premium Styles
       =================================================== */

    /* Hero */
    .legal-hero {
        background: linear-gradient(135deg, #0a0f1e 0%, #112044 50%, #1a3a6e 100%);
        position: relative;
        overflow: hidden;
        padding: 110px 0 80px;
    }
    .legal-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .legal-hero .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.2);
        color: #7eb6ff;
        font-size: .78rem;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: .35rem .85rem;
        border-radius: 50px;
        backdrop-filter: blur(8px);
        margin-bottom: 1.2rem;
    }
    .legal-hero h1 {
        font-size: clamp(2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.2;
        margin-bottom: 1rem;
    }
    .legal-hero p {
        color: rgba(255,255,255,.7);
        font-size: 1.05rem;
        max-width: 720px;
        line-height: 1.75;
    }
    .legal-hero .hero-deco {
        position: absolute;
        right: -120px;
        top: -80px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(100,160,255,.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    [dir="rtl"] .legal-hero .hero-deco { right: auto; left: -120px; }
    [dir="rtl"] .legal-hero p { margin-{{ $isRtl ? 'right' : 'left' }}: 0; }

    /* Trust Cards */
    .trust-section {
        background: #f7f9fc;
        padding: 80px 0;
    }
    .trust-card {
        background: #fff;
        border-radius: 16px;
        padding: 2rem 1.6rem;
        height: 100%;
        border: 1px solid rgba(0,0,0,.06);
        box-shadow: 0 4px 24px rgba(0,0,0,.05);
        transition: transform .3s ease, box-shadow .3s ease;
    }
    .trust-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,.1);
    }
    .trust-card .icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, #1a6ef5 0%, #0a3fbe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.2rem;
        font-size: 1.5rem;
        color: #fff;
        flex-shrink: 0;
    }
    .trust-card h5 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0d1b3e;
        margin-bottom: .5rem;
    }
    .trust-card p {
        font-size: .9rem;
        color: #6b7a9a;
        line-height: 1.65;
        margin: 0;
    }

    /* Registration Card */
    .registration-section {
        padding: 80px 0 100px;
        background: #fff;
    }
    .registration-card {
        background: linear-gradient(160deg, #0d1b3e 0%, #112a5e 60%, #1a3a7a 100%);
        border-radius: 24px;
        padding: 2.5rem 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(13,27,62,.35);
    }
    .registration-card::before {
        content: '';
        position: absolute;
        top: -60px;
        {{ $isRtl ? 'left' : 'right' }}: -60px;
        width: 280px;
        height: 280px;
        background: radial-gradient(circle, rgba(100,160,255,.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .registration-card .card-header-label {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #7eb6ff;
        margin-bottom: .3rem;
    }
    .registration-card .card-title {
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: .3rem;
    }
    .registration-card .card-subtitle {
        font-size: .85rem;
        color: rgba(255,255,255,.55);
        margin-bottom: 2rem;
    }

    /* Data rows */
    .data-row {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: .85rem 0;
        border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .data-row:last-child { border-bottom: none; }
    .data-row .data-label {
        font-size: .78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: rgba(255,255,255,.5);
        min-width: 200px;
        flex-shrink: 0;
        padding-top: .1rem;
    }
    .data-row .data-value {
        font-size: .98rem;
        font-weight: 600;
        color: #fff;
        word-break: break-all;
        line-height: 1.5;
    }
    .data-row .data-value .badge-code {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 6px;
        padding: .2rem .6rem;
        font-family: 'Courier New', monospace;
        font-size: .92rem;
        letter-spacing: .04em;
        color: #b3d4ff;
    }

    /* INPI Button */
    .inpi-verify-block {
        margin-top: 2rem;
        padding: 1.4rem 1.6rem;
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.13);
        border-radius: 14px;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .inpi-verify-block .inpi-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #4ade80 0%, #16a34a 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #fff;
        flex-shrink: 0;
    }
    .inpi-verify-block .inpi-text { flex: 1; }
    .inpi-verify-block .inpi-text strong {
        display: block;
        font-size: .9rem;
        color: #fff;
        margin-bottom: .15rem;
    }
    .inpi-verify-block .inpi-text span {
        font-size: .78rem;
        color: rgba(255,255,255,.5);
    }
    .btn-inpi {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: linear-gradient(135deg, #4ade80 0%, #16a34a 100%);
        color: #fff !important;
        font-weight: 700;
        font-size: .82rem;
        padding: .55rem 1.2rem;
        border-radius: 8px;
        text-decoration: none;
        transition: opacity .2s, transform .2s;
        white-space: nowrap;
    }
    .btn-inpi:hover { opacity: .88; transform: translateY(-1px); }

    /* Disclaimer */
    .disclaimer-section {
        background: #f7f9fc;
        padding: 40px 0;
        border-top: 1px solid #e8edf5;
    }
    .disclaimer-inner {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }
    .disclaimer-inner .disc-icon {
        color: #6b7a9a;
        font-size: 1.3rem;
        flex-shrink: 0;
        margin-top: .1rem;
    }
    .disclaimer-inner p {
        font-size: .82rem;
        color: #6b7a9a;
        line-height: 1.7;
        margin: 0;
    }

    /* Section headings */
    .section-eyebrow {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #1a6ef5;
        margin-bottom: .5rem;
    }
    .section-heading {
        font-size: clamp(1.5rem, 3vw, 2.1rem);
        font-weight: 800;
        color: #0d1b3e;
        margin-bottom: .5rem;
    }
    .section-subheading {
        font-size: .92rem;
        color: #6b7a9a;
        margin-bottom: 2.5rem;
    }

    /* RTL adjustments */
    [dir="rtl"] .data-row { flex-direction: row-reverse; }
    [dir="rtl"] .data-row .data-label { text-align: right; }
    [dir="rtl"] .inpi-verify-block { flex-direction: row-reverse; text-align: right; }
    [dir="rtl"] .disclaimer-inner { flex-direction: row-reverse; }

    @media (max-width: 767px) {
        .data-row { flex-direction: column; gap: .3rem; }
        .data-row .data-label { min-width: unset; }
        .registration-card { padding: 1.6rem 1.2rem; }
        .inpi-verify-block { flex-direction: column; text-align: center; }
    }
</style>
@endpush

@section('content')

{{-- ===========================
     HERO
     =========================== --}}
<section class="legal-hero" aria-labelledby="legal-hero-title">
    <div class="hero-deco" aria-hidden="true"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <x-premium-breadcrumb :items="[
                    ['url' => url($currentLocale . '/'), 'label' => __('legal.breadcrumb.home')],
                    ['label' => __('legal.breadcrumb.legal')]
                ]" />
                <div class="badge-pill">
                    <i class='bx bx-shield-check' aria-hidden="true"></i>
                    {{ __('legal.hero.badge') }}
                </div>
                <h1 id="legal-hero-title">{{ __('legal.hero.title') }}</h1>
                <p>{{ __('legal.hero.subtitle') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- ===========================
     TRUST PILLARS
     =========================== --}}
<section class="trust-section" aria-labelledby="trust-heading">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-eyebrow">{{ __('legal.trust.subtitle') }}</p>
            <h2 class="section-heading" id="trust-heading">{{ __('legal.trust.title') }}</h2>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach(__('legal.trust.items') as $item)
                <div class="col-md-4">
                    <article class="trust-card" aria-label="{{ $item['title'] }}">
                        <div class="icon-wrap" aria-hidden="true">
                            <i class="{{ $item['icon'] }}"></i>
                        </div>
                        <h5>{{ $item['title'] }}</h5>
                        <p>{{ $item['description'] }}</p>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===========================
     REGISTRATION DATA
     =========================== --}}
<section class="registration-section" aria-labelledby="reg-heading">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-eyebrow">RNE · INPI · {{ date('Y') }}</p>
            <h2 class="section-heading" id="reg-heading">{{ __('legal.registration.title') }}</h2>
            <p class="section-subheading">{{ __('legal.registration.subtitle') }}</p>
        </div>

        <div class="registration-card">
            <div class="card-header-label">{{ __('legal.registration.title') }}</div>
            <p class="card-title">APPLY VIP CONSEIL</p>
            <p class="card-subtitle">{{ __('legal.registration.subtitle') }}</p>

            @php
                $fields = __('legal.registration.fields');
                $values = __('legal.registration.values');
            @endphp

            {{-- Company Name --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['company_name'] }}</span>
                <span class="data-value">Apply Vip Conseil (A.V.C)</span>
            </div>

            {{-- Legal Name --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['legal_name'] }}</span>
                <span class="data-value">
                    <span class="badge-code">APPLY VIP CONSEIL</span>
                </span>
            </div>

            {{-- Legal Form --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['legal_form'] }}</span>
                <span class="data-value">{{ $values['legal_form'] }}</span>
            </div>

            {{-- SIREN --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['siren'] }}</span>
                <span class="data-value">
                    <span class="badge-code" dir="ltr">{{ $org['siren'] ?? '983 675 331' }}</span>
                </span>
            </div>

            {{-- SIRET --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['siret'] }}</span>
                <span class="data-value">
                    <span class="badge-code" dir="ltr">{{ $org['siret'] ?? '983 675 331 00018' }}</span>
                </span>
            </div>

            {{-- VAT --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['vat'] }}</span>
                <span class="data-value">
                    <span class="badge-code" dir="ltr">{{ $org['vat_id'] ?? 'FR49 983 675 331' }}</span>
                </span>
            </div>

            {{-- NAF Code --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['naf_code'] }}</span>
                <span class="data-value">
                    <span class="badge-code" dir="ltr">{{ $org['naf_code'] ?? '68.10Z' }}</span>
                </span>
            </div>

            {{-- NAF Label --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['naf_label'] }}</span>
                <span class="data-value">{{ $values['naf_label'] }}</span>
            </div>

            {{-- Address --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['address'] }}</span>
                <span class="data-value">{{ $values['address'] }}</span>
            </div>

            {{-- Share Capital --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['share_capital'] }}</span>
                <span class="data-value">{{ $values['share_capital'] }}</span>
            </div>

            {{-- Founding Date --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['founding_date'] }}</span>
                <span class="data-value">{{ $values['founding_date'] }}</span>
            </div>

            {{-- RNE Registration Date --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['rne_date'] }}</span>
                <span class="data-value">{{ $values['rne_date'] }}</span>
            </div>

            {{-- Nature --}}
            <div class="data-row">
                <span class="data-label">{{ $fields['nature'] }}</span>
                <span class="data-value">{{ $values['nature'] }}</span>
            </div>

            {{-- INPI Verify Button --}}
            <div class="inpi-verify-block" id="inpi-verify-block">
                <div class="inpi-icon" aria-hidden="true">
                    <i class='bx bx-check-shield'></i>
                </div>
                <div class="inpi-text">
                    <strong>{{ __('legal.registration.inpi_label') }}</strong>
                    <span>annuaire-entreprises.data.gouv.fr</span>
                </div>
                <a href="{{ $org['inpi_url'] ?? 'https://annuaire-entreprises.data.gouv.fr/entreprise/983675331' }}"
                   id="inpi-external-link"
                   class="btn-inpi"
                   target="_blank"
                   rel="noopener noreferrer"
                   aria-label="{{ __('legal.registration.inpi_label') }}">
                    <i class='bx bx-link-external' aria-hidden="true"></i>
                    {{ __('legal.registration.inpi_button') }}
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===========================
     DISCLAIMER
     =========================== --}}
<aside class="disclaimer-section" aria-label="{{ __('legal.disclaimer.title') }}">
    <div class="container">
        <div class="disclaimer-inner">
            <i class='bx bx-info-circle disc-icon' aria-hidden="true"></i>
            <p>
                <strong>{{ __('legal.disclaimer.title') }}:</strong>
                {{ __('legal.disclaimer.text') }}
            </p>
        </div>
    </div>
</aside>

@endsection
