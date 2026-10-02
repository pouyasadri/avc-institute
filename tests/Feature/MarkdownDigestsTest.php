<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarkdownDigestsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    protected function markdownHeaders(): array
    {
        return ['Accept' => 'text/markdown'];
    }

    public function test_services_hub_returns_real_markdown_digest(): void
    {
        $response = $this->get('/fa/services', $this->markdownHeaders());

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
        $content = $response->getContent();

        $this->assertStringNotContainsString('optimized for HTML', $content);
        $this->assertStringContainsString('Cite this page', $content);
        $this->assertStringContainsString('/fa/services/', $content);
        $this->assertStringContainsString('llms.txt', $content);
    }

    public function test_service_show_returns_digest_with_faq_and_canonical(): void
    {
        $response = $this->get('/fa/services/student-visa', $this->markdownHeaders());

        $response->assertOk();
        $content = $response->getContent();

        $this->assertStringNotContainsString('optimized for HTML', $content);
        $this->assertStringContainsString('service_slug: student-visa', $content);
        $this->assertStringContainsString('Cite this page', $content);
        $this->assertStringContainsString(url('/fa/services/student-visa'), $content);
        $this->assertStringContainsString('Campus France', $content);
    }

    public function test_all_configured_service_slugs_have_markdown_digests(): void
    {
        foreach (config('site_structure.service_slugs') as $slug) {
            $response = $this->get("/en/services/{$slug}", $this->markdownHeaders());
            $response->assertOk();
            $this->assertStringNotContainsString(
                'optimized for HTML',
                $response->getContent(),
                "Service {$slug} still returns stub markdown"
            );
            $this->assertStringContainsString("service_slug: {$slug}", $response->getContent());
        }
    }

    public function test_calculator_returns_markdown_digest(): void
    {
        $response = $this->get('/fa/calculator', $this->markdownHeaders());

        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringNotContainsString('optimized for HTML', $content);
        $this->assertStringContainsString('Cite this page', $content);
        $this->assertStringContainsString('/fa/services/student-visa', $content);
        $this->assertStringContainsString('CAF', $content);
    }

    public function test_priority_cities_return_markdown_digests(): void
    {
        foreach (['paris', 'lyon', 'toulouse', 'strasbourg'] as $city) {
            $response = $this->get("/fa/cities/{$city}", $this->markdownHeaders());
            $response->assertOk();
            $content = $response->getContent();
            $this->assertStringNotContainsString('optimized for HTML', $content);
            $this->assertStringContainsString("city: {$city}", $content);
            $this->assertStringContainsString('Cite this page', $content);
        }
    }

    public function test_priority_universities_return_markdown_digests(): void
    {
        foreach (['sciences-po', 'paris-saclay-university', 'paris-cite', 'lyon-2'] as $university) {
            $response = $this->get("/fa/universities/{$university}", $this->markdownHeaders());
            $response->assertOk();
            $content = $response->getContent();
            $this->assertStringNotContainsString('optimized for HTML', $content);
            $this->assertStringContainsString("university: {$university}", $content);
            $this->assertStringContainsString('Cite this page', $content);
        }
    }

    public function test_fallback_markdown_no_longer_returns_stub_only_for_blog_index(): void
    {
        $response = $this->get('/en/blog', $this->markdownHeaders());

        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString('Cite this page', $content);
        $this->assertStringContainsString('llms.txt', $content);
        // Either extracted body or short stub with cite block — never blank.
        $this->assertGreaterThan(120, strlen($content));
    }
}
