<?php

namespace App\Services\StructuredData;

class ServiceSchema extends SchemaBuilder
{
    public function __construct(
        protected string $name,
        protected string $url,
        protected string $description,
        protected string $providerName = 'A.V.C Institute',
        protected string $areaServed = 'France',
        protected string $serviceType = 'Immigration and Education Consulting'
    ) {}

    public function build(): array
    {
        $this->setContext()
            ->setType('Service')
            ->add('@id', $this->url.'#service')
            ->add('name', $this->name)
            ->add('url', $this->url)
            ->add('description', $this->description)
            ->add('serviceType', $this->serviceType)
            ->add('inLanguage', app()->getLocale())
            ->add('provider', [
                '@type' => 'Organization',
                'name' => $this->providerName,
                'url' => url('/'),
            ])
            ->add('areaServed', [
                '@type' => 'Country',
                'name' => $this->areaServed,
            ])
            ->add('audience', [
                '@type' => 'Audience',
                'audienceType' => 'International students and immigrants in France',
            ])
            ->add('potentialAction', [
                '@type' => 'ReserveAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url(app()->getLocale().'/consult'),
                    'inLanguage' => app()->getLocale(),
                    'actionPlatform' => [
                        'http://schema.org/DesktopWebPlatform',
                        'http://schema.org/MobileWebPlatform',
                    ],
                ],
                'result' => [
                    '@type' => 'Reservation',
                    'name' => 'Consultation Booking',
                ],
            ])
            ->add('availableChannel', [
                '@type' => 'ServiceChannel',
                'servicePhone' => [
                    '@type' => 'ContactPoint',
                    'telephone' => config('seo.organization.telephone', '+33768688326'),
                    'contactType' => 'customer service',
                    'availableLanguage' => ['English', 'French', 'Persian'],
                ],
            ]);

        return $this->data;
    }
}
