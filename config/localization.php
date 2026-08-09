<?php

use App\Enums\Locale;

return [
    // Locales supported by the application (used in routes and detection)
    'supported_locales' => Locale::values(),

    // Locales that are right-to-left
    'rtl_locales' => ['fa'],

    // Enable IP-based geolocation detection
    'enable_ip_detection' => env('LOCALE_IP_DETECTION', true),

    // Detection priority order (higher number = higher priority)
    // route: from URL parameter
    // session: from user's session
    // browser: from Accept-Language header
    // ip: from IP geolocation
    // config: fallback to config('app.locale')
    'detection_priority' => [
        'route' => 100,
        'session' => 90,
        'browser' => 80,
        'ip' => 70,
        'config' => 0,
    ],

    // Country to locale mapping for IP-based detection
    // Maps ISO country codes to supported locales
    'country_locale_map' => [
        // French-speaking countries
        'FR' => 'fr', // France
        'BE' => 'fr', // Belgium
        'CH' => 'fr', // Switzerland
        'CA' => 'fr', // Canada
        'LU' => 'fr', // Luxembourg
        'MC' => 'fr', // Monaco

        // Persian/Farsi-speaking countries
        'IR' => 'fa', // Iran
        'AF' => 'fa', // Afghanistan
        'TJ' => 'fa', // Tajikistan

        // Default to English for other countries
        // US, GB, AU, NZ, etc. will default to 'en' via fallback
    ],

    // Language to locale mapping for browser Accept-Language
    // Maps language codes to supported locales
    'language_locale_map' => [
        'en' => 'en',
        'fr' => 'fr',
        'fa' => 'fa',
        'pe' => 'fa', // Persian alternative code
        'ps' => 'fa', // Pashto (close to Persian)
    ],
];
