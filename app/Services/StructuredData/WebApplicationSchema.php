<?php

namespace App\Services\StructuredData;

class WebApplicationSchema extends SchemaBuilder
{
    protected string $url;

    protected string $name;

    protected ?string $description;

    protected ?string $inLanguage;

    public function __construct(
        string $url,
        string $name,
        ?string $description = null,
        ?string $inLanguage = null
    ) {
        $this->setContext()->setType('WebApplication');
        $this->url = $url;
        $this->name = $name;
        $this->description = $description;
        $this->inLanguage = $inLanguage;
    }

    public function build(): array
    {
        $this->add('@id', $this->url)
            ->add('url', $this->url)
            ->add('name', $this->name)
            ->add('description', $this->description)
            ->add('applicationCategory', 'FinanceApplication')
            ->add('applicationSubCategory', 'EducationalApplication')
            ->add('operatingSystem', 'All')
            ->add('browserRequirements', 'Requires JavaScript. Requires HTML5.')
            ->add('softwareHelp', ['@id' => url(($this->inLanguage ?? 'en').'/consult')])
            ->add('provider', [
                '@type' => 'Organization',
                'name' => 'A.V.C Institute (Apply VIP Conseil)',
                'url' => url(($this->inLanguage ?? 'en').'/'),
            ])
            ->add('featureList', [
                'Student budget estimation',
                'France visa financial requirements calculation',
                'CAF housing allowance estimation',
                'City-by-city living cost comparison in France',
            ])
            ->add('offers', [
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'EUR',
            ]);

        if ($this->inLanguage) {
            $this->add('inLanguage', $this->inLanguage);
        }

        return $this->data;
    }
}
