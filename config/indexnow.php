<?php

return [

    /*
    |--------------------------------------------------------------------------
    | IndexNow API Key
    |--------------------------------------------------------------------------
    | A unique hexadecimal key (8–128 chars) that proves site ownership.
    | This key must be served as a plain-text file at the URL:
    |   https://applyvipconseil.com/{key}.txt
    | The file is located at: public/{key}.txt
    |
    | To rotate the key: generate a new hex string, update this value,
    | rename/update the public key file, and redeploy.
    |--------------------------------------------------------------------------
    */
    'key' => env('INDEXNOW_KEY', '917a0bd55b154c0a96b10f32c5d76e9f'),

    /*
    |--------------------------------------------------------------------------
    | Key File Location (publicly accessible URL)
    |--------------------------------------------------------------------------
    | The full URL where the key verification file is served.
    | Must be reachable by search engine crawlers.
    |--------------------------------------------------------------------------
    */
    'key_location' => env('INDEXNOW_KEY_LOCATION', 'https://applyvipconseil.com/917a0bd55b154c0a96b10f32c5d76e9f.txt'),

    /*
    |--------------------------------------------------------------------------
    | Search Engine Endpoints
    | Priority order: most relevant for Iranian audience first.
    |
    | NOTE: Per the IndexNow spec, submitting to ANY one endpoint will
    | automatically propagate to all other participating engines.
    | We ping all of them independently for maximum reliability and speed.
    |
    | Engines and their Iranian audience relevance:
    |   1. Bing     — Powers DuckDuckGo (widely used in Iran via VPN) + Copilot
    |   2. Yandex   — Very popular in Iran when Google is restricted
    |   3. Yep      — New privacy-focused engine, growing in Iran
    |   4. Naver    — Korean engine, IndexNow participant (lower IR relevance)
    |   5. Seznam   — Czech engine, IndexNow participant (lowest IR relevance)
    |--------------------------------------------------------------------------
    */
    'engines' => [
        [
            'name' => 'Microsoft Bing',
            'endpoint' => 'https://www.bing.com/indexnow',
            'enabled' => true,
            'priority' => 1, // Highest — powers DuckDuckGo + Copilot AI search
        ],
        [
            'name' => 'Yandex',
            'endpoint' => 'https://yandex.com/indexnow',
            'enabled' => true,
            'priority' => 2, // High — extremely popular in Iran when Google blocked
        ],
        [
            'name' => 'Yep',
            'endpoint' => 'https://indexnow.yep.com/indexnow',
            'enabled' => true,
            'priority' => 3, // Growing privacy-focused engine
        ],
        [
            'name' => 'Naver',
            'endpoint' => 'https://searchadvisor.naver.com/indexnow',
            'enabled' => true,
            'priority' => 4, // Korean engine — lower Iranian audience relevance
        ],
        [
            'name' => 'Seznam.cz',
            'endpoint' => 'https://search.seznam.cz/indexnow',
            'enabled' => true,
            'priority' => 5, // Czech engine — lowest Iranian audience relevance
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Behavior Settings
    |--------------------------------------------------------------------------
    */

    // Use the queue to dispatch pings asynchronously (recommended for production).
    // Set to false to ping synchronously (useful for debugging).
    'async' => env('INDEXNOW_ASYNC', true),

    // Queue connection for async jobs. Uses the app's default queue if null.
    'queue_connection' => env('INDEXNOW_QUEUE_CONNECTION', null),

    // Queue name for IndexNow jobs (keeps them separate from other work).
    'queue_name' => env('INDEXNOW_QUEUE_NAME', 'indexnow'),

    // Maximum URLs per batch POST request (IndexNow limit is 10,000).
    'max_urls_per_batch' => 500,

    // Number of seconds to wait before retrying a failed ping.
    'retry_after' => 60,

    // Maximum retry attempts for a failed ping job.
    'max_retries' => 3,

    // Log all ping responses for debugging (disable in production to reduce log noise).
    'log_responses' => env('INDEXNOW_LOG_RESPONSES', false),
];
