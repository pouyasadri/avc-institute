<?php

namespace App\Services\StructuredData;

class WebSiteSchema extends SchemaBuilder
{
    protected string $url;

    protected string $name;

    protected ?string $searchUrlTemplate;

    /** @var string|array|null */
    protected mixed $inLanguage;

    public function __construct(string $url, string $name, ?string $searchUrlTemplate = null, string|array|null $inLanguage = null)
    {
        $this->setContext()->setType('WebSite');
        $this->url                = $url;
        $this->name               = $name;
        $this->searchUrlTemplate  = $searchUrlTemplate;
        $this->inLanguage         = $inLanguage;
    }

    public function build(): array
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $websiteId = $baseUrl.'/#website';
        $orgId = $baseUrl.'/#organization';

        $this->add('@id', $websiteId)
            ->add('name', $this->name)
            ->add('url', $this->url)
            ->add('publisher', ['@id' => $orgId]);

        if ($this->inLanguage) {
            $this->add('inLanguage', $this->inLanguage);
        }

        if ($this->searchUrlTemplate) {
            $this->add('potentialAction', [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $this->searchUrlTemplate,
                ],
                'query-input' => 'required name=search_term_string',
            ]);
        }

        return $this->data;
    }
}
