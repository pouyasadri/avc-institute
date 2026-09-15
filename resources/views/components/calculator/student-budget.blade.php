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

<div class="student-budget-calculator rounded-5 p-4 p-md-5 my-5 border border-primary-subtle shadow-sm bg-white position-relative overflow-hidden" 
     id="{{ $calculatorId }}"
     data-locale="{{ $currentLocale }}"
     data-is-rtl="{{ $isRtl ? 'true' : 'false' }}">

    {{-- Decorative subtle background glow --}}
    <div class="position-absolute top-0 end-0 p-5 rounded-circle bg-primary-subtle opacity-25 filter-blur" 
         style="width: 250px; height: 250px; margin-top: -100px; margin-inline-end: -100px; pointer-events: none;"></div>

    <header class="text-center mb-4 position-relative">
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-2 rounded-pill bg-primary-subtle text-primary fw-semibold small">
            <i class="bx bxs-calculator fs-5"></i>
            <span>{{ __('calculator.badge') }}</span>
        </div>
        <h3 class="h4 fw-bold text-dark mb-2">
            {{ __('calculator.title') }}
        </h3>
        <p class="text-secondary small mx-auto mb-0" style="max-width: 720px;">
            {{ __('calculator.subtitle') }}
        </p>
    </header>

    <div class="row g-4 mt-2">
        {{-- Left / Form Controls Column --}}
        <div class="col-lg-6">
            <div class="p-3 p-md-4 rounded-4 bg-light border h-100">
                
                {{-- 1. City Selection --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bx bxs-map-pin text-primary fs-5"></i>
                        {{ __('calculator.city_label') }}
                    </label>
                    <select class="form-select rounded-pill px-4 py-2 border-primary-subtle fw-semibold calc-city" aria-label="{{ __('calculator.city_label') }}">
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

                {{-- 2. Accommodation Type --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bx bxs-home-circle text-primary fs-5"></i>
                        {{ __('calculator.accommodation_label') }}
                    </label>
                    <div class="d-grid gap-2">
                        @foreach(\App\Enums\Calculator\AccommodationType::cases() as $acc)
                            <label class="btn btn-outline-secondary text-start d-flex align-items-center justify-content-between p-2 px-3 rounded-4 transition-all calc-acc-label {{ $acc->value === $initialAccommodation ? 'active border-primary bg-primary-subtle text-primary fw-bold' : '' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="radio" name="acc_{{ $calculatorId }}" value="{{ $acc->value }}" class="form-check-input mt-0 calc-acc" {{ $acc->value === $initialAccommodation ? 'checked' : '' }}>
                                    <span class="small">{{ $acc->label($currentLocale) }}</span>
                                </div>
                                <span class="badge bg-secondary-subtle text-dark small py-1 px-2 rounded-pill">
                                    +{{ $cafAllowances[$acc->value] ?? 180 }}€ CAF
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 3. University Tuition --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bx bxs-graduation text-primary fs-5"></i>
                        {{ __('calculator.tuition_label') }}
                    </label>
                    <div class="d-grid gap-2">
                        @foreach(\App\Enums\Calculator\TuitionType::cases() as $tui)
                            <label class="btn btn-outline-secondary text-start d-flex align-items-center justify-content-between p-2 px-3 rounded-4 transition-all calc-tui-label {{ $tui->value === $initialTuition ? 'active border-primary bg-primary-subtle text-primary fw-bold' : '' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="radio" name="tui_{{ $calculatorId }}" value="{{ $tui->value }}" class="form-check-input mt-0 calc-tui" {{ $tui->value === $initialTuition ? 'checked' : '' }}>
                                    <span class="small">{{ $tui->label($currentLocale) }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 4. Lifestyle / Spending --}}
                <div class="row g-2 mb-3">
                    <div class="col-sm-7">
                        <label class="form-label small fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bx bx-coffee text-primary fs-5"></i>
                            {{ __('calculator.lifestyle_label') }}
                        </label>
                        <select class="form-select rounded-pill px-3 py-2 small calc-lifestyle">
                            <option value="0">{{ __('calculator.lifestyle_economic') }}</option>
                            <option value="80" selected>{{ __('calculator.lifestyle_moderate') }}</option>
                            <option value="160">{{ __('calculator.lifestyle_comfortable') }}</option>
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <label class="form-label small fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bx bx-calendar text-primary fs-5"></i>
                            {{ __('calculator.duration_label') }}
                        </label>
                        <select class="form-select rounded-pill px-3 py-2 small calc-months">
                            <option value="12" selected>{{ __('calculator.duration_12_months') }}</option>
                            <option value="10">{{ __('calculator.duration_10_months') }}</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>

        {{-- Right / Results Display Column --}}
        <div class="col-lg-6 d-flex flex-column justify-content-between">
            <div class="p-4 rounded-4 border bg-light h-100 shadow-xs d-flex flex-column justify-content-between">
                
                <div>
                    {{-- Header with Risk Badge --}}
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-3 border-bottom">
                        <span class="fw-bold text-dark small d-flex align-items-center gap-2">
                            <i class="bx bx-bar-chart-alt-2 text-primary fs-5"></i>
                            {{ __('calculator.results_heading') }}
                        </span>
                        <span class="badge rounded-pill px-3 py-2 small calc-risk-badge {{ $initialResult->visaRiskLevel->badgeClass() }}">
                            <i class="{{ $initialResult->visaRiskLevel->icon() }} me-1 align-middle"></i>
                            <span class="calc-risk-text">{{ $initialResult->visaRiskLevel->label($currentLocale) }}</span>
                        </span>
                    </div>

                    {{-- Primary Metric: Net Monthly Living Cost --}}
                    <div class="p-3 p-md-4 rounded-4 bg-white border text-center mb-3 shadow-xs">
                        <span class="small text-muted d-block mb-1">
                            {{ __('calculator.net_monthly_title') }}
                        </span>
                        <div class="display-6 fw-bold text-primary mb-1">
                            <span class="calc-val-net-monthly">{{ number_format($initialResult->netMonthlyLivingCost) }}</span> €
                            <span class="fs-6 text-muted fw-normal">/ {{ $currentLocale === 'fa' ? 'ماه' : ($currentLocale === 'fr' ? 'mois' : 'month') }}</span>
                        </div>
                        <div class="small text-success fw-semibold">
                            <i class="bx bx-check-circle align-middle"></i>
                            {{ __('calculator.caf_subsidy_title') }}: 
                            <span class="calc-val-caf">-{{ number_format($initialResult->cafDeduction) }}</span> € / {{ $currentLocale === 'fa' ? 'ماه' : 'mo' }}
                        </div>
                    </div>

                    {{-- 2 Financial Proof Benchmarks (Official vs Recommended) --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-white border h-100">
                                <span class="small text-muted d-block mb-1 lh-sm">
                                    {{ __('calculator.official_proof_title') }}
                                </span>
                                <div class="h5 fw-bold text-dark mb-0">
                                    <span class="calc-val-official-proof">{{ number_format($initialResult->officialAnnualVisaProof) }}</span> €
                                </div>
                                <span class="text-muted" style="font-size: 0.75rem;">(615€ × <span class="calc-val-months">12</span>)</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-white border border-primary-subtle h-100">
                                <span class="small text-primary fw-semibold d-block mb-1 lh-sm">
                                    {{ __('calculator.recommended_proof_title') }}
                                </span>
                                <div class="h5 fw-bold text-primary mb-0">
                                    <span class="calc-val-recommended-proof">{{ number_format($initialResult->recommendedAnnualSafetyProof) }}</span> €
                                </div>
                                <span class="text-muted" style="font-size: 0.75rem;">(<span class="calc-val-recommended-monthly">{{ number_format($initialResult->recommendedMonthlySafetyProof) }}</span>€ / {{ $currentLocale === 'fa' ? 'ماه' : 'mo' }})</span>
                            </div>
                        </div>
                    </div>

                    {{-- Collapsible Breakdown Items --}}
                    <div class="p-3 rounded-4 bg-white border small text-secondary mb-3">
                        <div class="fw-bold text-dark mb-2 d-flex justify-content-between">
                            <span>{{ __('calculator.breakdown_title') }}</span>
                            <span class="text-primary fw-normal">
                                {{ __('calculator.first_year_total_title') }}: <strong class="calc-val-first-year">{{ number_format($initialResult->firstYearTotalNetBudget) }}</strong> €
                            </span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <span>{{ __('calculator.item_rent') }}</span>
                            <strong class="text-dark"><span class="calc-item-rent">{{ number_format($initialResult->monthlyBreakdown['rent']) }}</span> €</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <span>{{ __('calculator.item_food') }}</span>
                            <strong class="text-dark"><span class="calc-item-food">{{ number_format($initialResult->monthlyBreakdown['food']) }}</span> €</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <span>{{ __('calculator.item_transport') }} + {{ __('calculator.item_health_phone') }}</span>
                            <strong class="text-dark"><span class="calc-item-misc">{{ number_format($initialResult->monthlyBreakdown['transport'] + $initialResult->monthlyBreakdown['health_phone']) }}</span> €</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 text-success">
                            <span>{{ __('calculator.item_caf_deduction') }}</span>
                            <strong>-<span class="calc-item-caf">{{ number_format($initialResult->cafDeduction) }}</span> €</strong>
                        </div>
                    </div>
                </div>

                {{-- Lead Generation Conversion Area (Direct link to /consult prefilled) --}}
                <div class="pt-2">
                    @php
                        $prefillMessage = match($currentLocale) {
                            'fa' => "درخواست بررسی پرونده ویزای تحصیلی برای شهر " . ($cities[$initialCity]['name_fa'] ?? $initialCity) . " با برآورد بودجه ماهانه " . $initialResult->netMonthlyLivingCost . " یورو و تمکن سالانه " . $initialResult->officialAnnualVisaProof . " یورو.",
                            'fr' => "Demande d'évaluation de dossier visa étudiant pour " . ($cities[$initialCity]['name_fr'] ?? $initialCity) . " avec budget mensuel estimé à " . $initialResult->netMonthlyLivingCost . " € et ressources annuelles de " . $initialResult->officialAnnualVisaProof . " €.",
                            default => "Student visa case evaluation inquiry for " . ($cities[$initialCity]['name_en'] ?? $initialCity) . " with estimated monthly budget of " . $initialResult->netMonthlyLivingCost . " EUR and annual proof of " . $initialResult->officialAnnualVisaProof . " EUR.",
                        };
                        $consultTargetUrl = url($currentLocale . '/consult?service=student-visa&details=' . urlencode($prefillMessage));
                    @endphp

                    <a href="{{ $consultTargetUrl }}" 
                       class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 calc-consult-link"
                       data-base-url="{{ url($currentLocale . '/consult') }}">
                        <i class="bx bx-send fs-5"></i>
                        <span>{{ __('calculator.cta_button') }}</span>
                    </a>
                    <div class="text-center mt-2">
                        <span class="text-muted" style="font-size: 0.72rem;">
                            <i class="bx bx-shield-quarter align-middle"></i>
                            {{ __('calculator.disclaimer') }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

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
        const lifestyleSelect = container.querySelector('.calc-lifestyle');
        const monthsSelect = container.querySelector('.calc-months');
        const consultLink = container.querySelector('.calc-consult-link');
        const baseUrl = consultLink.getAttribute('data-base-url');

        function recalculate() {
            const cityKey = citySelect.value.toLowerCase();
            const cityData = (calcConfig.cities && calcConfig.cities[cityKey]) || calcConfig.cities['other'] || {};
            
            const accChecked = container.querySelector('input.calc-acc:checked');
            const accType = accChecked ? accChecked.value : 'colocation';

            const tuiChecked = container.querySelector('input.calc-tui:checked');
            const tuiType = tuiChecked ? tuiChecked.value : 'bienvenue_en_france';

            const lifestyleBuffer = parseInt(lifestyleSelect.value, 10) || 0;
            const months = parseInt(monthsSelect.value, 10) || 12;

            // Highlight active labels
            container.querySelectorAll('.calc-acc-label').forEach(lbl => {
                const radio = lbl.querySelector('input[type="radio"]');
                if (radio && radio.checked) {
                    lbl.classList.add('active', 'border-primary', 'bg-primary-subtle', 'text-primary', 'fw-bold');
                } else {
                    lbl.classList.remove('active', 'border-primary', 'bg-primary-subtle', 'text-primary', 'fw-bold');
                }
            });

            container.querySelectorAll('.calc-tui-label').forEach(lbl => {
                const radio = lbl.querySelector('input[type="radio"]');
                if (radio && radio.checked) {
                    lbl.classList.add('active', 'border-primary', 'bg-primary-subtle', 'text-primary', 'fw-bold');
                } else {
                    lbl.classList.remove('active', 'border-primary', 'bg-primary-subtle', 'text-primary', 'fw-bold');
                }
            });

            // Benchmark data
            const rents = cityData.rents || { crous: 250, colocation: 390, private_studio: 500 };
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

            // Update DOM Numbers
            const fmt = (num) => new Intl.NumberFormat(locale === 'fa' ? 'fa-IR' : 'fr-FR').format(num);

            const netMonthlyEl = container.querySelector('.calc-val-net-monthly');
            if (netMonthlyEl) netMonthlyEl.textContent = fmt(netMonthly);

            const cafEl = container.querySelector('.calc-val-caf');
            if (cafEl) cafEl.textContent = '-' + fmt(caf);

            const officialProofEl = container.querySelector('.calc-val-official-proof');
            if (officialProofEl) officialProofEl.textContent = fmt(officialAnnual);

            const recommendedProofEl = container.querySelector('.calc-val-recommended-proof');
            if (recommendedProofEl) recommendedProofEl.textContent = fmt(recommendedAnnual);

            const recommendedMonthlyEl = container.querySelector('.calc-val-recommended-monthly');
            if (recommendedMonthlyEl) recommendedMonthlyEl.textContent = fmt(recommendedMonthly);

            const monthsEl = container.querySelector('.calc-val-months');
            if (monthsEl) monthsEl.textContent = months;

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

            riskBadge.className = 'badge rounded-pill px-3 py-2 small calc-risk-badge ' + riskClass;
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

        // Attach event listeners
        citySelect.addEventListener('change', recalculate);
        lifestyleSelect.addEventListener('change', recalculate);
        monthsSelect.addEventListener('change', recalculate);

        container.querySelectorAll('input.calc-acc').forEach(radio => radio.addEventListener('change', recalculate));
        container.querySelectorAll('input.calc-tui').forEach(radio => radio.addEventListener('change', recalculate));

        recalculate();
    }

    document.querySelectorAll('.student-budget-calculator').forEach(initCalculator);
});
</script>
@endonce
