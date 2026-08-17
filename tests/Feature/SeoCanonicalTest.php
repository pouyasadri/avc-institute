<?php

namespace Tests\Feature;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoCanonicalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test homepage canonicals and hreflang for all locales.
     */
    public function test_homepage_seo_tags()
    {
        $locales = ['en', 'fr', 'fa'];

        foreach ($locales as $locale) {
            $response = $this->get("/$locale");

            $response->assertStatus(200);

            // Canonical should be self-referencing
            $response->assertSee('<link rel="canonical" href="'.url("/$locale").'" />', false);

            // Check hreflangs
            foreach ($locales as $l) {
                $response->assertSee('hreflang="'.$l.'" href="'.url("/$l").'"', false);
            }
        }
    }

    /**
     * Test static page (Cities) canonicals.
     */
    public function test_cities_page_seo_tags()
    {
        $locales = ['en', 'fr', 'fa'];

        foreach ($locales as $locale) {
            $response = $this->get("/$locale/cities");

            $response->assertStatus(200);

            // Canonical
            $response->assertSee('<link rel="canonical" href="'.url("/$locale/cities").'" />', false);
        }
    }

    /**
     * Test blog page canonicals, meta tags, and structured data.
     */
    public function test_blog_index_seo_tags()
    {
        $locales = ['en', 'fr', 'fa'];

        foreach ($locales as $locale) {
            $response = $this->get("/$locale/blog");

            $response->assertStatus(200);

            // Canonical
            $response->assertSee('<link rel="canonical" href="'.url("/$locale/blog").'" />', false);

            // Hreflang tags
            foreach ($locales as $l) {
                $response->assertSee('hreflang="'.$l.'" href="'.url("/$l/blog").'"', false);
            }

            // Meta tags & Structured Data
            $response->assertSee('<title>', false);
            $response->assertSee('name="description"', false);
            $response->assertSee('name="keywords"', false);
            $response->assertSee('property="og:title"', false);
            $response->assertSee('property="og:description"', false);
            $response->assertSee('name="twitter:card"', false);

            // Structured data
            $response->assertSee('"@type": "CollectionPage"', false);
            $response->assertSee('"@type": "BreadcrumbList"', false);
            $response->assertSee('"@type": "ItemList"', false);
        }

        // Test Persian 2026 specific SEO keywords
        $faResponse = $this->get('/fa/blog');
        $faResponse->assertSee('مهاجرت به فرانسه ۲۰۲۶', false);
        $faResponse->assertSee('ویزای تحصیلی فرانسه ۲۰۲۶', false);
        $faResponse->assertSee('تمکن مالی', false);
    }

    /**
     * Test redirects from root to default locale.
     */
    public function test_root_redirects_to_default_locale()
    {
        $response = $this->get('/');
        $defaultLocale = config('seo.default_locale', 'fa');

        $response->assertStatus(301);
        $response->assertRedirect("/$defaultLocale");
    }

    /**
     * Test that query parameters are stripped from canonical URLs.
     */
    public function test_canonical_strips_query_parameters()
    {
        $url = '/en/universities/lyon-1';
        $dirtyUrl = $url.'?view=university.lyon-1&status=200';

        $response = $this->get($dirtyUrl);

        $response->assertStatus(200);

        // Canonical should be the clean URL, NOT the dirty one
        $cleanCanonical = url($url);
        $response->assertSee('<link rel="canonical" href="'.$cleanCanonical.'" />', false);

        // Ensure the dirty parameters are NOT in the canonical
        $response->assertDontSee('<link rel="canonical" href="'.url($dirtyUrl).'" />', false);
    }

    /**
     * Test Bordeaux city page load.
     */
    public function test_bordeaux_city_page()
    {
        $response = $this->get('/fa/cities/bordeaux');
        $response->assertStatus(200);
    }
}
