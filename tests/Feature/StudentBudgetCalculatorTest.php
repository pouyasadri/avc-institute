<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Contracts\Calculator\StudentBudgetBenchmarkProviderInterface;
use App\DTOs\Calculator\StudentBudgetCriteria;
use App\Enums\Calculator\AccommodationType;
use App\Enums\Calculator\TuitionType;
use App\Enums\Calculator\VisaRiskLevel;
use App\Queries\Calculator\CalculateStudentBudgetHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class StudentBudgetCalculatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_student_budget_handler_computes_accurate_paris_colocation_budget(): void
    {
        $handler = app(CalculateStudentBudgetHandler::class);

        $criteria = new StudentBudgetCriteria(
            cityKey: 'paris',
            accommodation: AccommodationType::Colocation,
            tuition: TuitionType::BienvenueEnFrance,
            months: 12,
            lifestyleBuffer: 80,
        );

        $result = $handler->handle($criteria);

        // Paris benchmarks: rent = 650, food = 280, transport = 42, health_phone = 40, lifestyle = 80
        // Gross monthly = 650 + 280 + 42 + 40 + 80 = 1092
        $this->assertSame(1092, $result->grossMonthlyCost);

        // CAF for colocation in config is 180
        $this->assertSame(180, $result->cafDeduction);

        // Net monthly living cost = 1092 - 180 = 912
        $this->assertSame(912, $result->netMonthlyLivingCost);

        // Official consular minimum is 877.50 / month -> 10,530 for 12 months
        $this->assertSame(877.5, $result->officialMonthlyVisaProof);
        $this->assertSame(10530.0, $result->officialAnnualVisaProof);

        // Recommended monthly safety proof for Paris is 1000 -> 12,000 for 12 months
        $this->assertSame(1000, $result->recommendedMonthlySafetyProof);
        $this->assertSame(12000, $result->recommendedAnnualSafetyProof);

        // Tuition for Bienvenue en France Master's rate is 3,879
        $this->assertSame(3879, $result->annualTuitionFee);

        // First year total net budget = (912 * 12) + 3879 = 10944 + 3879 = 14823
        $this->assertSame(14823, $result->firstYearTotalNetBudget);

        // With gross monthly 1092 >= 1000 and < (1000 + 100), risk level should be Sufficient
        $this->assertSame(VisaRiskLevel::Sufficient, $result->visaRiskLevel);
    }

    public function test_visa_risk_level_evaluates_borderline_and_high_risk(): void
    {
        $handler = app(CalculateStudentBudgetHandler::class);

        // Custom mock provider to test boundary thresholds
        $mockProvider = new class implements StudentBudgetBenchmarkProviderInterface
        {
            public function getOfficialMonthlyMinimum(): float
            {
                return 877.5;
            }

            public function getDefaultDurationMonths(): int
            {
                return 12;
            }

            public function getCityBenchmarks(string $cityKey): array
            {
                return ['food' => 100, 'transport' => 20, 'health_phone' => 20, 'recommended_monthly_min' => 950];
            }

            public function getCities(): array
            {
                return [];
            }

            public function getAccommodationRent(string $cityKey, AccommodationType $accommodation): int
            {
                return 740; // Gross = 740 + 100 + 20 + 20 = 880 (between 877.5 and 950 -> Borderline)
            }

            public function getCafAllowance(AccommodationType $accommodation): int
            {
                return 150;
            }

            public function getRecommendedMonthlyMinimum(string $cityKey): int
            {
                return 950;
            }
        };

        $borderlineHandler = new CalculateStudentBudgetHandler($mockProvider);
        $borderlineResult = $borderlineHandler->handle(new StudentBudgetCriteria('test', AccommodationType::Crous, TuitionType::Exonerated, 12, 0));
        $this->assertSame(VisaRiskLevel::Borderline, $borderlineResult->visaRiskLevel);

        // High risk test (< 877.5)
        $highRiskProvider = new class implements StudentBudgetBenchmarkProviderInterface
        {
            public function getOfficialMonthlyMinimum(): float
            {
                return 877.5;
            }

            public function getDefaultDurationMonths(): int
            {
                return 12;
            }

            public function getCityBenchmarks(string $cityKey): array
            {
                return ['food' => 50, 'transport' => 10, 'health_phone' => 10, 'recommended_monthly_min' => 950];
            }

            public function getCities(): array
            {
                return [];
            }

            public function getAccommodationRent(string $cityKey, AccommodationType $accommodation): int
            {
                return 200; // Gross = 200 + 50 + 10 + 10 = 270 (< 877.5 -> HighRisk)
            }

            public function getCafAllowance(AccommodationType $accommodation): int
            {
                return 100;
            }

            public function getRecommendedMonthlyMinimum(string $cityKey): int
            {
                return 950;
            }
        };

        $highRiskHandler = new CalculateStudentBudgetHandler($highRiskProvider);
        $highRiskResult = $highRiskHandler->handle(new StudentBudgetCriteria('test', AccommodationType::Crous, TuitionType::Exonerated, 12, 0));
        $this->assertSame(VisaRiskLevel::HighRisk, $highRiskResult->visaRiskLevel);
    }

    public function test_student_budget_criteria_from_array_handles_bounds_and_fallbacks(): void
    {
        $criteria = StudentBudgetCriteria::fromArray([
            'city' => 'LYON',
            'accommodation' => 'private_studio',
            'tuition' => 'private_school',
            'months' => 999, // Should be clamped to 24
            'lifestyle_buffer' => -50, // Should be clamped to 0
        ]);

        $this->assertSame('lyon', $criteria->cityKey);
        $this->assertSame(AccommodationType::PrivateStudio, $criteria->accommodation);
        $this->assertSame(TuitionType::PrivateSchool, $criteria->tuition);
        $this->assertSame(24, $criteria->months);
        $this->assertSame(0, $criteria->lifestyleBuffer);
    }

    public function test_student_budget_calculator_blade_component_renders_cleanly_in_persian(): void
    {
        app()->setLocale('fa');

        $rendered = Blade::render('<x-calculator.student-budget initialCity="montpellier" />');

        $this->assertStringContainsString('محاسبه‌گر تمکن مالی ویزا', $rendered);
        $this->assertStringContainsString('مونپلیه', $rendered);
        $this->assertStringContainsString('خوابگاه دولتی کروس', $rendered);
        $this->assertStringContainsString('درخواست بررسی پرونده و رزرو مشاوره تخصصی', $rendered);
        $this->assertStringContainsString('توجه مهم: این ابزار صرفاً جهت برآورد اولیه است', $rendered);
        $this->assertStringContainsString('data-is-rtl="true"', $rendered);
    }

    public function test_student_budget_calculator_blade_component_renders_cleanly_in_english_and_french(): void
    {
        app()->setLocale('en');
        $renderedEn = Blade::render('<x-calculator.student-budget initialCity="paris" />');
        $this->assertStringContainsString('French Student Visa Financial Proof', $renderedEn);
        $this->assertStringContainsString('Paris', $renderedEn);
        $this->assertStringContainsString('Important Notice: This tool provides initial guidance only', $renderedEn);
        $this->assertStringContainsString('data-is-rtl="false"', $renderedEn);

        app()->setLocale('fr');
        $renderedFr = Blade::render('<x-calculator.student-budget initialCity="paris" />');
        $this->assertStringContainsString('Simulateur Justificatif Financier Visa', $renderedFr);
        $this->assertStringContainsString('Loyer (brut avant APL)', $renderedFr);
    }

    public function test_city_page_renders_calculator_teaser(): void
    {
        $response = $this->get('/fa/cities/paris');
        $response->assertStatus(200);
        $response->assertSee('avc-calculator-teaser', false);
        $response->assertSee('محاسبه‌گر هوشمند تمکن ۲۰۲۶', false);
        $response->assertSee('/fa/calculator?city=paris', false);
    }

    public function test_student_visa_service_page_renders_calculator_teaser(): void
    {
        $response = $this->get('/fa/services/student-visa');
        $response->assertStatus(200);
        $response->assertSee('avc-calculator-teaser', false);
        $response->assertSee('/fa/calculator', false);
    }

    public function test_standalone_calculator_page_renders_successfully(): void
    {
        $response = $this->get('/fa/calculator');
        $response->assertStatus(200);
        $response->assertSee('student-budget-calculator', false);
        $response->assertSee('calculator-page-area', false);
        $response->assertSee('calculator-guide-area', false);
        $response->assertSee('calculator-faqs-accordion', false);
    }
}
