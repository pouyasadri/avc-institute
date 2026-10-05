@props([
    'cityName' => null,
    'class' => '',
])

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
    
    // Normalize city key for query param
    $cityKey = $cityName ? strtolower(trim((string) $cityName)) : null;
    
    // Resolve localized city display name if valid city
    $availableCities = config('calculator.cities', []);
    $cityData = $cityKey && isset($availableCities[$cityKey]) ? $availableCities[$cityKey] : null;
    
    $cityDisplayName = $cityData ? ($cityData['name_' . $currentLocale] ?? $cityData['name_en'] ?? $cityKey) : $cityName;
    
    $title = $cityDisplayName 
        ? __('calculator.teaser_title', ['city' => $cityDisplayName])
        : __('calculator.title');

    $targetUrl = route('calculator', array_filter([
        'locale' => $currentLocale,
        'city' => $cityKey,
    ]));
@endphp

<div class="avc-calculator-teaser rounded-4 p-4 p-md-5 my-4 border shadow-sm position-relative overflow-hidden {{ $class }}">
    <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
        <div class="col-lg-8 col-md-12">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill teaser-badge fw-bold small">
                <i class="bx bxs-calculator fs-5"></i>
                <span>{{ __('calculator.teaser_badge') }}</span>
            </div>
            
            <h3 class="fw-bold text-dark mb-2 teaser-title fs-4">
                {{ $title }}
            </h3>
            
            <p class="text-secondary small mb-3 mb-md-4" style="line-height: 1.7; max-width: 620px;">
                {{ __('calculator.teaser_desc') }}
            </p>

            <div class="d-flex flex-wrap gap-2 gap-md-3">
                <span class="badge bg-white text-dark border rounded-pill py-2 px-3 fw-semibold small shadow-xs d-inline-flex align-items-center gap-1.5">
                    <i class="bx bxs-badge-check text-brand"></i>
                    <span>{{ $currentLocale === 'fa' ? 'کف سفارت: ۸۷۷٫۵۰€ در ماه' : 'Embassy Min: 877.50€/mo' }}</span>
                </span>
                <span class="badge bg-white text-dark border rounded-pill py-2 px-3 fw-semibold small shadow-xs d-inline-flex align-items-center gap-1.5">
                    <i class="bx bx-gift text-success"></i>
                    <span>{{ $currentLocale === 'fa' ? 'سوبسید CAF: تا ۲۱۰€' : 'CAF Subsidy: up to 210€' }}</span>
                </span>
                <span class="badge bg-white text-dark border rounded-pill py-2 px-3 fw-semibold small shadow-xs d-inline-flex align-items-center gap-1.5">
                    <i class="bx bx-shield-quarter text-primary"></i>
                    <span>{{ $currentLocale === 'fa' ? 'محاسبه ضریب ریسک ویزا' : 'Visa Risk Calculation' }}</span>
                </span>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 text-lg-end text-center">
            <a href="{{ $targetUrl }}" class="btn btn-brand rounded-pill px-4 py-3 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center gap-2 teaser-btn transition-all">
                <span>{{ __('calculator.teaser_btn') }}</span>
                <i class="bx {{ $isRtl ? 'bx-left-arrow-alt' : 'bx-right-arrow-alt' }} fs-4"></i>
            </a>
            <div class="mt-2 text-muted" style="font-size: 0.72rem;">
                {{ $currentLocale === 'fa' ? 'کاملاً رایگان • بدون نیاز به ثبت‌نام' : '100% Free • Instant Simulation' }}
            </div>
        </div>
    </div>
</div>

<style>
    .avc-calculator-teaser {
        background: linear-gradient(135deg, #ffffff 0%, #fff9f6 100%);
        border: 1.5px solid #ffeedb !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .avc-calculator-teaser:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(255, 93, 34, 0.08) !important;
    }
    .teaser-badge {
        background-color: #fff3ed;
        color: #ff5d22;
        border: 1px solid rgba(255, 93, 34, 0.25);
    }
    .text-brand {
        color: #ff5d22 !important;
    }
    .teaser-btn {
        background: linear-gradient(135deg, #ff5d22 0%, #e84e13 100%);
        color: #ffffff !important;
        border: none;
        box-shadow: 0 4px 15px rgba(255, 93, 34, 0.3);
    }
    .teaser-btn:hover {
        background: linear-gradient(135deg, #e84e13 0%, #cf400b 100%);
        transform: scale(1.02);
        box-shadow: 0 8px 22px rgba(255, 93, 34, 0.4);
    }
</style>
