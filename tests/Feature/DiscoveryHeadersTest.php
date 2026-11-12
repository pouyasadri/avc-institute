<?php

namespace Tests\Feature;

use App\Services\Discovery\LlmsTxtGenerator;
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
            $this->assertStringContainsString('</llms-full.txt>; rel="service-doc"', $linkHeader);
            $this->assertStringContainsString('</.well-known/agents-index.json>; rel="index"', $linkHeader);
            $this->assertStringContainsString('</.well-known/api-catalog>; rel="api-catalog"', $linkHeader);
            $this->assertStringContainsString('</sitemap.xml>; rel="sitemap"', $linkHeader);
        }
    }

    public function test_content_surfaces_have_discovery_headers(): void
    {
        foreach (['/fa/services', '/fa/calculator', '/fa/cities/paris', '/en/blog'] as $path) {
            $response = $this->get($path);
            $response->assertOk();
            $linkHeader = $response->headers->get('Link');
            $this->assertNotEmpty($linkHeader);
            $this->assertStringContainsString('</llms.txt>; rel="help"', $linkHeader);
            $this->assertStringContainsString('</llms-full.txt>; rel="service-doc"', $linkHeader);
        }
    }

    public function test_unrelated_pages_do_not_have_discovery_headers(): void
    {
        $response = $this->get('/en/contactUs');
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
        $base = rtrim(config('app.url'), '/');

        $response = $this->get('/.well-known/api-catalog');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/linkset+json; charset=utf-8');

        $response->assertJsonPath('linkset.0.anchor', $base.'/');
        $response->assertJsonPath('linkset.0.service-doc.0.href', $base.'/llms.txt');
        $response->assertJsonPath('linkset.0.service-doc.1.href', $base.'/llms-full.txt');
        $response->assertJsonPath('linkset.0.describedby.0.href', $base.'/.well-known/agents-index.json');
        $response->assertJsonPath('linkset.0.sitemap.0.href', $base.'/sitemap.xml');
    }

    public function test_agents_index_endpoint(): void
    {
        $base = rtrim(config('app.url'), '/');

        $response = $this->get('/.well-known/agents-index.json');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json; charset=utf-8');

        $response->assertJsonPath('name', 'A.V.C Institute');
        $response->assertJsonPath('legalName', 'APPLY VIP CONSEIL');
        $response->assertJsonPath('documentation', $base.'/llms.txt');
        $response->assertJsonPath('documentationFull', $base.'/llms-full.txt');
        $response->assertJsonPath('primaryLocale', 'fa');
        $response->assertJsonPath('capabilities.markdownDigests.accept', 'text/markdown');
    }

    public function test_llms_txt_is_served_as_plain_text(): void
    {
        $response = $this->get('/llms.txt');
        $response->assertStatus(200);
        $this->assertStringContainsString('text/plain', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('Canonical name: A.V.C Institute', $response->getContent());
        $this->assertStringContainsString('/.well-known/agents-index.json', $response->getContent());
    }

    public function test_llms_full_txt_is_served_as_plain_text(): void
    {
        if (! file_exists(public_path('llms-full.txt'))) {
            app(LlmsTxtGenerator::class)->generate();
        }

        $response = $this->get('/llms-full.txt');
        $response->assertStatus(200);
        $this->assertStringContainsString('text/plain', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('Full LLM Catalog', $response->getContent());
        $this->assertStringContainsString('Canonical name: A.V.C Institute', $response->getContent());
    }

    public function test_sitemap_includes_discovery_urls(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertOk();
        $body = $response->getContent();
        $this->assertStringContainsString('/llms.txt', $body);
        $this->assertStringContainsString('/llms-full.txt', $body);
        $this->assertStringContainsString('/.well-known/agents-index.json', $body);
    }

    public function test_html_layout_declares_llms_link_tags(): void
    {
        $response = $this->get('/fa');
        $response->assertOk();
        $html = $response->getContent();
        $this->assertStringContainsString('rel="describedby"', $html);
        $this->assertStringContainsString('/llms.txt', $html);
        $this->assertStringContainsString('/llms-full.txt', $html);
    }
}
