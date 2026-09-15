<?php

declare(strict_types=1);

namespace App\Services\Calculator;

use App\Contracts\Calculator\StudentBudgetBenchmarkProviderInterface;
use App\Enums\Calculator\AccommodationType;

final readonly class ConfigStudentBudgetBenchmarkProvider implements StudentBudgetBenchmarkProviderInterface
{
    public function getOfficialMonthlyMinimum(): int
    {
        return (int) config('calculator.official_monthly_minimum', 615);
    }

    public function getDefaultDurationMonths(): int
    {
        return (int) config('calculator.default_duration_months', 12);
    }

    public function getCityBenchmarks(string $cityKey): array
    {
        $cities = config('calculator.cities', []);
        $normalized = strtolower(trim($cityKey));

        return $cities[$normalized] ?? $cities['other'] ?? [
            'name_fa' => 'سایر شهرها',
            'name_en' => 'Other Cities',
            'name_fr' => 'Autres villes',
            'rents' => ['crous' => 250, 'colocation' => 390, 'private_studio' => 500],
            'food' => 210,
            'transport' => 30,
            'health_phone' => 40,
            'recommended_monthly_min' => 650,
        ];
    }

    public function getCities(): array
    {
        return config('calculator.cities', []);
    }

    public function getAccommodationRent(string $cityKey, AccommodationType $accommodation): int
    {
        $benchmarks = $this->getCityBenchmarks($cityKey);

        return (int) ($benchmarks['rents'][$accommodation->value] ?? 450);
    }

    public function getCafAllowance(AccommodationType $accommodation): int
    {
        $allowances = config('calculator.caf_allowances', []);

        return (int) ($allowances[$accommodation->value] ?? 180);
    }

    public function getRecommendedMonthlyMinimum(string $cityKey): int
    {
        $benchmarks = $this->getCityBenchmarks($cityKey);

        return (int) ($benchmarks['recommended_monthly_min'] ?? 650);
    }
}
