<?php

namespace App\Enums;

enum PropertyType: string
{
    case APARTMENT = 'apartment';
    case HOUSE = 'house';
    case VILLA = 'villa';
    case COMMERCIAL = 'commercial';

    /**
     * Get all string values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::APARTMENT => 'Apartment',
            self::HOUSE => 'House',
            self::VILLA => 'Villa',
            self::COMMERCIAL => 'Commercial',
        };
    }
}
