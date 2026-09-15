<?php

declare(strict_types=1);

namespace App\Contracts\Calculator;

use App\Enums\Calculator\AccommodationType;

interface StudentBudgetBenchmarkProviderInterface
{
    public function getOfficialMonthlyMinimum(): int;

    public function getDefaultDurationMonths(): int;

    public function getCityBenchmarks(string $cityKey): array;

    public function getCities(): array;

    public function getAccommodationRent(string $cityKey, AccommodationType $accommodation): int;

    public function getCafAllowance(AccommodationType $accommodation): int;

    public function getRecommendedMonthlyMinimum(string $cityKey): int;
}
