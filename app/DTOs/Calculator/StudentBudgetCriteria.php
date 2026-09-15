<?php

declare(strict_types=1);

namespace App\DTOs\Calculator;

use App\Enums\Calculator\AccommodationType;
use App\Enums\Calculator\TuitionType;

final readonly class StudentBudgetCriteria
{
    public function __construct(
        public string $cityKey = 'paris',
        public AccommodationType $accommodation = AccommodationType::Colocation,
        public TuitionType $tuition = TuitionType::BienvenueEnFrance,
        public int $months = 12,
        public int $lifestyleBuffer = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        $cityKey = isset($data['city']) && is_string($data['city']) ? strtolower(trim($data['city'])) : 'paris';

        $accommodation = isset($data['accommodation']) && is_string($data['accommodation'])
            ? AccommodationType::tryFrom($data['accommodation']) ?? AccommodationType::Colocation
            : AccommodationType::Colocation;

        $tuition = isset($data['tuition']) && is_string($data['tuition'])
            ? TuitionType::tryFrom($data['tuition']) ?? TuitionType::BienvenueEnFrance
            : TuitionType::BienvenueEnFrance;

        $months = isset($data['months']) ? max(6, min(24, (int) $data['months'])) : 12;

        $lifestyleBuffer = isset($data['lifestyle_buffer']) ? max(0, min(500, (int) $data['lifestyle_buffer'])) : 0;

        return new self(
            cityKey: $cityKey,
            accommodation: $accommodation,
            tuition: $tuition,
            months: $months,
            lifestyleBuffer: $lifestyleBuffer,
        );
    }
}
