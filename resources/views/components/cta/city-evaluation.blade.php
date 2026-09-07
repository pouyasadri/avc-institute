@props([
    'cityName' => null,
])

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);

    $cityLabel = $cityName ? ucwords(str_replace('-', ' ', $cityName)) : '';
    if ($currentLocale === 'fa') {
        $prefillDetails = 'درخواست مشاوره اقامت، ویزا و اسکان در ' . ($cityLabel ?: 'فرانسه');
    } elseif ($currentLocale === 'fr') {
        $prefillDetails = 'Demande de conseil visa, séjour et installation à ' . ($cityLabel ?: 'France');
    } else {
        $prefillDetails = 'Inquiry regarding visa, residency, and accommodation in ' . ($cityLabel ?: 'France');
    }

    $consultUrl = url($currentLocale . '/consult?service=student-visa&details=' . urlencode($prefillDetails));
    $waPhone = '33768688326';
    $waMsg = __('cta.city.whatsapp_msg');
    $waUrl = 'https://wa.me/' . $waPhone . '?text=' . urlencode($waMsg);
@endphp

<aside class="cta-city-evaluation-banner my-5 p-4 p-md-5 rounded-4 border border-primary-subtle shadow-sm position-relative overflow-hidden"
       style="background: linear-gradient(135deg, #f8faff 0%, #edf3fc 100%);"
       aria-label="{{ __('cta.city.title') }}">
    <div class="row align-items-center g-4">
        <div class="col-lg-12">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-primary-subtle text-primary fw-semibold small">
                <i class="bx bxs-map-pin fs-5"></i>
                <span>{{ __('cta.city.badge') }}</span>
            </div>

            <h3 class="h4 fw-bold text-dark mb-3">
                {{ __('cta.city.title') }}
            </h3>

            <p class="text-secondary mb-4 leading-relaxed">
                {{ __('cta.city.subtitle') }}
            </p>

            <div class="row g-2 mb-4">
                <div class="col-md-4">
                    <div class="d-flex align-items-start gap-2 small text-dark">
                        <i class="bx bx-check-circle text-primary fs-5 mt-1 flex-shrink-0"></i>
                        <span>{{ __('cta.city.feature_1') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-start gap-2 small text-dark">
                        <i class="bx bx-check-circle text-primary fs-5 mt-1 flex-shrink-0"></i>
                        <span>{{ __('cta.city.feature_2') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-start gap-2 small text-dark">
                        <i class="bx bx-check-circle text-primary fs-5 mt-1 flex-shrink-0"></i>
                        <span>{{ __('cta.city.feature_3') }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-3 pt-2">
                <a href="{{ $consultUrl }}"
                   class="btn btn-primary rounded-pill px-4 py-3 fw-bold text-white d-inline-flex align-items-center gap-2 shadow-sm transition-all"
                   title="{{ __('cta.city.primary_btn') }}">
                    <i class="bx bx-calendar-check fs-5"></i>
                    <span>{{ __('cta.city.primary_btn') }}</span>
                    <i class="bx {{ $isRtl ? 'bx-left-arrow-alt' : 'bx-right-arrow-alt' }} fs-5"></i>
                </a>

                <a href="{{ $waUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn btn-success rounded-pill px-4 py-3 fw-bold text-white d-inline-flex align-items-center gap-2 shadow-sm transition-all"
                   title="{{ __('cta.city.whatsapp_btn') }}">
                    <i class="bx bxl-whatsapp fs-5"></i>
                    <span>{{ __('cta.city.whatsapp_btn') }}</span>
                </a>
            </div>

            <div class="mt-3 pt-3 border-top border-secondary-subtle">
                <span class="text-muted small d-inline-flex align-items-center gap-1">
                    <i class="bx bx-check-shield text-secondary"></i>
                    {{ __('cta.city.trust_note') }}
                </span>
            </div>
        </div>
    </div>
</aside>
