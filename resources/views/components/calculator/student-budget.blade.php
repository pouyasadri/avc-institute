@props([
    'initialCity' => 'paris',
    'initialAccommodation' => 'colocation',
    'initialTuition' => 'bienvenue_en_france',
    'compact' => false,
])

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);

    $benchmarkProvider = app(\App\Contracts\Calculator\StudentBudgetBenchmarkProviderInterface::class);
    $cities = $benchmarkProvider->getCities();
    $cafAllowances = config('calculator.caf_allowances', []);
    $officialMonthlyMin = $benchmarkProvider->getOfficialMonthlyMinimum();

    // Default criteria for server-side initial render
    $criteria = new \App\DTOs\Calculator\StudentBudgetCriteria(
        cityKey: $initialCity,
        accommodation: \App\Enums\Calculator\AccommodationType::tryFrom($initialAccommodation) ?? \App\Enums\Calculator\AccommodationType::Colocation,
        tuition: \App\Enums\Calculator\TuitionType::tryFrom($initialTuition) ?? \App\Enums\Calculator\TuitionType::BienvenueEnFrance,
        months: 12,
        lifestyleBuffer: 80,
    );

    $handler = app(\App\Queries\Calculator\CalculateStudentBudgetHandler::class);
    $initialResult = $handler->handle($criteria);

    $calculatorId = 'budget-calc-' . uniqid();
@endphp

<div class="avc-budget-calculator student-budget-calculator rounded-4 p-2 p-sm-3 p-md-4 my-4 position-relative" 
     id="{{ $calculatorId }}"
     data-locale="{{ $currentLocale }}"
     data-is-rtl="{{ $isRtl ? 'true' : 'false' }}">

    {{-- Header Area --}}
    <header class="text-center mb-4 pt-2">
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-2 rounded-pill avc-calc-badge fw-bold small">
            <i class="bx bxs-calculator fs-5"></i>
            <span>{{ __('calculator.badge') }}</span>
        </div>
        <h3 class="fw-bold text-dark mb-2 calc-title fs-4 fs-md-3">
            {{ __('calculator.title') }}
        </h3>
        <p class="text-muted small mx-auto mb-0" style="max-width: 640px; line-height: 1.7;">
            {{ __('calculator.subtitle') }}
        </p>
    </header>

    <div class="calc-layout-grid">
        {{-- Controls / Inputs Column --}}
        <div class="calc-controls-col">
            <div class="d-flex flex-column gap-3">

                {{-- Step 1: Destination City --}}
                <div class="calc-step-card p-3 p-sm-3 p-md-4 rounded-4 bg-white border shadow-xs">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="step-circle">1</span>
                        <label class="form-label fw-bold text-dark mb-0 small">
                            <i class="bx bxs-map-pin text-brand me-1"></i>
                            {{ __('calculator.city_label') }}
                        </label>
                    </div>
                    <div class="position-relative mt-2">
                        <select class="form-select rounded-pill px-3 px-md-4 py-2 py-md-3 fw-bold border-2 calc-city" aria-label="{{ __('calculator.city_label') }}">
                            @foreach($cities as $key => $city)
                                @php
                                    $localizedCityName = match($currentLocale) {
                                        'fa' => $city['name_fa'] ?? $key,
                                        'fr' => $city['name_fr'] ?? $key,
                                        default => $city['name_en'] ?? $key,
                                    };
                                @endphp
                                <option value="{{ $key }}" {{ strtolower($initialCity) === strtolower($key) ? 'selected' : '' }}>
                                    {{ $localizedCityName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Step 2: Accommodation (Interactive Full-Width Horizontal Cards) --}}
                <div class="calc-step-card p-3 p-sm-3 p-md-4 rounded-4 bg-white border shadow-xs">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="step-circle">2</span>
                            <label class="form-label fw-bold text-dark mb-0 small">
                                <i class="bx bxs-home-circle text-brand me-1"></i>
                                {{ __('calculator.accommodation_label') }}
                            </label>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill py-1 px-2" style="font-size: 0.72rem;">
                            <i class="bx bx-check-shield align-middle"></i> سوبسید مسکن APL
                        </span>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @php
                            $accIcons = [
                                'crous' => 'bxs-institution',
                                'colocation' => 'bxs-group',
                                'private_studio' => 'bxs-home',
                            ];
                        @endphp
                        @foreach(\App\Enums\Calculator\AccommodationType::cases() as $acc)
                            <label class="calc-card-option d-flex align-items-center justify-content-between p-2.5 p-sm-3 rounded-4 border position-relative cursor-pointer transition-all {{ $acc->value === $initialAccommodation ? 'selected' : '' }}">
                                <input type="radio" name="acc_{{ $calculatorId }}" value="{{ $acc->value }}" class="visually-hidden calc-acc" {{ $acc->value === $initialAccommodation ? 'checked' : '' }}>
                                
                                <div class="d-flex align-items-center gap-2 gap-sm-3">
                                    <div class="calc-icon-box flex-shrink-0">
                                        <i class="bx {{ $accIcons[$acc->value] ?? 'bxs-home' }} fs-4 text-brand"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">{{ $acc->label($currentLocale) }}</div>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill py-0.5 px-2 mt-1" style="font-size: 0.68rem;">
                                            +{{ $cafAllowances[$acc->value] ?? 180 }}€ کمک‌هزینه CAF
                                        </span>
                                    </div>
                                </div>

                                <div class="text-end flex-shrink-0">
                                    <div class="d-flex align-items-baseline gap-1 justify-content-end text-muted" style="font-size: 0.78rem;">
                                        <strong class="text-dark fs-6 fs-sm-5 calc-acc-rent-preview" data-acc="{{ $acc->value }}">--</strong>
                                        <span>€ / {{ $currentLocale === 'fa' ? 'ماه' : 'mo' }}</span>
                                    </div>
                                </div>

                                <div class="check-indicator position-absolute top-0 end-0 m-1.5">
                                    <i class="bx bxs-check-circle text-brand fs-5"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Step 3: University Tuition Tier --}}
                <div class="calc-step-card p-3 p-sm-3 p-md-4 rounded-4 bg-white border shadow-xs">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="step-circle">3</span>
                        <label class="form-label fw-bold text-dark mb-0 small">
                            <i class="bx bxs-graduation text-brand me-1"></i>
                            {{ __('calculator.tuition_label') }}
                        </label>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @foreach(\App\Enums\Calculator\TuitionType::cases() as $tui)
                            <label class="calc-card-option d-flex align-items-center justify-content-between p-2.5 p-sm-3 rounded-4 border cursor-pointer position-relative transition-all {{ $tui->value === $initialTuition ? 'selected' : '' }}">
                                <div class="d-flex align-items-center gap-2 gap-sm-3">
                                    <input type="radio" name="tui_{{ $calculatorId }}" value="{{ $tui->value }}" class="visually-hidden calc-tui" {{ $tui->value === $initialTuition ? 'checked' : '' }}>
                                    <div class="calc-icon-box flex-shrink-0">
                                        <i class="bx bx-book-bookmark text-brand fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">{{ $tui->label($currentLocale) }}</div>
                                    </div>
                                </div>
                                <span class="badge bg-light text-secondary border rounded-pill py-1.5 px-2.5 fw-bold small flex-shrink-0">
                                    {{ number_format($tui->annualEstimate()) }} €
                                </span>
                                <div class="check-indicator position-absolute top-0 end-0 m-1.5">
                                    <i class="bx bxs-check-circle text-brand fs-5"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Step 4: Lifestyle & Duration --}}
                <div class="calc-step-card p-3 p-sm-3 p-md-4 rounded-4 bg-white border shadow-xs">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="step-circle">4</span>
                        <label class="form-label fw-bold text-dark mb-0 small">
                            <i class="bx bx-slider-alt text-brand me-1"></i>
                            {{ __('calculator.lifestyle_label') }} & {{ __('calculator.duration_label') }}
                        </label>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <div>
                            <span class="text-muted small d-block mb-2">{{ __('calculator.lifestyle_label') }}</span>
                            <div class="calc-segmented-group">
                                <label class="calc-segmented-btn">
                                    <input type="radio" name="life_{{ $calculatorId }}" value="0" class="visually-hidden calc-lifestyle-radio">
                                    <span>{{ __('calculator.lifestyle_economic') }}</span>
                                </label>
                                <label class="calc-segmented-btn active">
                                    <input type="radio" name="life_{{ $calculatorId }}" value="80" class="visually-hidden calc-lifestyle-radio" checked>
                                    <span>{{ __('calculator.lifestyle_moderate') }}</span>
                                </label>
                                <label class="calc-segmented-btn">
                                    <input type="radio" name="life_{{ $calculatorId }}" value="160" class="visually-hidden calc-lifestyle-radio">
                                    <span>{{ __('calculator.lifestyle_comfortable') }}</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted small d-block mb-2">{{ __('calculator.duration_label') }}</span>
                            <div class="calc-segmented-group">
                                <label class="calc-segmented-btn active">
                                    <input type="radio" name="months_{{ $calculatorId }}" value="12" class="visually-hidden calc-months-radio" checked>
                                    <span>۱۲ {{ $currentLocale === 'fa' ? 'ماهه' : 'mo' }}</span>
                                </label>
                                <label class="calc-segmented-btn">
                                    <input type="radio" name="months_{{ $calculatorId }}" value="10" class="visually-hidden calc-months-radio">
                                    <span>۱۰ {{ $currentLocale === 'fa' ? 'ماهه' : 'mo' }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Results Column --}}
        <div class="calc-results-col">
            <div class="calc-results-sidebar d-flex flex-column gap-3">
                
                {{-- Primary Highlight Card --}}
                <div class="p-3 p-sm-3 p-md-4 rounded-4 text-center border shadow-sm position-relative overflow-hidden calc-hero-card">
                    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 mb-3">
                        <span class="small fw-bold text-uppercase tracking-wider text-muted">
                            {{ __('calculator.results_heading') }}
                        </span>
                        <span class="badge rounded-pill px-3 py-1.5 small calc-risk-badge {{ $initialResult->visaRiskLevel->badgeClass() }}">
                            <i class="{{ $initialResult->visaRiskLevel->icon() }} me-1 align-middle"></i>
                            <span class="calc-risk-text">{{ $initialResult->visaRiskLevel->label($currentLocale) }}</span>
                        </span>
                    </div>

                    {{-- Big Metric --}}
                    <div class="my-3 py-1">
                        <span class="small text-muted d-block mb-1">{{ __('calculator.net_monthly_title') }}</span>
                        <div class="display-6 display-md-5 fw-bold text-brand mb-1 tracking-tight">
                            <span class="calc-val-net-monthly">{{ number_format($initialResult->netMonthlyLivingCost) }}</span>
                            <span class="fs-4">€</span>
                            <span class="fs-6 text-muted fw-normal">/ {{ $currentLocale === 'fa' ? 'ماه' : ($currentLocale === 'fr' ? 'mois' : 'mo') }}</span>
                        </div>
                        <div class="d-inline-flex flex-wrap align-items-center justify-content-center gap-1 bg-success-subtle text-success px-3 py-1.5 rounded-pill fw-semibold small mt-1 text-wrap" style="max-width: 100%; font-size: 0.78rem;">
                            <i class="bx bx-gift fs-6"></i>
                            <span>{{ __('calculator.caf_subsidy_title') }}: <strong class="calc-val-caf">-{{ number_format($initialResult->cafDeduction) }}€</strong></span>
                        </div>
                    </div>

                    {{-- 2 Sub-Metrics: Official vs Recommended --}}
                    <div class="row g-2 pt-3 border-top text-start">
                        <div class="col-12 col-sm-6">
                            <div class="p-2 px-3 rounded-3 bg-white border h-100">
                                <span class="d-block text-muted" style="font-size: 0.72rem;">{{ __('calculator.official_proof_title') }}</span>
                                <strong class="text-dark fs-6 d-block mt-1"><span class="calc-val-official-proof">{{ number_format($initialResult->officialAnnualVisaProof) }}</span> €</strong>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-2 px-3 rounded-3 bg-white border border-brand-subtle h-100">
                                <span class="d-block text-brand" style="font-size: 0.72rem;">{{ __('calculator.recommended_proof_title') }}</span>
                                <strong class="text-brand fs-6 d-block mt-1"><span class="calc-val-recommended-proof">{{ number_format($initialResult->recommendedAnnualSafetyProof) }}</span> €</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detailed Breakdown Card --}}
                <div class="p-3 p-sm-3 p-md-4 rounded-4 bg-white border shadow-xs">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                        <span class="fw-bold text-dark small">{{ __('calculator.breakdown_title') }}</span>
                        <div class="small bg-light px-2.5 py-1 rounded-pill border text-secondary text-wrap" style="font-size: 0.74rem;">
                            {{ __('calculator.first_year_total_title') }}: <strong class="text-brand fs-6 calc-val-first-year">{{ number_format($initialResult->firstYearTotalNetBudget) }}</strong> €
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2 small text-secondary">
                        <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom border-light w-100 min-w-0">
                            <span class="text-truncate me-2"><i class="bx bx-home-alt me-1 text-muted"></i> {{ __('calculator.item_rent') }}</span>
                            <strong class="text-dark flex-shrink-0 ms-2"><span class="calc-item-rent">{{ number_format($initialResult->monthlyBreakdown['rent']) }}</span> €</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom border-light w-100 min-w-0">
                            <span class="text-truncate me-2"><i class="bx bx-restaurant me-1 text-muted"></i> {{ __('calculator.item_food') }}</span>
                            <strong class="text-dark flex-shrink-0 ms-2"><span class="calc-item-food">{{ number_format($initialResult->monthlyBreakdown['food']) }}</span> €</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom border-light w-100 min-w-0">
                            <span class="text-truncate me-2"><i class="bx bx-bus me-1 text-muted"></i> {{ __('calculator.item_transport') }} + {{ __('calculator.item_health_phone') }}</span>
                            <strong class="text-dark flex-shrink-0 ms-2"><span class="calc-item-misc">{{ number_format($initialResult->monthlyBreakdown['transport'] + $initialResult->monthlyBreakdown['health_phone']) }}</span> €</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-1.5 text-success fw-semibold w-100 min-w-0">
                            <span class="text-truncate me-2"><i class="bx bx-check-circle me-1"></i> {{ __('calculator.item_caf_deduction') }}</span>
                            <span class="flex-shrink-0 ms-2">-<span class="calc-item-caf">{{ number_format($initialResult->cafDeduction) }}</span> €</span>
                        </div>
                    </div>
                </div>

                {{-- Advisory & Legal Guidance Disclaimer Card (As requested by user) --}}
                <div class="p-3 p-sm-3 p-md-4 rounded-4 border calc-advisory-card shadow-xs position-relative">
                    <div class="d-flex align-items-start gap-2.5">
                        <div class="flex-shrink-0 mt-1">
                            <i class="bx bxs-shield-error fs-3 text-warning"></i>
                        </div>
                        <div>
                            <h5 class="h6 fw-bold text-dark mb-1" style="font-size: 0.88rem; line-height: 1.45;">
                                {{ __('calculator.advisory_title') }}
                            </h5>
                            <p class="small text-secondary mb-0" style="font-size: 0.8rem; line-height: 1.65;">
                                {{ __('calculator.advisory_text') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Primary Consultation CTA Button --}}
                <div class="pt-1">
                    @php
                        $prefillMessage = match($currentLocale) {
                            'fa' => "درخواست بررسی پرونده ویزای تحصیلی برای شهر " . ($cities[$initialCity]['name_fa'] ?? $initialCity) . " با برآورد بودجه ماهانه " . $initialResult->netMonthlyLivingCost . " یورو و تمکن سالانه " . $initialResult->officialAnnualVisaProof . " یورو.",
                            'fr' => "Demande d'évaluation de dossier visa étudiant pour " . ($cities[$initialCity]['name_fr'] ?? $initialCity) . " avec budget mensuel estimé à " . $initialResult->netMonthlyLivingCost . " € et ressources annuelles de " . $initialResult->officialAnnualVisaProof . " €.",
                            default => "Student visa case evaluation inquiry for " . ($cities[$initialCity]['name_en'] ?? $initialCity) . " with estimated monthly budget of " . $initialResult->netMonthlyLivingCost . " EUR and annual proof of " . $initialResult->officialAnnualVisaProof . " EUR.",
                        };
                        $consultTargetUrl = url($currentLocale . '/consult?service=student-visa&details=' . urlencode($prefillMessage));
                    @endphp

                    <a href="{{ $consultTargetUrl }}" 
                       class="btn btn-brand w-100 rounded-pill py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 calc-consult-link transition-all"
                       data-base-url="{{ url($currentLocale . '/consult') }}">
                        <i class="bx bx-paper-plane fs-5"></i>
                        <span>{{ __('calculator.cta_button') }}</span>
                    </a>
                    
                    <div class="text-center mt-2">
                        <span class="text-muted" style="font-size: 0.72rem;">
                            <i class="bx bx-shield-quarter align-middle me-1"></i>
                            {{ __('calculator.disclaimer') }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Custom Scoped Calculator Styles --}}
<style>
    .avc-budget-calculator {
        container-type: inline-size;
        container-name: calc-box;
        background: linear-gradient(160deg, #ffffff 0%, #fcfcfd 100%);
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
        scroll-margin-top: 100px;
        overflow-x: hidden;
    }
    .calc-layout-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    @container calc-box (min-width: 860px) {
        .calc-layout-grid {
            grid-template-columns: 1fr 370px;
            gap: 1.75rem;
            align-items: start;
        }
        .calc-results-sidebar {
            position: sticky;
            top: 100px;
        }
    }
    .avc-calc-badge {
        background-color: #fff3ed;
        color: #ff5d22;
        border: 1px solid rgba(255, 93, 34, 0.2);
    }
    .text-brand {
        color: #ff5d22 !important;
    }
    .border-brand-subtle {
        border-color: rgba(255, 93, 34, 0.3) !important;
    }
    .step-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: #ff5d22;
        color: #fff;
        font-weight: 700;
        font-size: 0.8rem;
    }
    .calc-icon-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background-color: #fff3ed;
    }
    .calc-card-option {
        cursor: pointer;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        transition: all 0.2s ease-in-out;
    }
    .calc-card-option:hover {
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }
    .calc-card-option.selected {
        border-color: #ff5d22 !important;
        background-color: #fffaf7 !important;
        box-shadow: 0 3px 12px rgba(255, 93, 34, 0.1) !important;
    }
    .calc-card-option .check-indicator {
        display: none;
    }
    .calc-card-option.selected .check-indicator {
        display: block;
    }
    .calc-segmented-group {
        display: flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 9999px;
        border: 1px solid #e2e8f0;
        gap: 4px;
    }
    .calc-segmented-btn {
        flex: 1 1 0;
        text-align: center;
        padding: 7px 6px;
        border-radius: 9999px;
        font-size: 0.76rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .calc-segmented-btn.active {
        background: #ffffff;
        color: #ff5d22;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .calc-hero-card {
        background: linear-gradient(135deg, #ffffff 0%, #fff9f6 100%);
        border-color: #ffeedb !important;
    }
    .calc-advisory-card {
        background-color: #fffbf0;
        border: 1.5px solid #fed7aa !important;
    }
    .btn-brand {
        background: linear-gradient(135deg, #ff5d22 0%, #e84e13 100%);
        color: #ffffff !important;
        border: none;
        box-shadow: 0 4px 15px rgba(255, 93, 34, 0.3);
    }
    .btn-brand:hover {
        background: linear-gradient(135deg, #e84e13 0%, #cf400b 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 93, 34, 0.4);
    }
    .calc-risk-badge {
        white-space: normal;
        line-height: 1.4;
        text-align: center;
    }
    [dir="rtl"] .calc-city,
    [data-is-rtl="true"] .calc-city {
        background-position: left 0.75rem center !important;
        padding-left: 2.25rem !important;
        padding-right: 1.25rem !important;
        text-align: right !important;
        direction: rtl !important;
    }
</style>

{{-- Reactive Client-Side Calculator Logic (Pure Vanilla JS, Zero Dependencies) --}}
@once
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calcConfig = @json(config('calculator'));
    const tuitionRates = {
        'exonerated': 243,
        'bienvenue_en_france': 3879,
        'private_school': 9500
    };

    function initCalculator(container) {
        const locale = container.getAttribute('data-locale') || 'en';
        const citySelect = container.querySelector('.calc-city');
        const consultLink = container.querySelector('.calc-consult-link');
        const baseUrl = consultLink.getAttribute('data-base-url');

        function getSelectedLifestyle() {
            const checked = container.querySelector('input.calc-lifestyle-radio:checked');
            return checked ? parseInt(checked.value, 10) : 80;
        }

        function getSelectedMonths() {
            const checked = container.querySelector('input.calc-months-radio:checked');
            return checked ? parseInt(checked.value, 10) : 12;
        }

        function recalculate() {
            const cityKey = citySelect.value.toLowerCase();
            const cityData = (calcConfig.cities && calcConfig.cities[cityKey]) || calcConfig.cities['other'] || {};
            
            const accChecked = container.querySelector('input.calc-acc:checked');
            const accType = accChecked ? accChecked.value : 'colocation';

            const tuiChecked = container.querySelector('input.calc-tui:checked');
            const tuiType = tuiChecked ? tuiChecked.value : 'bienvenue_en_france';

            const lifestyleBuffer = getSelectedLifestyle();
            const months = getSelectedMonths();

            // Highlight active accommodation cards
            container.querySelectorAll('input.calc-acc').forEach(radio => {
                const card = radio.closest('.calc-card-option');
                if (card) {
                    if (radio.checked) {
                        card.classList.add('selected');
                    } else {
                        card.classList.remove('selected');
                    }
                }
            });

            // Highlight active tuition cards
            container.querySelectorAll('input.calc-tui').forEach(radio => {
                const card = radio.closest('.calc-card-option');
                if (card) {
                    if (radio.checked) {
                        card.classList.add('selected');
                    } else {
                        card.classList.remove('selected');
                    }
                }
            });

            // Highlight segmented buttons
            container.querySelectorAll('input.calc-lifestyle-radio').forEach(radio => {
                const btn = radio.closest('.calc-segmented-btn');
                if (btn) {
                    if (radio.checked) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                }
            });

            container.querySelectorAll('input.calc-months-radio').forEach(radio => {
                const btn = radio.closest('.calc-segmented-btn');
                if (btn) {
                    if (radio.checked) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                }
            });

            // Update rent previews on accommodation cards based on city
            const rents = cityData.rents || { crous: 250, colocation: 390, private_studio: 500 };
            container.querySelectorAll('.calc-acc-rent-preview').forEach(el => {
                const acc = el.getAttribute('data-acc');
                if (acc && rents[acc]) {
                    el.textContent = rents[acc];
                }
            });

            const rent = rents[accType] || 450;
            const food = cityData.food || 230;
            const transport = cityData.transport || 35;
            const healthPhone = cityData.health_phone || 40;
            const cafAllowances = calcConfig.caf_allowances || { crous: 150, colocation: 180, private_studio: 210 };
            const caf = cafAllowances[accType] || 180;

            const grossMonthly = rent + food + transport + healthPhone + lifestyleBuffer;
            const netMonthly = Math.max(0, grossMonthly - caf);

            const officialMonthlyMin = calcConfig.official_monthly_minimum || 615;
            const officialAnnual = officialMonthlyMin * months;

            const recommendedMonthly = cityData.recommended_monthly_min || 700;
            const recommendedAnnual = recommendedMonthly * months;

            const tuitionAnnual = tuitionRates[tuiType] || 3879;
            const firstYearNetTotal = (netMonthly * months) + tuitionAnnual;

            // Formatter
            const fmt = (num) => new Intl.NumberFormat(locale === 'fa' ? 'fa-IR' : 'fr-FR').format(num);

            const netMonthlyEl = container.querySelector('.calc-val-net-monthly');
            if (netMonthlyEl) netMonthlyEl.textContent = fmt(netMonthly);

            const cafEl = container.querySelector('.calc-val-caf');
            if (cafEl) cafEl.textContent = '-' + fmt(caf) + '€';

            const officialProofEl = container.querySelector('.calc-val-official-proof');
            if (officialProofEl) officialProofEl.textContent = fmt(officialAnnual);

            const recommendedProofEl = container.querySelector('.calc-val-recommended-proof');
            if (recommendedProofEl) recommendedProofEl.textContent = fmt(recommendedAnnual);

            const firstYearEl = container.querySelector('.calc-val-first-year');
            if (firstYearEl) firstYearEl.textContent = fmt(firstYearNetTotal);

            // Breakdown
            const rentEl = container.querySelector('.calc-item-rent');
            if (rentEl) rentEl.textContent = fmt(rent);

            const foodEl = container.querySelector('.calc-item-food');
            if (foodEl) foodEl.textContent = fmt(food);

            const miscEl = container.querySelector('.calc-item-misc');
            if (miscEl) miscEl.textContent = fmt(transport + healthPhone);

            const itemCafEl = container.querySelector('.calc-item-caf');
            if (itemCafEl) itemCafEl.textContent = fmt(caf);

            // Risk Assessment Badge
            const riskBadge = container.querySelector('.calc-risk-badge');
            const riskText = container.querySelector('.calc-risk-text');
            const riskIcon = riskBadge.querySelector('i');

            let riskClass = 'bg-danger text-white';
            let iconClass = 'bx bx-shield-x';
            let labelText = '';

            if (grossMonthly >= (recommendedMonthly + 100)) {
                riskClass = 'bg-success text-white';
                iconClass = 'bx bx-check-double';
                labelText = locale === 'fa' ? 'ضریب اطمینان ویزا: ایده‌آل (بسیار قوی)' : (locale === 'fr' ? 'Sécurité visa : Optimale' : 'Visa Safety: Optimal');
            } else if (grossMonthly >= recommendedMonthly) {
                riskClass = 'bg-primary text-white';
                iconClass = 'bx bx-check-circle';
                labelText = locale === 'fa' ? 'ضریب اطمینان ویزا: مطلوب (تطابق استاندارد)' : (locale === 'fr' ? 'Sécurité visa : Conforme' : 'Visa Safety: Sufficient');
            } else if (grossMonthly >= officialMonthlyMin) {
                riskClass = 'bg-warning text-dark';
                iconClass = 'bx bx-error-circle';
                labelText = locale === 'fa' ? 'ضریب اطمینان ویزا: مرزی (پیشنهاد افزایش تمکن)' : (locale === 'fr' ? 'Sécurité visa : Juste limite' : 'Visa Safety: Borderline');
            } else {
                riskClass = 'bg-danger text-white';
                iconClass = 'bx bx-shield-x';
                labelText = locale === 'fa' ? 'ضریب اطمینان ویزا: ریسک بالا (کمتر از کف سفارت)' : (locale === 'fr' ? 'Sécurité visa : Risqué' : 'Visa Safety: High Risk');
            }

            riskBadge.className = 'badge rounded-pill px-3 py-1 small calc-risk-badge ' + riskClass;
            riskIcon.className = iconClass + ' me-1 align-middle';
            riskText.textContent = labelText;

            // Update Consult Link Pre-fill
            const cityName = (locale === 'fa' ? cityData.name_fa : (locale === 'fr' ? cityData.name_fr : cityData.name_en)) || cityKey;
            let detailsMsg = '';
            if (locale === 'fa') {
                detailsMsg = `درخواست بررسی پرونده ویزای تحصیلی برای شهر ${cityName} با برآورد بودجه ماهانه ${netMonthly} یورو و تمکن سالانه ${officialAnnual} یورو.`;
            } else if (locale === 'fr') {
                detailsMsg = `Demande d'évaluation visa pour ${cityName} avec budget mensuel de ${netMonthly} € et ressources annuelles de ${officialAnnual} €.`;
            } else {
                detailsMsg = `Student visa inquiry for ${cityName} with monthly budget of ${netMonthly} EUR and annual proof of ${officialAnnual} EUR.`;
            }
            consultLink.href = baseUrl + '?service=student-visa&details=' + encodeURIComponent(detailsMsg);
        }

        // Event listeners
        citySelect.addEventListener('change', recalculate);

        container.querySelectorAll('input.calc-acc').forEach(radio => radio.addEventListener('change', recalculate));
        container.querySelectorAll('input.calc-tui').forEach(radio => radio.addEventListener('change', recalculate));
        container.querySelectorAll('input.calc-lifestyle-radio').forEach(radio => radio.addEventListener('change', recalculate));
        container.querySelectorAll('input.calc-months-radio').forEach(radio => radio.addEventListener('change', recalculate));

        recalculate();
    }

    document.querySelectorAll('.avc-budget-calculator').forEach(initCalculator);
});
</script>
@endonce
