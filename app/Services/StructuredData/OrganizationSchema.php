<?php

namespace App\Services\StructuredData;

class OrganizationSchema extends SchemaBuilder
{
    public function __construct()
    {
        $this->setContext()->setType('Organization');
    }

    /**
     * Build organization schema from config
     */
    public function build(): array
    {
        $org = config('seo.organization');

        $orgId = rtrim(config('app.url'), '/').'/'.'#organization';

        // Use locale-aware description so LLMs see Persian text for Persian pages
        $description = __('index.schema.description');
        if ($description === 'index.schema.description' || empty($description)) {
            $description = $org['description'];
        }

        $this->add('@id', $orgId)
            ->add('name', $org['name'])
            ->add('legalName', $org['legal_name'])
            ->add('url', $org['url'])
            ->add('logo', $this->buildLogo($org['logo']))
            ->add('description', $description)
            ->add('email', $org['email'])
            ->add('telephone', $org['telephone'])
            ->add('address', $this->buildAddress($org['address']))
            ->add('sameAs', $org['same_as'])
            ->add('founder', $org['founder'])
            ->add('foundingDate', $org['founding_date'])
            ->add('areaServed', ['France', 'Iran'])
            ->add('availableLanguage', ['French', 'Persian', 'English']);

        // Add official French registration identifiers
        if (! empty($org['vat_id'])) {
            $this->add('vatID', $org['vat_id']);
        }
        if (! empty($org['siren'])) {
            $this->add('taxID', $org['siren']);
        }
        if (! empty($org['siret'])) {
            $this->add('identifier', [
                '@type' => 'PropertyValue',
                'name' => 'SIRET',
                'value' => $org['siret'],
                'propertyID' => 'https://www.wikidata.org/wiki/Q1350767',
            ]);
        }
        if (! empty($org['naf_code'])) {
            $this->add('additionalProperty', [
                '@type' => 'PropertyValue',
                'name' => 'Code NAF/APE',
                'value' => $org['naf_code'],
                'description' => $org['naf_label'] ?? '',
            ]);
        }

        // Add contact point
        if (! empty($org['telephone']) || ! empty($org['email'])) {
            $this->add('contactPoint', $this->buildContactPoint($org));
        }

        return $this->data;
    }

    /**
     * Build logo object
     */
    protected function buildLogo(string $logoPath): array
    {
        return [
            '@type' => 'ImageObject',
            'url' => $this->url($logoPath),
        ];
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
     * Build contact point
     */
    protected function buildContactPoint(array $org): array
    {
        $contactPoint = [
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
            'availableLanguage' => ['French', 'Persian', 'English'],
        ];

        if (! empty($org['telephone'])) {
            $contactPoint['telephone'] = $org['telephone'];
        }

        if (! empty($org['email'])) {
            $contactPoint['email'] = $org['email'];
        }

        return $contactPoint;
    }
}
