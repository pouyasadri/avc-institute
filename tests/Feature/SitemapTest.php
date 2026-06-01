<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test sitemap XML generation, headers, and dynamic lastmod format.
     */
    public function test_sitemap_xml_generation()
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $content = $response->getContent();

        // Check sitemap tags are present
        $this->assertStringContainsString('<urlset', $content);
        $this->assertStringContainsString('</urlset>', $content);
        $this->assertStringContainsString('<loc>', $content);
        $this->assertStringContainsString('<lastmod>', $content);

        // Extract all <lastmod> tag values and verify their format matches W3C Atom format
        preg_match_all('/<lastmod>([^<]+)<\/lastmod>/', $content, $matches);

        $this->assertNotEmpty($matches[1], 'Sitemap should contain lastmod elements');

        foreach ($matches[1] as $lastmod) {
            // Validate it is a valid Atom format (e.g. 2026-06-01T12:00:00+02:00)
            $this->assertMatchesRegularExpression(
                '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[+-]\d{2}:\d{2}$/',
                $lastmod,
                "Lastmod '{$lastmod}' does not match expected Atom date format."
            );
        }
    }
}
