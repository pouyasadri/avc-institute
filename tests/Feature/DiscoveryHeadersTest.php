<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscoveryHeadersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that discovery headers are present on the homepage.
     */
    public function test_homepage_has_discovery_headers(): void
    {
        $locales = ['', 'en', 'fr', 'fa'];

        foreach ($locales as $locale) {
            $path = $locale === '' ? '/' : "/$locale";
            $response = $this->get($path);

            if ($path === '/') {
                $response->assertStatus(301); // Root redirect
            } else {
                $response->assertStatus(200);
            }

            $response->assertHeader('Link');
            $linkHeader = $response->headers->get('Link');

            $this->assertStringContainsString('</llms.txt>; rel="help"', $linkHeader);
            $this->assertStringContainsString('</llms.txt>; rel="describedby"', $linkHeader);
            $this->assertStringContainsString('</llms.txt>; rel="service-doc"', $linkHeader);
            $this->assertStringContainsString('</.well-known/api-catalog>; rel="api-catalog"', $linkHeader);
            $this->assertStringContainsString('</sitemap.xml>; rel="sitemap"', $linkHeader);
        }
    }

    /**
     * Test that discovery headers are NOT present on other pages.
     */
    public function test_other_pages_do_not_have_discovery_headers(): void
    {
        $response = $this->get('/en/blog');
        $response->assertStatus(200);

        $linkHeader = $response->headers->get('Link');

        if ($linkHeader) {
            $this->assertStringNotContainsString('</llms.txt>; rel="help"', $linkHeader);
        }
    }

    /**
     * Test that the API catalog is served at the well-known path.
     */
    public function test_api_catalog_endpoint(): void
    {
        $response = $this->get('/.well-known/api-catalog');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/linkset+json; charset=utf-8');

        $response->assertJson([
            'linkset' => [
                [
                    'anchor' => url('/'),
                    'service-doc' => [
                        [
                            'href' => url('/llms.txt'),
                            'type' => 'text/plain',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
