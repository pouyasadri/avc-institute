<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarkdownNegotiationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that requesting markdown on the homepage returns a markdown response.
     */
    public function test_homepage_returns_markdown_when_requested(): void
    {
        $response = $this->get('/en', [
            'Accept' => 'text/markdown',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
        $response->assertHeader('x-markdown-tokens', 'true');

        $content = $response->getContent();
        $this->assertStringContainsString('# ', $content);
        $this->assertStringContainsString('Our Core Services', $content);
        $this->assertStringContainsString('Explore French Cities', $content);
        $this->assertStringContainsString('University Guides', $content);
        $this->assertStringContainsString('Latest Immigration News', $content);
    }

    /**
     * Test that requesting HTML (default) still returns HTML.
     */
    public function test_homepage_returns_html_by_default(): void
    {
        $response = $this->get('/en');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');

        $content = $response->getContent();
        $this->assertStringContainsString('<!DOCTYPE html>', $content);
    }

    /**
     * Test fallback markdown for other pages.
     */
    public function test_fallback_markdown_for_other_pages(): void
    {
        $response = $this->get('/en/blog', [
            'Accept' => 'text/markdown',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');

        $content = $response->getContent();
        $this->assertStringContainsString('# ', $content);
        $this->assertStringContainsString('optimized for HTML', $content);
        $this->assertStringContainsString('/llms.txt', $content);
    }
}
