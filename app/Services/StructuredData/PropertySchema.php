<?php

namespace App\Services\StructuredData;

use Illuminate\Support\Facades\Storage;

class PropertySchema extends SchemaBuilder
{
    protected $property;

    protected $translation;

    protected string $locale;

    public function __construct($property, string $locale)
    {
        $this->property = $property;
        $this->locale = $locale;
        $this->translation = $property->getTranslation($locale);

        $this->setContext()->setType('Product');
    }

    /**
     * Build property schema (as Product with Offer)
     */
    public function build(): array
    {
        if (! $this->translation) {
            return [];
        }

        $this->add('name', $this->translation->name)
            ->add('description', strip_tags($this->translation->description ?? ''))
            ->add('image', $this->buildImages())
            ->add('offers', $this->buildOffer())
            ->add('brand', [
                '@type' => 'Organization',
                'name' => config('seo.organization.name'),
            ])
            ->add('url', $this->url(route('properties.show', ['locale' => $this->locale, 'property' => $this->property->id])));

        // Add additional property details
        $this->add('additionalProperty', $this->buildAdditionalProperties());

        return $this->data;
    }

    /**
     * Build for RealEstateListing type (alternative)
     */
    public function buildAsRealEstate(): array
    {
        $this->data = []; // Reset
        $this->setContext()->setType('RealEstateListing');

        if (! $this->translation) {
            return [];
        }

        $this->add('name', $this->translation->name)
            ->add('description', strip_tags($this->translation->description ?? ''))
            ->add('url', $this->url(route('properties.show', ['locale' => $this->locale, 'property' => $this->property->id])))
            ->add('address', $this->buildAddress())
            ->add('geo', $this->buildGeo())
            ->add('numberOfRooms', $this->property->rooms);

        // Add listing details
        if ($this->property->type) {
            $this->add('category', ucfirst($this->property->type));
        }

        // Add offer with price
        $this->add('offers', $this->buildOffer());

        // Add images
        $this->add('image', $this->buildImages());

        return $this->data;
    }

    /**
     * Build images array
     */
    protected function buildImages(): array
    {
        $images = [];

        if ($this->property->main_image) {
            $images[] = Storage::url($this->property->main_image);
        }

        foreach ($this->property->images as $image) {
            $images[] = Storage::url($image->path);
        }

        return $images ?: [$this->asset('assets/img/default-property.jpg')];
    }

    /**
     * Build offer object
     */
    protected function buildOffer(): array
    {
        $availability = $this->property->status === 'available'
            ? 'https://schema.org/InStock'
            : 'https://schema.org/OutOfStock';

        return [
            '@type' => 'Offer',
            'price' => number_format($this->property->price, 2, '.', ''),
            'priceCurrency' => 'EUR',
            'availability' => $availability,
            'url' => $this->url(route('properties.show', ['locale' => $this->locale, 'property' => $this->property->id])),
            'priceValidUntil' => now()->addMonths(3)->format('Y-m-d'),
        ];
    }

    /**
     * Build address object
     */
    protected function buildAddress(): array
    {
        return [
            '@type' => 'PostalAddress',
            'streetAddress' => $this->property->address_line ?? '',
            'addressLocality' => $this->property->city ?? '',
            'addressRegion' => $this->property->region ?? '',
            'postalCode' => $this->property->postal_code ?? '',
            'addressCountry' => $this->property->country ?? 'France',
        ];
    }

    /**
     * Build geo coordinates (placeholder - you can add actual coordinates to your Property model)
     */
    protected function buildGeo(): ?array
    {
        // If you have latitude/longitude in your property model, use them here
        // For now, returning null
        return null;
    }

    /**
     * Build additional properties
     */
    protected function buildAdditionalProperties(): array
    {
        $properties = [];

        $properties[] = [
            '@type' => 'PropertyValue',
            'name' => 'Number of Rooms',
            'value' => $this->property->rooms,
        ];

        $properties[] = [
            '@type' => 'PropertyValue',
            'name' => 'Number of Garages',
            'value' => $this->property->garages,
        ];

        $properties[] = [
            '@type' => 'PropertyValue',
            'name' => 'Property Type',
            'value' => ucfirst($this->property->type),
        ];

        return $properties;
    }
}
