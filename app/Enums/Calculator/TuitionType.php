<?php

declare(strict_types=1);

namespace App\Enums\Calculator;

enum TuitionType: string
{
    case Exonerated = 'exonerated';
    case BienvenueEnFrance = 'bienvenue_en_france';
    case PrivateSchool = 'private_school';

    public function annualEstimate(): int
    {
        return match ($this) {
            self::Exonerated => 243, // Standard French public university baseline fee
            self::BienvenueEnFrance => 3879, // Standard Non-EU Master's degree rate
            self::PrivateSchool => 9500, // Average private business / engineering school
        };
    }

    public function monthlyEstimate(): int
    {
        return (int) round($this->annualEstimate() / 12);
    }

    public function label(string $locale = 'fa'): string
    {
        return match ($locale) {
            'fa' => match ($this) {
                self::Exonerated => 'دانشگاه دولتی با معافیت / نرخ دولتی (~۲۵۰€ در سال)',
                self::BienvenueEnFrance => 'دانشگاه دولتی نرخ مصوب Bienvenue en France (~۳,۸۷۹€ در سال)',
                self::PrivateSchool => 'مدارس عالی خصوصی / بیزینس اسکول (~۹,۵۰۰€ در سال)',
            },
            'fr' => match ($this) {
                self::Exonerated => 'Université publique avec exonération (~250€ / an)',
                self::BienvenueEnFrance => 'Université publique tarif plein Bienvenue en France (~3 879€ / an)',
                self::PrivateSchool => 'Grande École privée / Business School (~9 500€ / an)',
            },
            default => match ($this) {
                self::Exonerated => 'Public University with Exoneration (~€250 / year)',
                self::BienvenueEnFrance => 'Public University Bienvenue en France (~€3,879 / year)',
                self::PrivateSchool => 'Private Grande École / Business School (~€9,500 / year)',
            },
        };
    }
}
