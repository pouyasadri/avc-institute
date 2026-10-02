<?php

namespace App\Services\Discovery;

/**
 * Builds machine-readable discovery payloads for AI agents (llms.txt companions).
 */
class AgentDiscoveryCatalog
{
    /**
     * Lightweight agent index published at /.well-known/agents-index.json.
     *
     * @return array<string, mixed>
     */
    public function agentsIndex(): array
    {
        $org = config('seo.organization');
        $base = rtrim(config('app.url'), '/');

        return [
            'version' => '1.0',
            'name' => $org['name'] ?? 'A.V.C Institute',
            'legalName' => $org['legal_name'] ?? 'APPLY VIP CONSEIL',
            'alternateName' => $org['alternate_name'] ?? [],
            'url' => $org['url'] ?? $base,
            'description' => $org['description'] ?? '',
            'email' => $org['email'] ?? null,
            'telephone' => $org['telephone'] ?? null,
            'primaryLocale' => config('seo.default_locale', 'fa'),
            'locales' => array_keys(config('seo.locales', [])),
            'documentation' => $base.'/llms.txt',
            'sitemap' => $base.'/sitemap.xml',
            'sameAs' => $org['same_as'] ?? [],
            'endpoints' => [
                [
                    'rel' => 'service-doc',
                    'href' => $base.'/llms.txt',
                    'type' => 'text/plain',
                    'title' => 'Site intent map for LLMs (Persian-first)',
                ],
                [
                    'rel' => 'api-catalog',
                    'href' => $base.'/.well-known/api-catalog',
                    'type' => 'application/linkset+json',
                    'title' => 'RFC 9727 API catalog',
                ],
                [
                    'rel' => 'sitemap',
                    'href' => $base.'/sitemap.xml',
                    'type' => 'application/xml',
                    'title' => 'XML sitemap',
                ],
                [
                    'rel' => 'canonical-home',
                    'href' => $base.'/'.config('seo.default_locale', 'fa'),
                    'type' => 'text/html',
                    'title' => 'Primary locale homepage',
                ],
            ],
        ];
    }

    /**
     * RFC 9727-style linkset for /.well-known/api-catalog.
     *
     * @return array<string, mixed>
     */
    public function apiCatalog(): array
    {
        $base = rtrim(config('app.url'), '/');

        return [
            'linkset' => [
                [
                    'anchor' => $base.'/',
                    'service-doc' => [
                        [
                            'href' => $base.'/llms.txt',
                            'type' => 'text/plain',
                        ],
                    ],
                    'api-catalog' => [
                        [
                            'href' => $base.'/.well-known/api-catalog',
                            'type' => 'application/linkset+json',
                        ],
                    ],
                    'describedby' => [
                        [
                            'href' => $base.'/.well-known/agents-index.json',
                            'type' => 'application/json',
                        ],
                    ],
                    'sitemap' => [
                        [
                            'href' => $base.'/sitemap.xml',
                            'type' => 'application/xml',
                        ],
                    ],
                ],
            ],
        ];
    }
}
