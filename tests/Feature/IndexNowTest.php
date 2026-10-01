<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use App\Services\IndexNowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IndexNowTest extends TestCase
{
    use RefreshDatabase;

    public function test_build_all_site_urls_includes_legal_and_localized_blog_slugs(): void
    {
        $author = User::factory()->create([
            'password' => 'password',
        ]);
        $category = BlogCategory::create();

        $blog = Blog::create([
            'status' => 'published',
            'author_id' => $author->id,
            'category_id' => $category->id,
            'published_at' => now(),
        ]);
        $blog->translations()->create([
            'locale' => 'fa',
            'slug' => 'test-blog-post-fa',
            'title' => 'عنوان آزمایشی',
            'body' => 'محتوا',
        ]);
        $blog->translations()->create([
            'locale' => 'en',
            'slug' => 'test-blog-post-en',
            'title' => 'Test Blog Post',
            'body' => 'Content',
        ]);
        $blog->translations()->create([
            'locale' => 'fr',
            'slug' => 'test-blog-post-fr',
            'title' => 'Article de test',
            'body' => 'Contenu',
        ]);

        $service = app(IndexNowService::class);
        $urls = $service->buildAllSiteUrls();

        // Check legal pages are present
        $this->assertContains('https://applyvipconseil.com/fa/legal', $urls);
        $this->assertContains('https://applyvipconseil.com/en/legal', $urls);
        $this->assertContains('https://applyvipconseil.com/fr/legal', $urls);

        // Check localized blog slugs are present
        $this->assertContains('https://applyvipconseil.com/fa/blog/test-blog-post-fa', $urls);
        $this->assertContains('https://applyvipconseil.com/en/blog/test-blog-post-en', $urls);
        $this->assertContains('https://applyvipconseil.com/fr/blog/test-blog-post-fr', $urls);

        // Category index URLs for SEO
        $this->assertContains('https://applyvipconseil.com/fa/blog/categories', $urls);
        $this->assertContains('https://applyvipconseil.com/en/blog/categories', $urls);
        $this->assertContains('https://applyvipconseil.com/fr/blog/categories', $urls);

        // Calculator aligned with sitemap static pages
        $this->assertContains('https://applyvipconseil.com/fa/calculator', $urls);
        $this->assertContains('https://applyvipconseil.com/en/calculator', $urls);
        $this->assertContains('https://applyvipconseil.com/fr/calculator', $urls);
    }

    public function test_build_blog_post_urls_uses_translation_slugs_not_missing_blog_slug(): void
    {
        $author = User::factory()->create([
            'password' => 'password',
        ]);
        $category = BlogCategory::create();

        $blog = Blog::create([
            'author_id' => $author->id,
            'category_id' => $category->id,
            'published_at' => now(),
        ]);
        $blog->translations()->create([
            'locale' => 'fa',
            'slug' => 'post-fa',
            'title' => 'عنوان',
            'body' => 'محتوا',
        ]);
        $blog->translations()->create([
            'locale' => 'en',
            'slug' => 'post-en',
            'title' => 'Title',
            'body' => 'Content',
        ]);

        $urls = app(IndexNowService::class)->buildBlogPostUrls($blog);

        $this->assertContains('https://applyvipconseil.com/fa/blog/post-fa', $urls);
        $this->assertContains('https://applyvipconseil.com/en/blog/post-en', $urls);
        $this->assertContains('https://applyvipconseil.com/fa/blog', $urls);
        $this->assertContains('https://applyvipconseil.com/en/blog', $urls);
        $this->assertFalse(
            collect($urls)->contains(fn (string $url) => str_ends_with($url, '/blog/')),
            'Must not ping URLs built from a missing parent slug attribute'
        );
    }

    public function test_submit_batch_to_all_engines(): void
    {
        Http::fake([
            '*' => Http::response(['success' => true], 200),
        ]);

        $service = app(IndexNowService::class);
        $results = $service->submitBatchToAllEngines(['https://applyvipconseil.com/fa']);

        $this->assertNotEmpty($results);
        foreach ($results as $res) {
            $this->assertEquals(200, $res['status']);
            $this->assertNull($res['error']);
        }
    }
}
