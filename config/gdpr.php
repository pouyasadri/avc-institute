<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GDPR / Privacy Configuration
    |--------------------------------------------------------------------------
    |
    | Version string is stored alongside each form submission so we can prove
    | which version of the policy was in force when consent was given.
    |
    */

    'privacy_policy_version' => '1.0',

    /*
    | How long (in days) to retain form-submission personal data.
    | After this period, records should be purged by a scheduled command.
    */
    'retention_days' => 730, // 2 years

    /*
    | IP / User-Agent retention in days (security purposes only).
    */
    'technical_retention_days' => 90,

    /*
    | DPO contact email — also referenced in the privacy-policy view.
    */
    'dpo_email' => 'dpo@applyvipconseil.com',

    /*
    | Supervisory authority URL (CNIL for France).
    */
    'supervisory_authority_url' => 'https://www.cnil.fr',

    /*
    | Cookie name used for consent storage.
    */
    'cookie_name' => 'gdpr_consent',

    /*
    | Cookie lifetime in minutes (12 months).
    */
    'cookie_minutes' => 525_600,
];
