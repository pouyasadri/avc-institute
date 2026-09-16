<?php

return [
    // Page meta
    'meta' => [
        'title' => 'Privacy Policy – A.V.C Institute | GDPR Compliance',
        'description' => 'Read A.V.C Institute\'s full privacy policy: how we collect, use, and protect your personal data in accordance with GDPR (EU 2016/679).',
        'keywords' => 'privacy policy, GDPR, personal data, A.V.C Institute, data protection',
    ],

    'breadcrumb' => [
        'home' => 'Home',
        'privacy' => 'Privacy Policy',
    ],

    'title' => 'Privacy Policy',
    'version' => '1.0',
    'updated' => 'Last updated: September 2026',
    'intro' => 'A.V.C Institute ("we", "us", "our") is committed to protecting your personal data. This Privacy Policy explains which personal data we collect, why we collect it, how we use it, and what rights you hold under the General Data Protection Regulation (EU) 2016/679 (GDPR) and applicable French data-protection law. This policy applies to all visitors and clients of <strong>applyvipconseil.com</strong>.',

    // 1 — Data controller
    'controller' => [
        'heading' => '1. Data Controller',
        'body' => 'The data controller responsible for your personal data is:',
        'name' => 'ApplyVIP Conseil (A.V.C Institute)',
        'address' => '67000 Strasbourg, France',
        'email' => 'info@applyvipconseil.com',
        'phone' => '+33 7 68 68 83 26',
        'note' => 'You may contact our Data Protection Officer (DPO) at any time at <a href="mailto:dpo@applyvipconseil.com">dpo@applyvipconseil.com</a>.',
    ],

    // 2 — Data collected
    'data_collected' => [
        'heading' => '2. Data We Collect',
        'intro' => 'We collect only the minimum data necessary for the purposes described below:',
        'categories' => [
            [
                'name' => 'Identity & Contact Data',
                'items' => ['Full name', 'Email address', 'Phone / WhatsApp number'],
            ],
            [
                'name' => 'Request Data',
                'items' => ['Subject of your enquiry', 'Message content', 'Type of visa or service requested'],
            ],
            [
                'name' => 'Technical Data',
                'items' => [
                    'IP address (logged automatically on each request for security purposes)',
                    'Browser User-Agent string',
                    'Preferred language / locale',
                ],
            ],
            [
                'name' => 'Consent Data',
                'items' => [
                    'Whether you accepted or rejected our cookie notice (stored in the <code>gdpr_consent</code> cookie)',
                    'Date and time of consent',
                    'Version of the privacy policy in force at time of consent',
                ],
            ],
        ],
        'not_collected' => 'We do <strong>not</strong> collect special-category data (Article 9 GDPR), financial data, or children\'s data (our services are directed at adults aged 18+).',
    ],

    // 3 — Legal basis
    'legal_basis' => [
        'heading' => '3. Legal Basis for Processing',
        'intro' => 'We rely on the following legal bases under Article 6 GDPR:',
        'bases' => [
            [
                'basis' => 'Legitimate interests (Art. 6(1)(f))',
                'use' => 'Responding to general enquiries; operating and improving our website; security logging.',
            ],
            [
                'basis' => 'Performance of a contract / pre-contractual steps (Art. 6(1)(b))',
                'use' => 'Processing consultation and service requests at your initiative.',
            ],
            [
                'basis' => 'Consent (Art. 6(1)(a))',
                'use' => 'Activating Microsoft Clarity analytics and setting non-essential cookies — only after you click "Accept" in the cookie notice.',
            ],
            [
                'basis' => 'Legal obligation (Art. 6(1)(c))',
                'use' => 'Retaining records as required by French accounting and anti-fraud regulations.',
            ],
        ],
    ],

    // 4 — Purposes
    'purposes' => [
        'heading' => '4. Purposes of Processing',
        'items' => [
            'Replying to your contact, consultation, or question form submissions',
            'Sending you a confirmation email after form submission',
            'Notifying our team of new enquiries via internal email',
            'Improving and securing our website (analytics only with your consent)',
            'Complying with French and EU legal obligations',
        ],
    ],

    // 5 — Cookies
    'cookies' => [
        'heading' => '5. Cookies',
        'intro' => 'We use a minimal set of cookies. No third-party advertising or tracking cookies are set without your consent.',
        'table_headers' => ['Cookie name', 'Purpose', 'Expiry', 'Type'],
        'items' => [
            [
                'name' => 'gdpr_consent',
                'purpose' => 'Stores your cookie-consent choice (accepted / rejected)',
                'expiry' => '12 months',
                'type' => 'First-party · HttpOnly · Secure · SameSite=Lax',
            ],
            [
                'name' => 'XSRF-TOKEN',
                'purpose' => 'CSRF protection (Laravel built-in)',
                'expiry' => 'Session',
                'type' => 'First-party · Secure · SameSite=Lax',
            ],
            [
                'name' => 'laravel_session',
                'purpose' => 'Maintains your anonymous session',
                'expiry' => 'Session',
                'type' => 'First-party · HttpOnly · Secure · SameSite=Lax',
            ],
            [
                'name' => 'Microsoft Clarity (_clsk, _clck, MR)',
                'purpose' => 'Behavioural analytics — session heatmaps and recordings',
                'expiry' => 'Up to 1 year',
                'type' => 'Third-party · only loaded <strong>after</strong> your consent',
            ],
        ],
        'change_mind' => 'You can withdraw consent at any time by clicking "Cookie Settings" in the footer, or by clearing your browser cookies.',
    ],

    // 6 — Data sharing
    'sharing' => [
        'heading' => '6. Who We Share Your Data With',
        'intro' => 'We do not sell your data. We share it only as required to run our service:',
        'recipients' => [
            [
                'name' => 'Microsoft Clarity',
                'purpose' => 'Web analytics (heatmaps / recordings) — only with your consent',
                'country' => 'USA (covered by EU–US Data Privacy Framework)',
            ],
            [
                'name' => 'Email infrastructure provider',
                'purpose' => 'Sending transactional confirmation emails',
                'country' => 'EU / EEA',
            ],
            [
                'name' => 'PlanetHoster (web host)',
                'purpose' => 'Server and database hosting',
                'country' => 'France / Canada (GDPR-compliant DPA in place)',
            ],
        ],
    ],

    // 7 — Retention
    'retention' => [
        'heading' => '7. Data Retention',
        'body' => 'We keep form-submission data for a maximum of <strong>2 years</strong> from the date of submission, after which records are permanently deleted. IP addresses and user-agent strings are retained for <strong>90 days</strong> for security purposes only. Consent records are kept for <strong>5 years</strong> to demonstrate compliance, as required by Article 7(1) GDPR.',
    ],

    // 8 — Your rights
    'rights' => [
        'heading' => '8. Your Rights Under GDPR',
        'intro' => 'As a data subject in the EU / EEA, you have the following rights:',
        'items' => [
            ['right' => 'Right of access (Art. 15)',       'desc' => 'Obtain a copy of the personal data we hold about you.'],
            ['right' => 'Right to rectification (Art. 16)', 'desc' => 'Have inaccurate data corrected without undue delay.'],
            ['right' => 'Right to erasure (Art. 17)',       'desc' => 'Request deletion of your data ("right to be forgotten").'],
            ['right' => 'Right to restriction (Art. 18)',   'desc' => 'Ask us to pause processing while a dispute is resolved.'],
            ['right' => 'Right to portability (Art. 20)',   'desc' => 'Receive your data in a structured, machine-readable format.'],
            ['right' => 'Right to object (Art. 21)',        'desc' => 'Object to processing based on legitimate interests.'],
            ['right' => 'Right to withdraw consent',        'desc' => 'Withdraw consent for analytics cookies at any time without affecting the lawfulness of prior processing.'],
        ],
        'exercise' => 'To exercise any of these rights, please email us at <a href="mailto:dpo@applyvipconseil.com">dpo@applyvipconseil.com</a> or write to us at the address above. We will respond within <strong>30 days</strong>.',
        'supervisory' => 'If you believe we are not handling your data correctly, you have the right to lodge a complaint with the French data-protection authority: <a href="https://www.cnil.fr" target="_blank" rel="noopener noreferrer">CNIL — Commission Nationale de l\'Informatique et des Libertés</a>.',
    ],

    // 9 — Security
    'security' => [
        'heading' => '9. Security',
        'body' => 'We implement technical and organisational measures including HTTPS/TLS encryption in transit, bcrypt password hashing, CSRF protection, HttpOnly and Secure cookie flags, and access controls limiting who can view submission data.',
    ],

    // 10 — International transfers
    'transfers' => [
        'heading' => '10. International Transfers',
        'body' => 'Where data is transferred outside the EEA (e.g. Microsoft Clarity, whose servers may process data in the USA), we ensure adequate safeguards are in place — including the EU–US Data Privacy Framework (DPF) or Standard Contractual Clauses (SCCs) as required by Chapter V GDPR.',
    ],

    // 11 — Children
    'children' => [
        'heading' => '11. Children\'s Privacy',
        'body' => 'Our website and services are not directed at children under 18. We do not knowingly collect data from minors. If you believe a child has provided us with personal data, please contact us immediately so we can delete it.',
    ],

    // 12 — Updates
    'updates' => [
        'heading' => '12. Changes to This Policy',
        'body' => 'We may update this Privacy Policy from time to time. The current version number and date of last update are shown at the top of this page. Material changes will be notified via a banner on the website.',
    ],

    // 13 — Contact
    'contact_us' => [
        'heading' => '13. Contact Us',
        'body' => 'For any privacy-related question or to exercise your rights, contact us at:',
    ],

    // Form consent label (used in contact / consult / question / comment forms)
    'form' => [
        'gdpr_consent_label' => 'I have read and agree to the <a href=":url" target="_blank" class="text-decoration-underline">Privacy Policy</a> and consent to the processing of my personal data for the purpose of handling my enquiry.',
        'gdpr_consent_error' => 'You must accept the Privacy Policy to submit this form.',
    ],

    // Data-Subject Rights Request Form (Art. 15–22)
    'data_rights' => [
        'meta' => [
            'title' => 'Your Data Rights — A.V.C Institute | GDPR Art. 15–22',
        ],
        'title' => 'Exercise Your Data Rights',
        'subtitle' => 'Submit a request to access, correct, delete, or export the personal data we hold about you.',
        'response_time_heading' => 'Response within 30 days',
        'response_time_body' => 'Under <strong>GDPR Article 12</strong>, we are required to respond to all data rights requests within 30 days of receipt. You will receive an email confirmation immediately after submitting.',
        'form' => [
            'heading' => 'Submit a Data Rights Request',
            'email' => 'Your Email Address',
            'email_hint' => 'Must match the email used when submitting a form on our site.',
            'request_type' => 'Type of Request',
            'notes' => 'Additional Details (optional)',
            'notes_placeholder' => 'Describe your request in more detail if necessary…',
            'submit' => 'Submit Request',
            'success' => 'Your request has been received. You will get a confirmation email shortly. We will respond within 30 days (GDPR Art. 12).',
            'error' => 'An error occurred while processing your request. Please try again or contact us directly at dpo@applyvipconseil.com.',
        ],
        'types' => [
            'access' => ['label' => 'Right of Access',       'article' => 'Art. 15', 'desc' => 'Obtain a copy of the personal data we hold about you.'],
            'rectification' => ['label' => 'Right to Rectification', 'article' => 'Art. 16', 'desc' => 'Correct inaccurate personal data without undue delay.'],
            'erasure' => ['label' => 'Right to Erasure',       'article' => 'Art. 17', 'desc' => 'Request deletion of your data ("right to be forgotten").'],
            'portability' => ['label' => 'Right to Portability',   'article' => 'Art. 20', 'desc' => 'Receive your data in a structured, machine-readable format.'],
            'objection' => ['label' => 'Right to Object',        'article' => 'Art. 21', 'desc' => 'Object to processing based on legitimate interests.'],
            'restriction' => ['label' => 'Right to Restriction',   'article' => 'Art. 18', 'desc' => 'Request suspension of processing pending resolution of a dispute.'],
        ],
    ],
];
