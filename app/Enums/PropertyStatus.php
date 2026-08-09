<?php

namespace App\Enums;

enum PropertyStatus: string
{
    case AVAILABLE = 'available';
    case PENDING = 'pending';
    case SOLD = 'sold';
    case RENTED = 'rented';

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
            self::AVAILABLE => 'Available',
            self::PENDING => 'Pending',
            self::SOLD => 'Sold',
            self::RENTED => 'Rented',
        };
    }
}
