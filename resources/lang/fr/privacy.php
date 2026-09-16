<?php

return [
    // Page meta
    'meta' => [
        'title' => 'Politique de Confidentialité – Institut A.V.C | Conformité RGPD',
        'description' => 'Lisez la politique de confidentialité complète de l\'Institut A.V.C : comment nous collectons, utilisons et protégeons vos données personnelles conformément au RGPD (UE 2016/679).',
        'keywords' => 'politique de confidentialité, RGPD, données personnelles, Institut A.V.C, protection des données',
    ],

    'breadcrumb' => [
        'home' => 'Accueil',
        'privacy' => 'Politique de Confidentialité',
    ],

    'title' => 'Politique de Confidentialité',
    'version' => '1.0',
    'updated' => 'Dernière mise à jour : septembre 2026',
    'intro' => 'L\'Institut A.V.C (« nous », « notre ») s\'engage à protéger vos données personnelles. La présente politique explique quelles données personnelles nous collectons, pourquoi nous les collectons, comment nous les utilisons et quels droits vous détenez en vertu du Règlement général sur la protection des données (UE) 2016/679 (RGPD) et du droit français applicable. Cette politique s\'applique à tous les visiteurs et clients de <strong>applyvipconseil.com</strong>.',

    // 1 — Data controller
    'controller' => [
        'heading' => '1. Responsable du traitement',
        'body' => 'Le responsable du traitement de vos données personnelles est :',
        'name' => 'ApplyVIP Conseil (Institut A.V.C)',
        'address' => '67000 Strasbourg, France',
        'email' => 'info@applyvipconseil.com',
        'phone' => '+33 7 68 68 83 26',
        'note' => 'Vous pouvez contacter notre Délégué à la Protection des Données (DPO) à tout moment à l\'adresse <a href="mailto:dpo@applyvipconseil.com">dpo@applyvipconseil.com</a>.',
    ],

    // 2 — Data collected
    'data_collected' => [
        'heading' => '2. Données que nous collectons',
        'intro' => 'Nous collectons uniquement les données strictement nécessaires aux finalités décrites ci-dessous :',
        'categories' => [
            [
                'name' => 'Données d\'identité et de contact',
                'items' => ['Nom et prénom complets', 'Adresse e-mail', 'Numéro de téléphone / WhatsApp'],
            ],
            [
                'name' => 'Données de demande',
                'items' => ['Sujet de votre demande', 'Contenu du message', 'Type de visa ou service souhaité'],
            ],
            [
                'name' => 'Données techniques',
                'items' => [
                    'Adresse IP (enregistrée automatiquement à chaque requête pour des raisons de sécurité)',
                    'Chaîne User-Agent du navigateur',
                    'Langue préférée / localisation',
                ],
            ],
            [
                'name' => 'Données de consentement',
                'items' => [
                    'Votre choix d\'acceptation ou de refus du bandeau cookie (stocké dans le cookie <code>gdpr_consent</code>)',
                    'Date et heure du consentement',
                    'Version de la politique de confidentialité en vigueur au moment du consentement',
                ],
            ],
        ],
        'not_collected' => 'Nous ne collectons <strong>pas</strong> de données de catégories spéciales (article 9 RGPD), de données financières, ni de données relatives à des mineurs de moins de 18 ans.',
    ],

    // 3 — Legal basis
    'legal_basis' => [
        'heading' => '3. Base légale du traitement',
        'intro' => 'Nous nous appuyons sur les bases légales suivantes de l\'article 6 RGPD :',
        'bases' => [
            [
                'basis' => 'Intérêts légitimes (art. 6(1)(f))',
                'use' => 'Répondre aux demandes générales ; exploiter et améliorer notre site ; journalisation de sécurité.',
            ],
            [
                'basis' => 'Exécution d\'un contrat / mesures précontractuelles (art. 6(1)(b))',
                'use' => 'Traitement des demandes de consultation et de services à votre initiative.',
            ],
            [
                'basis' => 'Consentement (art. 6(1)(a))',
                'use' => 'Activation de Microsoft Clarity et dépôt de cookies non essentiels — uniquement après votre clic sur « Accepter ».',
            ],
            [
                'basis' => 'Obligation légale (art. 6(1)(c))',
                'use' => 'Conservation des enregistrements exigée par la réglementation française comptable et anti-fraude.',
            ],
        ],
    ],

    // 4 — Purposes
    'purposes' => [
        'heading' => '4. Finalités du traitement',
        'items' => [
            'Répondre à vos formulaires de contact, de consultation ou de question',
            'Vous envoyer un e-mail de confirmation après soumission du formulaire',
            'Informer notre équipe des nouvelles demandes par e-mail interne',
            'Améliorer et sécuriser notre site (analyses uniquement avec votre consentement)',
            'Respecter les obligations légales françaises et européennes',
        ],
    ],

    // 5 — Cookies
    'cookies' => [
        'heading' => '5. Cookies',
        'intro' => 'Nous utilisons un ensemble minimal de cookies. Aucun cookie publicitaire ou de pistage tiers n\'est déposé sans votre consentement.',
        'table_headers' => ['Nom du cookie', 'Finalité', 'Durée', 'Type'],
        'items' => [
            [
                'name' => 'gdpr_consent',
                'purpose' => 'Enregistre votre choix de consentement (accepté / refusé)',
                'expiry' => '12 mois',
                'type' => 'First-party · HttpOnly · Secure · SameSite=Lax',
            ],
            [
                'name' => 'XSRF-TOKEN',
                'purpose' => 'Protection CSRF (intégré à Laravel)',
                'expiry' => 'Session',
                'type' => 'First-party · Secure · SameSite=Lax',
            ],
            [
                'name' => 'laravel_session',
                'purpose' => 'Maintien de votre session anonyme',
                'expiry' => 'Session',
                'type' => 'First-party · HttpOnly · Secure · SameSite=Lax',
            ],
            [
                'name' => 'Microsoft Clarity (_clsk, _clck, MR)',
                'purpose' => 'Analyses comportementales — enregistrements de session et cartes de chaleur',
                'expiry' => 'Jusqu\'à 1 an',
                'type' => 'Third-party · chargé <strong>après</strong> votre consentement uniquement',
            ],
        ],
        'change_mind' => 'Vous pouvez retirer votre consentement à tout moment en cliquant sur « Paramètres des cookies » dans le pied de page ou en effaçant les cookies de votre navigateur.',
    ],

    // 6 — Data sharing
    'sharing' => [
        'heading' => '6. Destinataires de vos données',
        'intro' => 'Nous ne vendons pas vos données. Nous les partageons uniquement dans les limites nécessaires à nos prestations :',
        'recipients' => [
            [
                'name' => 'Microsoft Clarity',
                'purpose' => 'Analyses web (cartes de chaleur / enregistrements) — uniquement avec votre consentement',
                'country' => 'États-Unis (couvert par le cadre EU–US Data Privacy Framework)',
            ],
            [
                'name' => 'Prestataire d\'e-mails transactionnels',
                'purpose' => 'Envoi des e-mails de confirmation',
                'country' => 'UE / EEE',
            ],
            [
                'name' => 'PlanetHoster (hébergeur)',
                'purpose' => 'Hébergement serveur et base de données',
                'country' => 'France / Canada (DPA conforme RGPD en place)',
            ],
        ],
    ],

    // 7 — Retention
    'retention' => [
        'heading' => '7. Durées de conservation',
        'body' => 'Nous conservons les données des formulaires pendant une durée maximale de <strong>2 ans</strong> à compter de la date de soumission, après quoi elles sont supprimées définitivement. Les adresses IP et chaînes User-Agent sont conservées <strong>90 jours</strong> à des fins de sécurité uniquement. Les enregistrements de consentement sont conservés <strong>5 ans</strong> pour prouver la conformité, comme l\'exige l\'article 7(1) RGPD.',
    ],

    // 8 — Your rights
    'rights' => [
        'heading' => '8. Vos droits au titre du RGPD',
        'intro' => 'En tant que personne concernée dans l\'UE / EEE, vous bénéficiez des droits suivants :',
        'items' => [
            ['right' => 'Droit d\'accès (art. 15)',       'desc' => 'Obtenir une copie des données personnelles que nous détenons à votre sujet.'],
            ['right' => 'Droit de rectification (art. 16)', 'desc' => 'Faire corriger des données inexactes sans délai excessif.'],
            ['right' => 'Droit à l\'effacement (art. 17)', 'desc' => 'Demander la suppression de vos données (« droit à l\'oubli »).'],
            ['right' => 'Droit à la limitation (art. 18)',  'desc' => 'Demander la suspension du traitement pendant la résolution d\'un litige.'],
            ['right' => 'Droit à la portabilité (art. 20)', 'desc' => 'Recevoir vos données dans un format structuré et lisible par machine.'],
            ['right' => 'Droit d\'opposition (art. 21)',    'desc' => 'Vous opposer au traitement fondé sur les intérêts légitimes.'],
            ['right' => 'Droit de retirer le consentement', 'desc' => 'Retirer à tout moment votre consentement aux cookies analytiques sans affecter la licéité du traitement antérieur.'],
        ],
        'exercise' => 'Pour exercer ces droits, écrivez-nous à <a href="mailto:dpo@applyvipconseil.com">dpo@applyvipconseil.com</a> ou à l\'adresse postale indiquée ci-dessus. Nous répondrons dans un délai de <strong>30 jours</strong>.',
        'supervisory' => 'Si vous estimez que nous ne traitons pas vos données correctement, vous disposez du droit d\'introduire une réclamation auprès de l\'autorité de contrôle française : <a href="https://www.cnil.fr" target="_blank" rel="noopener noreferrer">CNIL — Commission Nationale de l\'Informatique et des Libertés</a>.',
    ],

    // 9 — Security
    'security' => [
        'heading' => '9. Sécurité',
        'body' => 'Nous mettons en œuvre des mesures techniques et organisationnelles comprenant le chiffrement HTTPS/TLS, le hachage bcrypt des mots de passe, la protection CSRF, les attributs HttpOnly et Secure sur les cookies, et des contrôles d\'accès limitant la consultation des soumissions.',
    ],

    // 10 — International transfers
    'transfers' => [
        'heading' => '10. Transferts internationaux',
        'body' => 'Lorsque des données sont transférées hors de l\'EEE (par ex. Microsoft Clarity), nous veillons à ce que des garanties adéquates soient en place, notamment le cadre EU–US Data Privacy Framework (DPF) ou les Clauses contractuelles types (CCT) conformément au Chapitre V du RGPD.',
    ],

    // 11 — Children
    'children' => [
        'heading' => '11. Protection des mineurs',
        'body' => 'Notre site et nos services ne s\'adressent pas aux moins de 18 ans. Nous ne collectons pas sciemment de données relatives à des mineurs. Si vous pensez qu\'un enfant nous a transmis des données personnelles, contactez-nous immédiatement afin que nous puissions les supprimer.',
    ],

    // 12 — Updates
    'updates' => [
        'heading' => '12. Modifications de la présente politique',
        'body' => 'Nous pouvons mettre à jour cette politique ponctuellement. Le numéro de version en vigueur et la date de dernière modification figurent en tête de page. Les changements importants seront notifiés par un bandeau sur le site.',
    ],

    // 13 — Contact
    'contact_us' => [
        'heading' => '13. Nous contacter',
        'body' => 'Pour toute question relative à la vie privée ou pour exercer vos droits, contactez-nous à :',
    ],

    // Libellé de consentement du formulaire
    'form' => [
        'gdpr_consent_label' => 'J\'ai lu et j\'accepte la <a href=":url" target="_blank" class="text-decoration-underline">Politique de confidentialité</a> et consens au traitement de mes données personnelles aux fins du traitement de ma demande.',
        'gdpr_consent_error' => 'Vous devez accepter la politique de confidentialité pour soumettre ce formulaire.',
    ],

    // Formulaire de demande de droits (Art. 15–22 RGPD)
    'data_rights' => [
        'meta' => [
            'title' => 'Vos droits — Institut A.V.C | RGPD Art. 15–22',
        ],
        'title' => 'Exercez vos droits sur vos données',
        'subtitle' => 'Soumettez une demande pour accéder, corriger, supprimer ou exporter vos données personnelles.',
        'response_time_heading' => 'Réponse sous 30 jours',
        'response_time_body' => 'Conformément à l\'<strong>article 12 du RGPD</strong>, nous sommes tenus de répondre à toutes les demandes dans un délai de 30 jours. Vous recevrez une confirmation par e-mail immédiatement.',
        'form' => [
            'heading' => 'Soumettre une demande de droit',
            'email' => 'Votre adresse e-mail',
            'email_hint' => 'Doit correspondre à l\'adresse utilisée lors d\'une soumission sur notre site.',
            'request_type' => 'Type de demande',
            'notes' => 'Précisions supplémentaires (facultatif)',
            'notes_placeholder' => 'Décrivez votre demande plus en détail si nécessaire…',
            'submit' => 'Envoyer la demande',
            'success' => 'Votre demande a été reçue. Vous recevrez un e-mail de confirmation. Nous répondrons dans un délai de 30 jours (RGPD Art. 12).',
            'error' => 'Une erreur s\'est produite. Veuillez réessayer ou nous contacter directement à dpo@applyvipconseil.com.',
        ],
        'types' => [
            'access' => ['label' => 'Droit d\'accès',               'article' => 'Art. 15', 'desc' => 'Obtenir une copie des données personnelles que nous détenons.'],
            'rectification' => ['label' => 'Droit de rectification',        'article' => 'Art. 16', 'desc' => 'Corriger des données inexactes sans délai excessif.'],
            'erasure' => ['label' => 'Droit à l\'effacement',         'article' => 'Art. 17', 'desc' => 'Demander la suppression de vos données (« droit à l\'oubli »).'],
            'portability' => ['label' => 'Droit à la portabilité',        'article' => 'Art. 20', 'desc' => 'Recevoir vos données dans un format structuré et lisible par machine.'],
            'objection' => ['label' => 'Droit d\'opposition',           'article' => 'Art. 21', 'desc' => 'Vous opposer au traitement fondé sur les intérêts légitimes.'],
            'restriction' => ['label' => 'Droit à la limitation',         'article' => 'Art. 18', 'desc' => 'Demander la suspension du traitement en attente d\'une décision.'],
        ],
    ],
];
