<?php

namespace App\Enums;

enum Locale: string
{
    case EN = 'en';
    case FR = 'fr';
    case FA = 'fa';

    /**
     * Get all supported locale string values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get human-readable label for the locale.
     */
    public function label(): string
    {
        return match ($this) {
            self::EN => 'English',
            self::FR => 'Français',
            self::FA => 'فارسی',
        };
    }

    /**
     * Get text direction for the locale ('ltr' or 'rtl').
     */
    public function direction(): string
    {
        return $this === self::FA ? 'rtl' : 'ltr';
    }

    /**
     * Check if the locale uses right-to-left script.
     */
    public function isRtl(): bool
    {
        return $this === self::FA;
    }

    /**
     * Get default application locale enum instance.
     */
    public static function default(): self
    {
        $default = config('app.locale', 'en');

        return self::tryFrom($default) ?? self::EN;
    }
}
