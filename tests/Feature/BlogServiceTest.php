<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use App\Services\BlogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_blog_creates_translations_for_all_locales(): void
    {
        $admin = User::factory()->admin()->create(['password' => 'password']);
        $this->actingAs($admin);

        $category = BlogCategory::create();
        $category->translations()->create([
            'locale' => 'en',
            'name' => 'General',
            'slug' => 'general',
        ]);

        $blog = app(BlogService::class)->storeBlog([
            'category_id' => (string) $category->id,
            'is_pinned' => false,
            'published_at' => now()->toDateTimeString(),
            'translations' => [
                [
                    'locale' => 'fa',
                    'title' => 'عنوان فارسی',
                    'slug' => 'onvan-farsi',
                    'excerpt' => 'خلاصه',
                    'body' => '<p>متن</p>',
                ],
                [
                    'locale' => 'fr',
                    'title' => 'Titre français',
                    'slug' => 'titre-francais',
                    'excerpt' => 'Extrait',
                    'body' => '<p>Contenu</p>',
                ],
                [
                    'locale' => 'en',
                    'title' => 'English title',
                    'slug' => 'english-title',
                    'excerpt' => 'Excerpt',
                    'body' => '<p>Body</p>',
                ],
            ],
        ]);

        $this->assertInstanceOf(Blog::class, $blog);
        $this->assertCount(3, $blog->translations);
        $this->assertSame('onvan-farsi', $blog->getTranslation('fa')?->slug);
        $this->assertSame('titre-francais', $blog->getTranslation('fr')?->slug);
        $this->assertSame('english-title', $blog->getTranslation('en')?->slug);
    }

    public function test_store_blog_strips_unsafe_html_from_body(): void
    {
        $admin = User::factory()->admin()->create(['password' => 'password']);
        $this->actingAs($admin);

        $category = BlogCategory::create();

        $blog = app(BlogService::class)->storeBlog([
            'category_id' => (string) $category->id,
            'published_at' => now()->toDateTimeString(),
            'translations' => [
                [
                    'locale' => 'en',
                    'title' => 'Safe post',
                    'body' => '<p>Hello</p><script>alert(1)</script>',
                ],
            ],
        ]);

        $body = $blog->getTranslation('en')?->body;
        $this->assertStringContainsString('<p>Hello</p>', (string) $body);
        $this->assertStringNotContainsString('<script>', (string) $body);
    }
}
