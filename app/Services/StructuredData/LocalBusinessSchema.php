<?php

namespace App\Services\StructuredData;

class LocalBusinessSchema extends SchemaBuilder
{
    public function __construct()
    {
        $this->setContext()->setType('LocalBusiness');
    }

    /**
     * Build local business schema from config
     */
    public function build(): array
    {
        $org = config('seo.organization');

        $baseUrl    = rtrim(config('app.url'), '/');
        $orgId      = $baseUrl . '/#organization';
        $businessId = $baseUrl . '/#localbusiness';

        $this->add('@id', $businessId)
            ->add('name', $org['name'])
            ->add('image', $this->url($org['logo']))
            ->add('url', $org['url'])
            ->add('telephone', $org['telephone'])
            ->add('email', $org['email'])
            ->add('address', $this->buildAddress($org['address']))
            ->add('priceRange', '€€')
            ->add('currenciesAccepted', $org['currencies_accepted'] ?? 'EUR')
            ->add('paymentAccepted', $org['payment_accepted'] ?? 'Cash, Credit Card')
            ->add('sameAs', $org['same_as'])
            ->add('parentOrganization', ['@id' => $orgId]);

        // Geo coordinates (improves Google Maps association)
        if (! empty($org['geo'])) {
            $this->add('geo', [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $org['geo']['latitude'],
                'longitude' => $org['geo']['longitude'],
            ]);
        }

        // Google Maps link
        if (! empty($org['has_map'])) {
            $this->add('hasMap', $org['has_map']);
        }

        // Add opening hours
        $this->add('openingHoursSpecification', $this->buildOpeningHours());

        // Add services offered
        $this->add('makesOffer', $this->buildServices());

        return $this->data;
    }

    /**
     * Build postal address
     */
    protected function buildAddress(array $address): array
    {
        return [
            '@type' => 'PostalAddress',
            'streetAddress' => $address['street_address'] ?? '',
            'addressLocality' => $address['locality'] ?? '',
            'addressRegion' => $address['region'] ?? '',
            'postalCode' => $address['postal_code'] ?? '',
            'addressCountry' => $address['country'] ?? '',
        ];
    }

    /**
     * Build opening hours
     */
    protected function buildOpeningHours(): array
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $hours = [];

        foreach ($days as $day) {
            $hours[] = [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => $day,
                'opens' => '09:00',
                'closes' => '18:00',
            ];
        }

        return $hours;
    }

    /**
     * Build services offered
     */
    protected function buildServices(): array
    {
        return [
            [
                '@type' => 'Offer',
                'itemOffered' => [
                    '@type' => 'Service',
                    'name' => 'Immigration Consulting',
                    'description' => 'Expert immigration consulting services for France',
                ],
            ],
            [
                '@type' => 'Offer',
                'itemOffered' => [
                    '@type' => 'Service',
                    'name' => 'Student Visa Assistance',
                    'description' => 'Complete support for student visa applications',
                ],
            ],
            [
                '@type' => 'Offer',
                'itemOffered' => [
                    '@type' => 'Service',
                    'name' => 'Real Estate Services',
                    'description' => 'Property acquisition and rental services in France',
                ],
            ],
        ];
    }
}
