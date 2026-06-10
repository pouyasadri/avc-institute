<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Meta Tags
    |--------------------------------------------------------------------------
    |
    | Default meta tags used across the site when page-specific tags are not provided.
    |
    */
    'defaults' => [
        'title' => env('APP_NAME', 'Apply Vip Conseil'),
        'title_suffix' => ' | Apply Vip Conseil',
        'description' => 'Apply Vip Conseil - Your trusted partner for immigration, education, and real estate services in France. Expert consultation for residence permits, student visas, and property acquisition.',
        'keywords' => 'immigration france, student visa france, real estate france, french residence permit, study in france, property france',
        'author' => 'Apply Vip Conseil',
        'image' => '/assets/img/logo/logo.webp',
        'locale' => 'en_US',
        'type' => 'website',
    ],

    /*
    |--------------------------------------------------------------------------
    | Organization Information
    |--------------------------------------------------------------------------
    |
    | Your business/organization details for Schema.org structured data
    |
    */
    'organization' => [
        'name' => 'Apply Vip Conseil',
        'legal_name' => 'Apply Vip Conseil AVC',
        'url' => env('APP_URL', 'https://applyvipconseil.com'),
        'logo' => '/assets/img/logo/logo.png',
        'description' => 'Expert immigration and education consulting services in France, specializing in student visas, residence permits, and real estate acquisition.',
        'email' => 'info@applyvipconseil.com',
        'telephone' => '+33768688326',
        'address' => [
            'street_address' => '24 Rue de Courcelles', // TODO: Update with your actual office address
            'locality' => 'Paris',
            'region' => 'Île-de-France',
            'postal_code' => '75008',
            'country' => 'France',
        ],
        'same_as' => [
            'https://www.instagram.com/apply_vip_conseil/',
            // Add other social media profiles
        ],
        'founder' => 'Apply Vip Conseil',
        'founding_date' => '2023',
        // // Geo coordinates for LocalBusiness schema (Paris, France — update with exact location)
        // 'geo' => [
        //     'latitude'  => 48.8723,
        //     'longitude' => 2.3029,
        // ],
        // // Google Maps link for hasMap property
        // 'has_map'           => 'https://maps.app.goo.gl/applyvipconseil',
        // // Currencies accepted
        // 'currencies_accepted' => 'EUR',
        // // Payment methods accepted
        // 'payment_accepted'    => 'Cash, Credit Card, Bank Transfer',
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media Settings
    |--------------------------------------------------------------------------
    |
    | Social media handles and settings for Open Graph and Twitter Cards
    |
    */
    'social' => [
        'twitter' => [
            'site' => '@applyvipconseil', // Update with actual handle if available
            'creator' => '@applyvipconseil',
            'card' => 'summary_large_image',
        ],
        'facebook' => [
            'app_id' => env('FACEBOOK_APP_ID', ''),
            'page_id' => env('FACEBOOK_PAGE_ID', ''),
        ],
        'instagram' => 'apply_vip_conseil',
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | Locales supported by your application for hreflang and meta tags
    |
    */
    'locales' => [
        'en' => [
            'code' => 'en',
            'name' => 'English',
            'og_locale' => 'en_US',
        ],
        'fr' => [
            'code' => 'fr',
            'name' => 'Français',
            'og_locale' => 'fr_FR',
        ],
        'fa' => [
            'code' => 'fa',
            'name' => 'فارسی',
            'og_locale' => 'fa_IR',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    */
    'default_locale' => 'fa',

    /*
    |--------------------------------------------------------------------------
    | Robots Settings
    |--------------------------------------------------------------------------
    |
    | Default robots meta tag settings
    |
    */
    'robots' => [
        'default' => 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1',
        'noindex' => 'noindex, nofollow',
    ],

    /*
    |--------------------------------------------------------------------------
    | Structured Data Settings
    |--------------------------------------------------------------------------
    |
    | Settings for Schema.org structured data generation
    |
    */
    'structured_data' => [
        'enable_organization' => true,
        'enable_website' => true,
        'enable_breadcrumbs' => true,
        'enable_local_business' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap Settings
    |--------------------------------------------------------------------------
    */
    'sitemap' => [
        'priorities' => [
            'homepage' => 1.0,
            'blog_post' => 0.8,
            'property' => 0.8,
            'city' => 0.8,
            'university' => 0.75,
            'category' => 0.7,
            'static_page' => 0.9, // Consult page is our primary conversion page — raised from 0.6
        ],
        'changefreq' => [
            'homepage' => 'daily',
            'blog_post' => 'weekly',
            'property' => 'weekly',
            'city' => 'monthly',
            'university' => 'monthly',
            'category' => 'weekly',
            'static_page' => 'monthly',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Settings
    |--------------------------------------------------------------------------
    */
    'images' => [
        'default_og_image' => '/assets/img/og-default.png',
        'default_twitter_image' => '/assets/img/twitter-default.png',
        'sizes' => [
            'og' => ['width' => 1200, 'height' => 630],
            'twitter' => ['width' => 1200, 'height' => 600],
        ],
    ],
];
