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
    ) {
    }

    public function build(): array
    {
        $this->setContext()
            ->setType('Service')
            ->add('name', $this->name)
            ->add('url', $this->url)
            ->add('description', $this->description)
            ->add('serviceType', $this->serviceType)
            ->add('provider', [
                '@type' => 'Organization',
                'name' => $this->providerName,
                'url' => url('/'),
            ])
            ->add('areaServed', [
                '@type' => 'Country',
                'name' => $this->areaServed,
            ]);

        return $this->data;
    }
}
