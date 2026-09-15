<?php

declare(strict_types=1);

namespace App\Enums\Calculator;

enum AccommodationType: string
{
    case Crous = 'crous';
    case Colocation = 'colocation';
    case PrivateStudio = 'private_studio';

    public function label(string $locale = 'fa'): string
    {
        return match ($locale) {
            'fa' => match ($this) {
                self::Crous => 'خوابگاه دولتی کروس (CROUS)',
                self::Colocation => 'آپارتمان اشتراکی (Colocation)',
                self::PrivateStudio => 'استودیو مستقل خصوصی (Studio)',
            },
            'fr' => match ($this) {
                self::Crous => 'Résidence universitaire CROUS',
                self::Colocation => 'Colocation',
                self::PrivateStudio => 'Studio privé indépendant',
            },
            default => match ($this) {
                self::Crous => 'CROUS University Residence',
                self::Colocation => 'Shared Flat (Colocation)',
                self::PrivateStudio => 'Private Independent Studio',
            },
        };
    }
}
