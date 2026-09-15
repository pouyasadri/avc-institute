<?php

declare(strict_types=1);

namespace App\Queries\Calculator;

use App\Contracts\Calculator\StudentBudgetBenchmarkProviderInterface;
use App\DTOs\Calculator\StudentBudgetCriteria;
use App\DTOs\Calculator\StudentBudgetResult;
use App\Enums\Calculator\VisaRiskLevel;

final readonly class CalculateStudentBudgetHandler
{
    public function __construct(
        private StudentBudgetBenchmarkProviderInterface $benchmarkProvider,
    ) {}

    public function handle(StudentBudgetCriteria $criteria): StudentBudgetResult
    {
        $benchmarks = $this->benchmarkProvider->getCityBenchmarks($criteria->cityKey);

        $rent = $this->benchmarkProvider->getAccommodationRent($criteria->cityKey, $criteria->accommodation);
        $food = (int) ($benchmarks['food'] ?? 230);
        $transport = (int) ($benchmarks['transport'] ?? 35);
        $healthPhone = (int) ($benchmarks['health_phone'] ?? 40);
        $lifestyleBuffer = $criteria->lifestyleBuffer;

        $grossMonthlyCost = $rent + $food + $transport + $healthPhone + $lifestyleBuffer;

        $cafDeduction = $this->benchmarkProvider->getCafAllowance($criteria->accommodation);
        $netMonthlyLivingCost = max(0, $grossMonthlyCost - $cafDeduction);

        $officialMonthlyVisaProof = $this->benchmarkProvider->getOfficialMonthlyMinimum();
        $officialAnnualVisaProof = $officialMonthlyVisaProof * $criteria->months;

        $recommendedMonthlySafetyProof = $this->benchmarkProvider->getRecommendedMonthlyMinimum($criteria->cityKey);
        $recommendedAnnualSafetyProof = $recommendedMonthlySafetyProof * $criteria->months;

        $annualTuitionFee = $criteria->tuition->annualEstimate();

        $firstYearTotalGrossBudget = ($grossMonthlyCost * $criteria->months) + $annualTuitionFee;
        $firstYearTotalNetBudget = ($netMonthlyLivingCost * $criteria->months) + $annualTuitionFee;

        // Determine Visa Approval Risk Level
        $plannedMonthly = $grossMonthlyCost;
        $riskLevel = match (true) {
            $plannedMonthly >= ($recommendedMonthlySafetyProof + 100) => VisaRiskLevel::Optimal,
            $plannedMonthly >= $recommendedMonthlySafetyProof => VisaRiskLevel::Sufficient,
            $plannedMonthly >= $officialMonthlyVisaProof => VisaRiskLevel::Borderline,
            default => VisaRiskLevel::HighRisk,
        };

        return new StudentBudgetResult(
            grossMonthlyCost: $grossMonthlyCost,
            cafDeduction: $cafDeduction,
            netMonthlyLivingCost: $netMonthlyLivingCost,
            officialMonthlyVisaProof: $officialMonthlyVisaProof,
            officialAnnualVisaProof: $officialAnnualVisaProof,
            recommendedMonthlySafetyProof: $recommendedMonthlySafetyProof,
            recommendedAnnualSafetyProof: $recommendedAnnualSafetyProof,
            annualTuitionFee: $annualTuitionFee,
            firstYearTotalGrossBudget: $firstYearTotalGrossBudget,
            firstYearTotalNetBudget: $firstYearTotalNetBudget,
            visaRiskLevel: $riskLevel,
            monthlyBreakdown: [
                'rent' => $rent,
                'food' => $food,
                'transport' => $transport,
                'health_phone' => $healthPhone,
                'lifestyle_buffer' => $lifestyleBuffer,
                'caf_deduction' => $cafDeduction,
            ],
            cityKey: $criteria->cityKey,
        );
    }
}
