<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Official Campus France & Consular Requirements (2026)
    |--------------------------------------------------------------------------
    | The legally established minimum resource threshold defined by the French
    | Ministry of the Interior for VLS-TS student visa approval.
    */
    'official_monthly_minimum' => 615,
    'default_duration_months' => 12,

    /*
    |--------------------------------------------------------------------------
    | Estimated CAF (Caisse d'Allocations Familiales) APL Subsidy
    |--------------------------------------------------------------------------
    | Standard monthly housing allowance deductions granted to foreign students
    | registered in French higher education institutions.
    */
    'caf_allowances' => [
        'crous' => 150,
        'colocation' => 180,
        'private_studio' => 210,
    ],

    /*
    |--------------------------------------------------------------------------
    | City Cost of Living Benchmarks (EUR / month)
    |--------------------------------------------------------------------------
    | Comprehensive real-world metrics based on 2026 student survey data
    | for major French academic hubs.
    */
    'cities' => [
        'paris' => [
            'name_fa' => 'پاریس',
            'name_en' => 'Paris',
            'name_fr' => 'Paris',
            'rents' => [
                'crous' => 380,
                'colocation' => 650,
                'private_studio' => 880,
            ],
            'food' => 280,
            'transport' => 42, // Pass Imagine R student monthly equivalent
            'health_phone' => 40,
            'recommended_monthly_min' => 900,
        ],
        'lyon' => [
            'name_fa' => 'لیون',
            'name_en' => 'Lyon',
            'name_fr' => 'Lyon',
            'rents' => [
                'crous' => 300,
                'colocation' => 480,
                'private_studio' => 620,
            ],
            'food' => 240,
            'transport' => 35,
            'health_phone' => 40,
            'recommended_monthly_min' => 750,
        ],
        'marseille' => [
            'name_fa' => 'مارسی',
            'name_en' => 'Marseille',
            'name_fr' => 'Marseille',
            'rents' => [
                'crous' => 280,
                'colocation' => 440,
                'private_studio' => 580,
            ],
            'food' => 230,
            'transport' => 35,
            'health_phone' => 40,
            'recommended_monthly_min' => 720,
        ],
        'montpellier' => [
            'name_fa' => 'مونپلیه',
            'name_en' => 'Montpellier',
            'name_fr' => 'Montpellier',
            'rents' => [
                'crous' => 270,
                'colocation' => 440,
                'private_studio' => 580,
            ],
            'food' => 230,
            'transport' => 30, // TAM network student
            'health_phone' => 40,
            'recommended_monthly_min' => 720,
        ],
        'toulouse' => [
            'name_fa' => 'تولوز',
            'name_en' => 'Toulouse',
            'name_fr' => 'Toulouse',
            'rents' => [
                'crous' => 270,
                'colocation' => 430,
                'private_studio' => 560,
            ],
            'food' => 220,
            'transport' => 30,
            'health_phone' => 40,
            'recommended_monthly_min' => 700,
        ],
        'strasbourg' => [
            'name_fa' => 'استراسبورگ',
            'name_en' => 'Strasbourg',
            'name_fr' => 'Strasbourg',
            'rents' => [
                'crous' => 260,
                'colocation' => 420,
                'private_studio' => 550,
            ],
            'food' => 220,
            'transport' => 30,
            'health_phone' => 40,
            'recommended_monthly_min' => 700,
        ],
        'nice' => [
            'name_fa' => 'نیس',
            'name_en' => 'Nice',
            'name_fr' => 'Nice',
            'rents' => [
                'crous' => 320,
                'colocation' => 500,
                'private_studio' => 680,
            ],
            'food' => 250,
            'transport' => 35,
            'health_phone' => 40,
            'recommended_monthly_min' => 780,
        ],
        'bordeaux' => [
            'name_fa' => 'بوردو',
            'name_en' => 'Bordeaux',
            'name_fr' => 'Bordeaux',
            'rents' => [
                'crous' => 290,
                'colocation' => 470,
                'private_studio' => 630,
            ],
            'food' => 240,
            'transport' => 35,
            'health_phone' => 40,
            'recommended_monthly_min' => 740,
        ],
        'grenoble' => [
            'name_fa' => 'گرنوبل',
            'name_en' => 'Grenoble',
            'name_fr' => 'Grenoble',
            'rents' => [
                'crous' => 250,
                'colocation' => 400,
                'private_studio' => 520,
            ],
            'food' => 220,
            'transport' => 30,
            'health_phone' => 40,
            'recommended_monthly_min' => 680,
        ],
        'other' => [
            'name_fa' => 'سایر شهرهای دانشگاهی',
            'name_en' => 'Other Academic Cities',
            'name_fr' => 'Autres villes universitaires',
            'rents' => [
                'crous' => 250,
                'colocation' => 390,
                'private_studio' => 500,
            ],
            'food' => 210,
            'transport' => 30,
            'health_phone' => 40,
            'recommended_monthly_min' => 650,
        ],
    ],
];
