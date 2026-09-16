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

<div class="avc-budget-calculator student-budget-calculator rounded-4 p-3 p-md-4 my-4 border shadow-sm bg-white mx-auto" 
     style="max-width: 800px;"
     id="{{ $calculatorId }}"
     data-locale="{{ $currentLocale }}"
     data-is-rtl="{{ $isRtl ? 'true' : 'false' }}">

    {{-- Header Area --}}
    <header class="text-center mb-5 pt-2">
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

    <div class="d-flex flex-column gap-5">
        {{-- Controls / Inputs Section --}}
        <div>
            <div class="d-flex flex-column gap-4">

                {{-- Step 1: Destination City --}}
                <div class="calc-step-section">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="step-circle">1</span>
                        <label class="form-label fw-bold text-dark mb-0 fs-6">
                            {{ __('calculator.city_label') }}
                        </label>
                    </div>
                    <div class="position-relative ms-sm-4 ms-0 ps-sm-3 ps-0 border-start-sm border-2 border-light">
                        <select class="form-select form-select-lg rounded-3 fw-bold border-2 calc-city shadow-none" aria-label="{{ __('calculator.city_label') }}">
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

                {{-- Step 2: Accommodation --}}
                <div class="calc-step-section">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="step-circle">2</span>
                            <label class="form-label fw-bold text-dark mb-0 fs-6">
                                {{ __('calculator.accommodation_label') }}
                            </label>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill py-1 px-3 d-inline-flex align-items-center gap-1">
                            <i class="bx bx-check-shield fs-6"></i> سوبسید مسکن APL
                        </span>
                    </div>

                    <div class="ms-sm-4 ms-0 ps-sm-3 ps-0 border-start-sm border-2 border-light">
                        <div class="d-flex flex-column gap-3">
                            @php
                                $accIcons = [
                                    'crous' => 'bxs-institution',
                                    'colocation' => 'bxs-group',
                                    'private_studio' => 'bxs-home',
                                ];
                            @endphp
                            @foreach(\App\Enums\Calculator\AccommodationType::cases() as $acc)
                                <label class="calc-card-option d-block p-3 p-md-4 rounded-4 border position-relative cursor-pointer transition-all {{ $acc->value === $initialAccommodation ? 'selected' : '' }}">
                                    <input type="radio" name="acc_{{ $calculatorId }}" value="{{ $acc->value }}" class="visually-hidden calc-acc" {{ $acc->value === $initialAccommodation ? 'checked' : '' }}>
                                    
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="calc-icon-box flex-shrink-0">
                                            <i class="bx {{ $accIcons[$acc->value] ?? 'bxs-home' }} fs-3 text-brand"></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-bold text-dark fs-6">{{ $acc->label($currentLocale) }}</div>
                                            
                                            <div class="d-flex flex-wrap align-items-center justify-content-between mt-2 gap-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-success text-white rounded-pill py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                                        <i class="bx bx-plus"></i>
                                                        {{ $cafAllowances[$acc->value] ?? 180 }}€ CAF
                                                    </span>
                                                </div>
                                                
                                                <div class="text-muted d-flex align-items-baseline gap-1" style="font-size: 0.85rem;">
                                                    <strong class="text-dark fs-5 calc-acc-rent-preview" data-acc="{{ $acc->value }}">--</strong>
                                                    <span>€ / {{ $currentLocale === 'fa' ? 'ماه' : 'mo' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Step 3: University Tuition Tier --}}
                <div class="calc-step-section">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="step-circle">3</span>
                        <label class="form-label fw-bold text-dark mb-0 fs-6">
                            {{ __('calculator.tuition_label') }}
                        </label>
                    </div>

                    <div class="ms-sm-4 ms-0 ps-sm-3 ps-0 border-start-sm border-2 border-light">
                        <div class="row g-3">
                            @foreach(\App\Enums\Calculator\TuitionType::cases() as $tui)
                                <div class="col-12 col-md-6">
                                    <label class="calc-card-option h-100 d-block p-3 rounded-4 border cursor-pointer position-relative transition-all {{ $tui->value === $initialTuition ? 'selected' : '' }}">
                                        <input type="radio" name="tui_{{ $calculatorId }}" value="{{ $tui->value }}" class="visually-hidden calc-tui" {{ $tui->value === $initialTuition ? 'checked' : '' }}>
                                        <div class="d-flex flex-column gap-2 text-center h-100 align-items-center justify-content-center">
                                            <div class="calc-icon-box mx-auto mb-1 rounded-circle">
                                                <i class="bx bx-book-bookmark text-brand fs-4"></i>
                                            </div>
                                            <div class="fw-bold text-dark lh-sm" style="font-size: 0.95rem;">{{ $tui->label($currentLocale) }}</div>
                                            <span class="badge bg-light text-secondary border rounded-pill py-1.5 px-3 fw-bold mt-auto w-auto">
                                                {{ number_format($tui->annualEstimate()) }} €
                                            </span>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Step 4: Lifestyle & Duration --}}
                <div class="calc-step-section">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="step-circle">4</span>
                        <label class="form-label fw-bold text-dark mb-0 fs-6">
                            {{ __('calculator.lifestyle_label') }} <span class="mx-1 text-muted fw-normal">/</span> {{ __('calculator.duration_label') }}
                        </label>
                    </div>

                    <div class="ms-sm-4 ms-0 ps-sm-3 ps-0 border-start-sm border-2 border-light">
                        <div class="row g-4">
                            <div class="col-12 col-xl-7">
                                <span class="text-muted small d-block mb-3 fw-semibold">{{ __('calculator.lifestyle_label') }}</span>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <label class="calc-card-option h-100 w-100 d-block p-2 rounded-3 border cursor-pointer position-relative text-center transition-all">
                                            <input type="radio" name="life_{{ $calculatorId }}" value="0" class="visually-hidden calc-lifestyle-radio">
                                            <div class="d-flex flex-column align-items-center justify-content-center h-100 gap-1 py-1">
                                                <i class="bx bx-coffee text-muted fs-4 calc-mini-icon"></i>
                                                <span class="fw-semibold lh-sm text-secondary" style="font-size: 0.82rem;">{{ __('calculator.lifestyle_economic') }}</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="calc-card-option h-100 w-100 d-block p-2 rounded-3 border cursor-pointer position-relative text-center transition-all selected">
                                            <input type="radio" name="life_{{ $calculatorId }}" value="80" class="visually-hidden calc-lifestyle-radio" checked>
                                            <div class="d-flex flex-column align-items-center justify-content-center h-100 gap-1 py-1">
                                                <i class="bx bx-shopping-bag text-muted fs-4 calc-mini-icon"></i>
                                                <span class="fw-semibold lh-sm text-secondary" style="font-size: 0.82rem;">{{ __('calculator.lifestyle_moderate') }}</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="calc-card-option h-100 w-100 d-block p-2 rounded-3 border cursor-pointer position-relative text-center transition-all">
                                            <input type="radio" name="life_{{ $calculatorId }}" value="160" class="visually-hidden calc-lifestyle-radio">
                                            <div class="d-flex flex-column align-items-center justify-content-center h-100 gap-1 py-1">
                                                <i class="bx bx-restaurant text-muted fs-4 calc-mini-icon"></i>
                                                <span class="fw-semibold lh-sm text-secondary" style="font-size: 0.82rem;">{{ __('calculator.lifestyle_comfortable') }}</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xl-5">
                                <span class="text-muted small d-block mb-3 fw-semibold">{{ __('calculator.duration_label') }}</span>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="calc-card-option h-100 w-100 d-block p-2 rounded-3 border cursor-pointer position-relative text-center transition-all selected">
                                            <input type="radio" name="months_{{ $calculatorId }}" value="12" class="visually-hidden calc-months-radio" checked>
                                            <div class="d-flex flex-column align-items-center justify-content-center h-100 gap-1 py-1">
                                                <i class="bx bx-calendar-check text-muted fs-4 calc-mini-icon"></i>
                                                <span class="fw-semibold lh-sm text-secondary" style="font-size: 0.82rem;">۱۲ {{ $currentLocale === 'fa' ? 'ماهه' : 'mo' }}</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <label class="calc-card-option h-100 w-100 d-block p-2 rounded-3 border cursor-pointer position-relative text-center transition-all">
                                            <input type="radio" name="months_{{ $calculatorId }}" value="10" class="visually-hidden calc-months-radio">
                                            <div class="d-flex flex-column align-items-center justify-content-center h-100 gap-1 py-1">
                                                <i class="bx bx-calendar text-muted fs-4 calc-mini-icon"></i>
                                                <span class="fw-semibold lh-sm text-secondary" style="font-size: 0.82rem;">۱۰ {{ $currentLocale === 'fa' ? 'ماهه' : 'mo' }}</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Results Section --}}
        <div>
            <div>
                <div class="d-flex flex-column gap-4">
                    
                    {{-- Primary Highlight Card --}}
                    <div class="p-4 rounded-4 text-center border shadow-sm position-relative overflow-hidden calc-hero-card bg-white">
                        <div class="d-flex flex-column align-items-center gap-3 mb-4">
                            <span class="small fw-bold text-uppercase tracking-wider text-muted">
                                {{ __('calculator.results_heading') }}
                            </span>
                            <span class="badge rounded-pill px-3 py-2 small calc-risk-badge {{ $initialResult->visaRiskLevel->badgeClass() }} d-inline-flex align-items-center gap-1 w-100 justify-content-center">
                                <i class="{{ $initialResult->visaRiskLevel->icon() }} fs-6"></i>
                                <span class="calc-risk-text">{{ $initialResult->visaRiskLevel->label($currentLocale) }}</span>
                            </span>
                        </div>

                        {{-- Big Metric --}}
                        <div class="my-4 py-2 border-bottom border-light">
                            <span class="small text-muted d-block mb-2 fw-semibold">{{ __('calculator.net_monthly_title') }}</span>
                            <div class="display-5 fw-bold text-brand mb-2 tracking-tight d-flex justify-content-center align-items-baseline gap-1">
                                <span class="calc-val-net-monthly">{{ number_format($initialResult->netMonthlyLivingCost) }}</span>
                                <span>€</span>
                                <span class="fs-6 text-muted fw-normal ms-1">/ {{ $currentLocale === 'fa' ? 'ماه' : ($currentLocale === 'fr' ? 'mois' : 'mo') }}</span>
                            </div>
                            <div class="d-inline-flex align-items-center justify-content-center gap-1 bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold small mt-2 w-100">
                                <i class="bx bx-gift fs-5"></i>
                                <span>{{ __('calculator.caf_subsidy_title') }}: <strong class="calc-val-caf">-{{ number_format($initialResult->cafDeduction) }}€</strong></span>
                            </div>
                        </div>

                        {{-- 2 Sub-Metrics: Official vs Recommended --}}
                        <div class="row g-3 text-start">
                            <div class="col-6">
                                <div class="p-3 rounded-4 bg-light border border-light h-100 transition-all hover-shadow">
                                    <span class="d-block text-muted fw-semibold mb-1" style="font-size: 0.75rem;">{{ __('calculator.official_proof_title') }}</span>
                                    <strong class="text-dark fs-5 d-block"><span class="calc-val-official-proof">{{ number_format($initialResult->officialAnnualVisaProof) }}</span> €</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4 bg-brand-subtle border border-brand-subtle h-100 transition-all hover-shadow">
                                    <span class="d-block text-brand fw-semibold mb-1" style="font-size: 0.75rem;">{{ __('calculator.recommended_proof_title') }}</span>
                                    <strong class="text-brand fs-5 d-block"><span class="calc-val-recommended-proof">{{ number_format($initialResult->recommendedAnnualSafetyProof) }}</span> €</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detailed Breakdown Card --}}
                    <div class="p-3 p-md-4 rounded-4 bg-white border shadow-sm">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                            <span class="fw-bold text-dark fs-6">{{ __('calculator.breakdown_title') }}</span>
                            <div class="small bg-light px-3 py-1.5 rounded-pill border border-light text-secondary d-inline-flex align-items-center gap-1">
                                {{ __('calculator.first_year_total_title') }}: <strong class="text-brand fs-6 ms-1 calc-val-first-year">{{ number_format($initialResult->firstYearTotalNetBudget) }}</strong> €
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-0">
                            <div class="d-flex justify-content-between align-items-start py-3 border-bottom border-light gap-2">
                                <div class="d-flex align-items-start gap-2 text-secondary flex-grow-1 pe-2">
                                    <i class="bx bx-home-alt fs-5 text-muted mt-1 flex-shrink-0"></i>
                                    <span class="lh-base" style="font-size: 0.95rem;">{{ __('calculator.item_rent') }}</span>
                                </div>
                                <strong class="text-dark flex-shrink-0 mt-1 text-nowrap"><span class="calc-item-rent">{{ number_format($initialResult->monthlyBreakdown['rent']) }}</span> €</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-start py-3 border-bottom border-light gap-2">
                                <div class="d-flex align-items-start gap-2 text-secondary flex-grow-1 pe-2">
                                    <i class="bx bx-restaurant fs-5 text-muted mt-1 flex-shrink-0"></i>
                                    <span class="lh-base" style="font-size: 0.95rem;">{{ __('calculator.item_food') }}</span>
                                </div>
                                <strong class="text-dark flex-shrink-0 mt-1 text-nowrap"><span class="calc-item-food">{{ number_format($initialResult->monthlyBreakdown['food']) }}</span> €</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-start py-3 border-bottom border-light gap-2">
                                <div class="d-flex align-items-start gap-2 text-secondary flex-grow-1 pe-2">
                                    <i class="bx bx-bus fs-5 text-muted mt-1 flex-shrink-0"></i>
                                    <span class="lh-base" style="font-size: 0.95rem;">{{ __('calculator.item_transport') }} + {{ __('calculator.item_health_phone') }}</span>
                                </div>
                                <strong class="text-dark flex-shrink-0 mt-1 text-nowrap"><span class="calc-item-misc">{{ number_format($initialResult->monthlyBreakdown['transport'] + $initialResult->monthlyBreakdown['health_phone']) }}</span> €</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-start py-3 text-success fw-semibold gap-2">
                                <div class="d-flex align-items-start gap-2 flex-grow-1 pe-2">
                                    <i class="bx bx-check-circle fs-5 mt-1 flex-shrink-0"></i>
                                    <span class="lh-base" style="font-size: 0.95rem;">{{ __('calculator.item_caf_deduction') }}</span>
                                </div>
                                <span class="flex-shrink-0 mt-1 text-nowrap">-<span class="calc-item-caf">{{ number_format($initialResult->cafDeduction) }}</span> €</span>
                            </div>
                        </div>
                    </div>

                    {{-- Advisory & Legal Guidance Disclaimer Card --}}
                    <div class="p-3 p-md-4 rounded-4 border calc-advisory-card shadow-sm mt-2 position-relative">
                        <div class="d-flex align-items-start gap-3">
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
                    <div class="mt-2">
                        @php
                            $prefillMessage = match($currentLocale) {
                                'fa' => "درخواست بررسی پرونده ویزای تحصیلی برای شهر " . ($cities[$initialCity]['name_fa'] ?? $initialCity) . " با برآورد بودجه ماهانه " . $initialResult->netMonthlyLivingCost . " یورو و تمکن سالانه " . $initialResult->officialAnnualVisaProof . " یورو.",
                                'fr' => "Demande d'évaluation de dossier visa étudiant pour " . ($cities[$initialCity]['name_fr'] ?? $initialCity) . " avec budget mensuel estimé à " . $initialResult->netMonthlyLivingCost . " € et ressources annuelles de " . $initialResult->officialAnnualVisaProof . " €.",
                                default => "Student visa case evaluation inquiry for " . ($cities[$initialCity]['name_en'] ?? $initialCity) . " with estimated monthly budget of " . $initialResult->netMonthlyLivingCost . " EUR and annual proof of " . $initialResult->officialAnnualVisaProof . " EUR.",
                            };
                            $consultTargetUrl = url($currentLocale . '/consult?service=student-visa&details=' . urlencode($prefillMessage));
                        @endphp

                        <a href="{{ $consultTargetUrl }}" 
                           class="btn btn-brand w-100 rounded-pill py-3 fw-bold shadow-lg d-flex align-items-center justify-content-center gap-2 calc-consult-link transition-all fs-6"
                           data-base-url="{{ url($currentLocale . '/consult') }}">
                            <i class="bx bx-paper-plane fs-4"></i>
                            <span>{{ __('calculator.cta_button') }}</span>
                        </a>
                        
                        <div class="text-center mt-3">
                            <span class="text-muted d-inline-flex align-items-center justify-content-center gap-1" style="font-size: 0.75rem;">
                                <i class="bx bx-shield-quarter fs-6"></i>
                                {{ __('calculator.disclaimer') }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom Scoped Calculator Styles --}}
<style>
    .avc-budget-calculator {
        /* Removed custom overflow and container constraints to solve mobile issues */
    }
    .avc-calc-badge {
        background-color: #fff3ed;
        color: #ff5d22;
        border: 1px solid rgba(255, 93, 34, 0.2);
    }
    .text-brand {
        color: #ff5d22 !important;
    }
    .bg-brand-subtle {
        background-color: #fff3ed !important;
    }
    .border-brand-subtle {
        border-color: rgba(255, 93, 34, 0.3) !important;
    }
    .step-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #ff5d22;
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(255, 93, 34, 0.3);
    }
    .calc-icon-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background-color: #fff3ed;
        transition: all 0.3s ease;
    }
    .calc-icon-box i {
        color: #ff5d22;
        transition: color 0.3s ease;
    }
    .calc-card-option {
        cursor: pointer;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .calc-card-option:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
    }
    .calc-card-option.selected {
        border-color: #ff5d22 !important;
        background: #fff9f6 !important;
        box-shadow: 0 8px 24px rgba(255, 93, 34, 0.12) !important;
    }
    .calc-card-option.selected .calc-icon-box {
        background-color: #ff5d22 !important;
    }
    .calc-card-option.selected .calc-icon-box i {
        color: #ffffff !important;
    }
    .calc-segmented-group {
        display: flex;
        background: #f1f5f9;
        padding: 6px;
        border-radius: 9999px;
        border: 1px solid #e2e8f0;
        gap: 6px;
    }
    .calc-segmented-btn {
        flex: 1 1 0;
        text-align: center;
        padding: 10px 12px;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        white-space: normal;
        line-height: 1.2;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
    }
    .calc-segmented-btn.active {
        background: #ffffff;
        color: #ff5d22;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .calc-hero-card {
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
    .hover-shadow:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transform: translateY(-1px);
    }

    /* Native Select & Option Alignment */
    .avc-budget-calculator .calc-city,
    .avc-budget-calculator select.calc-city {
        width: 100% !important;
        height: 56px !important;
        font-size: 1.05rem !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        background-color: #ffffff !important;
        border: 2px solid #e2e8f0 !important;
        border-radius: 12px !important;
        cursor: pointer;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .avc-budget-calculator select.calc-city:focus,
    .avc-budget-calculator .calc-city:focus {
        border-color: #ff5d22 !important;
        outline: none !important;
        box-shadow: 0 0 0 4px rgba(255, 93, 34, 0.15) !important;
    }
    
    @media (min-width: 576px) {
        .border-start-sm {
            border-left: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
        }
        [dir="rtl"] .border-start-sm,
        [data-is-rtl="true"] .border-start-sm {
            border-left: none !important;
            border-right: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
        }
    }

    [dir="rtl"] .avc-budget-calculator select.calc-city,
    [data-is-rtl="true"] .avc-budget-calculator select.calc-city {
        text-align: center !important;
        text-align-last: center !important;
        direction: rtl !important;
    }
    [dir="rtl"] .avc-budget-calculator select.calc-city option,
    [data-is-rtl="true"] .avc-budget-calculator select.calc-city option {
        text-align: center !important;
        direction: rtl !important;
        padding: 10px 14px !important;
        font-weight: 500 !important;
    }
    [dir="ltr"] .avc-budget-calculator select.calc-city,
    [data-is-rtl="false"] .avc-budget-calculator select.calc-city {
        text-align: center !important;
        text-align-last: center !important;
        direction: ltr !important;
    }
    [dir="ltr"] .avc-budget-calculator select.calc-city option,
    [data-is-rtl="false"] .avc-budget-calculator select.calc-city option {
        text-align: center !important;
        direction: ltr !important;
        padding: 10px 14px !important;
    }

    /* jQuery NiceSelect Overrides (If enabled by global theme script) */
    .avc-budget-calculator .nice-select.calc-city {
        float: none !important;
        width: 100% !important;
        height: 56px !important;
        line-height: 52px !important;
        border: 2px solid #e2e8f0 !important;
        border-radius: 12px !important;
        background-color: #ffffff !important;
        background-image: none !important; /* Prevent double arrow if form-select class is copied */
        box-shadow: none !important;
        text-align: center !important;
    }
    .avc-budget-calculator .nice-select.calc-city .current {
        float: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: 100% !important;
        text-align: center !important;
    }
    [dir="rtl"] .avc-budget-calculator .nice-select.calc-city,
    [data-is-rtl="true"] .avc-budget-calculator .nice-select.calc-city {
        direction: rtl !important;
        padding-right: 3rem !important;
        padding-left: 3rem !important;
    }
    [dir="ltr"] .avc-budget-calculator .nice-select.calc-city,
    [data-is-rtl="false"] .avc-budget-calculator .nice-select.calc-city {
        direction: ltr !important;
        padding-right: 3rem !important;
        padding-left: 3rem !important;
    }
    [dir="rtl"] .avc-budget-calculator .nice-select.calc-city:after,
    [data-is-rtl="true"] .avc-budget-calculator .nice-select.calc-city:after {
        left: 1.25rem !important;
        right: auto !important;
    }
    .avc-budget-calculator .nice-select.calc-city .list {
        width: 100% !important;
        border-radius: 12px !important;
        border: 2px solid #e2e8f0 !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
        max-height: 280px !important;
        overflow-y: auto !important;
        z-index: 1050 !important;
        text-align: start !important;
    }
    [dir="rtl"] .avc-budget-calculator .nice-select.calc-city .list .option,
    [data-is-rtl="true"] .avc-budget-calculator .nice-select.calc-city .list .option {
        text-align: right !important;
        padding: 0.75rem 1.5rem !important;
        font-size: 1rem !important;
    }
    .avc-budget-calculator .nice-select.calc-city .list .option.selected {
        font-weight: 700 !important;
        color: #ff5d22 !important;
        background-color: #fffaf7 !important;
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

            // Highlight lifestyle cards
            container.querySelectorAll('input.calc-lifestyle-radio').forEach(radio => {
                const card = radio.closest('.calc-card-option');
                if (card) {
                    if (radio.checked) {
                        card.classList.add('selected');
                        const icon = card.querySelector('.calc-mini-icon');
                        if (icon) { icon.classList.remove('text-muted'); icon.classList.add('text-brand'); }
                        const text = card.querySelector('.text-secondary');
                        if (text) { text.classList.remove('text-secondary'); text.classList.add('text-brand'); }
                    } else {
                        card.classList.remove('selected');
                        const icon = card.querySelector('.calc-mini-icon');
                        if (icon) { icon.classList.remove('text-brand'); icon.classList.add('text-muted'); }
                        const text = card.querySelector('.text-brand');
                        if (text && !text.classList.contains('text-secondary')) { text.classList.remove('text-brand'); text.classList.add('text-secondary'); }
                    }
                }
            });

            // Highlight duration cards
            container.querySelectorAll('input.calc-months-radio').forEach(radio => {
                const card = radio.closest('.calc-card-option');
                if (card) {
                    if (radio.checked) {
                        card.classList.add('selected');
                        const icon = card.querySelector('.calc-mini-icon');
                        if (icon) { icon.classList.remove('text-muted'); icon.classList.add('text-brand'); }
                        const text = card.querySelector('.text-secondary');
                        if (text) { text.classList.remove('text-secondary'); text.classList.add('text-brand'); }
                    } else {
                        card.classList.remove('selected');
                        const icon = card.querySelector('.calc-mini-icon');
                        if (icon) { icon.classList.remove('text-brand'); icon.classList.add('text-muted'); }
                        const text = card.querySelector('.text-brand');
                        if (text && !text.classList.contains('text-secondary')) { text.classList.remove('text-brand'); text.classList.add('text-secondary'); }
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

            riskBadge.className = 'badge rounded-pill px-3 py-2 small calc-risk-badge d-inline-flex align-items-center gap-1 w-100 justify-content-center ' + riskClass;
            riskIcon.className = iconClass + ' fs-6';
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
        if (typeof window.jQuery !== 'undefined') {
            window.jQuery(citySelect).on('change', recalculate);
        }

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
