<?php

declare(strict_types=1);

namespace App\Enums\Calculator;

enum VisaRiskLevel: string
{
    case Optimal = 'optimal';
    case Sufficient = 'sufficient';
    case Borderline = 'borderline';
    case HighRisk = 'high_risk';

    public function badgeClass(): string
    {
        return match ($this) {
            self::Optimal => 'bg-success text-white',
            self::Sufficient => 'bg-primary text-white',
            self::Borderline => 'bg-warning text-dark',
            self::HighRisk => 'bg-danger text-white',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Optimal => 'bx bx-check-double',
            self::Sufficient => 'bx bx-check-circle',
            self::Borderline => 'bx bx-error-circle',
            self::HighRisk => 'bx bx-shield-x',
        };
    }

    public function label(string $locale = 'fa'): string
    {
        return match ($locale) {
            'fa' => match ($this) {
                self::Optimal => 'ضریب اطمینان ویزا: ایده‌آل (شانس تایید بالا)',
                self::Sufficient => 'ضریب اطمینان ویزا: مطلوب (تطابق کامل با استاندارد سفارت)',
                self::Borderline => 'ضریب اطمینان ویزا: مرزی (پیشنهاد افزایش تمکن مالی)',
                self::HighRisk => 'ضریب اطمینان ویزا: ریسک بالا (کمتر از کف قانونی کنسولگری)',
            },
            'fr' => match ($this) {
                self::Optimal => 'Niveau de sécurité visa : Optimal (Très favorable)',
                self::Sufficient => 'Niveau de sécurité visa : Conforme aux critères consulaires',
                self::Borderline => 'Niveau de sécurité visa : Juste limite (Renfort conseillé)',
                self::HighRisk => 'Niveau de sécurité visa : Risque de refus élevé',
            },
            default => match ($this) {
                self::Optimal => 'Visa Safety Level: Optimal (High approval probability)',
                self::Sufficient => 'Visa Safety Level: Sufficient (Meets consular standards)',
                self::Borderline => 'Visa Safety Level: Borderline (Buffer strongly recommended)',
                self::HighRisk => 'Visa Safety Level: High Risk of Refusal',
            },
        };
    }
}
