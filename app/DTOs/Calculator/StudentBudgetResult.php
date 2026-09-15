<?php

declare(strict_types=1);

namespace App\DTOs\Calculator;

use App\Enums\Calculator\VisaRiskLevel;

final readonly class StudentBudgetResult
{
    /**
     * @param  array<string, int>  $monthlyBreakdown
     */
    public function __construct(
        public int $grossMonthlyCost,
        public int $cafDeduction,
        public int $netMonthlyLivingCost,
        public int $officialMonthlyVisaProof,
        public int $officialAnnualVisaProof,
        public int $recommendedMonthlySafetyProof,
        public int $recommendedAnnualSafetyProof,
        public int $annualTuitionFee,
        public int $firstYearTotalGrossBudget,
        public int $firstYearTotalNetBudget,
        public VisaRiskLevel $visaRiskLevel,
        public array $monthlyBreakdown,
        public string $cityKey,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'gross_monthly_cost' => $this->grossMonthlyCost,
            'caf_deduction' => $this->cafDeduction,
            'net_monthly_living_cost' => $this->netMonthlyLivingCost,
            'official_monthly_visa_proof' => $this->officialMonthlyVisaProof,
            'official_annual_visa_proof' => $this->officialAnnualVisaProof,
            'recommended_monthly_safety_proof' => $this->recommendedMonthlySafetyProof,
            'recommended_annual_safety_proof' => $this->recommendedAnnualSafetyProof,
            'annual_tuition_fee' => $this->annualTuitionFee,
            'first_year_total_gross_budget' => $this->firstYearTotalGrossBudget,
            'first_year_total_net_budget' => $this->firstYearTotalNetBudget,
            'visa_risk_level' => $this->visaRiskLevel->value,
            'monthly_breakdown' => $this->monthlyBreakdown,
            'city' => $this->cityKey,
        ];
    }
}
