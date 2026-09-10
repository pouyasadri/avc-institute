<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogPostTranslation;
use App\Models\User;
use App\Services\IndexNowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersianIndexationAndSlugTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test sitemap XML encodes Persian blog slugs as valid percent-encoded ASCII URIs.
     */
    public function test_sitemap_encodes_persian_blog_slugs(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);
        $blog = Blog::create([
            'author_id' => $user->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $persianSlug = 'خرید-ملک-در-فرانسه-۲۰۲۶';
        $encodedSlug = rawurlencode($persianSlug);

        BlogPostTranslation::create([
            'blog_post_id' => $blog->id,
            'locale' => 'fa',
            'title' => 'راهنمای خرید ملک',
            'slug' => $persianSlug,
            'body' => 'متن کامل مقاله درباره خرید ملک در فرانسه.',
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);

        $content = $response->getContent();

        // The sitemap should contain the percent-encoded URL in <loc>
        $this->assertStringContainsString("/fa/blog/{$encodedSlug}</loc>", $content);

        // It must NOT contain raw unencoded Persian characters in the blog loc
        $this->assertStringNotContainsString("/fa/blog/{$persianSlug}</loc>", $content);
    }

    /**
     * Test non-prefixed /blog/{post} redirects 301 to /fa/blog/{post} even with Persian unicode.
     */
    public function test_non_localized_blog_redirect_supports_persian_unicode(): void
    {
        $persianSlug = 'خرید-ملک-در-فرانسه-۲۰۲۶';
        $encodedSlug = rawurlencode($persianSlug);
        $response = $this->get("/blog/{$encodedSlug}");

        $response->assertStatus(301);
        $response->assertRedirect("/fa/blog/{$encodedSlug}");
    }

    /**
     * Test non-localized contact redirects 301.
     */
    public function test_contact_redirects_301(): void
    {
        $this->get('/contact')->assertStatus(301)->assertRedirect('/fa/contactUs');
        $this->get('/contact-us')->assertStatus(301)->assertRedirect('/fa/contactUs');
        $this->get('/contactUs')->assertStatus(301)->assertRedirect('/fa/contactUs');
    }

    /**
     * Test BlogController::show resolves Persian slug with Persian digits, English digits, and percent-encoded string.
     */
    public function test_blog_controller_resolves_persian_slug_variants(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);
        $blog = Blog::create([
            'author_id' => $user->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $persianSlug = 'خرید-ملک-در-فرانسه-۲۰۲۶';

        BlogPostTranslation::create([
            'blog_post_id' => $blog->id,
            'locale' => 'fa',
            'title' => 'راهنمای خرید ملک',
            'slug' => $persianSlug,
            'body' => 'متن کامل مقاله درباره خرید ملک در فرانسه.',
        ]);

        // 1. Percent-encoded slug request
        $encodedSlug = rawurlencode($persianSlug);
        $res = $this->get("/fa/blog/{$encodedSlug}");
        $res->assertStatus(200);
        $res->assertSee('راهنمای خرید ملک');

        // 2. English digits variant (2026 instead of ۲۰۲۶) -> 301 canonical redirect
        $latinDigitsSlug = rawurlencode('خرید-ملک-در-فرانسه-2026');
        $res3 = $this->get("/fa/blog/{$latinDigitsSlug}");
        $res3->assertStatus(301);
        $res3->assertRedirect(route('blog.show', ['locale' => 'fa', 'blog' => $persianSlug]));
    }

    /**
     * Test BlogController::show redirects with flash error if blog not found.
     */
    public function test_blog_controller_redirects_with_flash_error_if_not_found(): void
    {
        $response = $this->get('/fa/blog/non-existent-blog-slug');
        $response->assertStatus(302);
        $response->assertRedirect('/fa/blog');
        $response->assertSessionHas('error', __('messages.blog_not_found'));
    }

    /**
     * Test IndexNowService::buildAllSiteUrls produces percent-encoded URLs for Persian posts.
     */
    public function test_indexnow_service_encodes_persian_blog_urls(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);
        $blog = Blog::create([
            'author_id' => $user->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $persianSlug = 'خرید-ملک-در-فرانسه-۲۰۲۶';
        $encodedSlug = rawurlencode($persianSlug);

        BlogPostTranslation::create([
            'blog_post_id' => $blog->id,
            'locale' => 'fa',
            'title' => 'راهنمای خرید ملک',
            'slug' => $persianSlug,
            'body' => 'متن کامل مقاله درباره خرید ملک در فرانسه.',
        ]);

        $indexNowService = app(IndexNowService::class);
        $urls = $indexNowService->buildAllSiteUrls();

        $expectedUrl = "https://applyvipconseil.com/fa/blog/{$encodedSlug}";
        $this->assertContains($expectedUrl, $urls);

        // Ensure no raw non-ASCII URL is in the IndexNow list
        $rawUrl = "https://applyvipconseil.com/fa/blog/{$persianSlug}";
        $this->assertNotContains($rawUrl, $urls);
    }
}
