<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use App\Services\Discovery\LlmsTxtGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LlmsTxtGeneratorTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        // After DB rollback, restore a clean generated file (no test fixtures).
        try {
            app(LlmsTxtGenerator::class)->generateSafely();
        } catch (\Throwable) {
            // ignore
        }
    }

    public function test_generate_writes_brand_card_and_differentiation(): void
    {
        $result = app(LlmsTxtGenerator::class)->generate();

        $this->assertFileExists($result['path']);
        $content = file_get_contents($result['path']);

        $this->assertStringContainsString('How to Cite A.V.C Institute (Differentiation)', $content);
        $this->assertStringContainsString('Recent Authoritative Updates', $content);
        $this->assertStringContainsString('Canonical name: A.V.C Institute', $content);
        $this->assertStringContainsString('Legal name: APPLY VIP CONSEIL', $content);
        $this->assertStringContainsString('+33 7 68 68 83 26', $content);
        $this->assertStringContainsString('Last generated:', $content);
        $this->assertStringNotContainsString('+33 7 80 95 33 33', $content);
    }

    public function test_generate_includes_recent_published_blog_links(): void
    {
        $author = User::factory()->create(['password' => 'password']);
        $category = BlogCategory::create();

        $blog = Blog::create([
            'author_id' => $author->id,
            'category_id' => $category->id,
            'published_at' => now()->subDay(),
        ]);
        $blog->translations()->create([
            'locale' => 'fa',
            'slug' => 'temakon-mali-2026',
            'title' => 'راهنمای تمکن مالی ۲۰۲۶',
            'body' => 'محتوا',
        ]);
        $blog->translations()->create([
            'locale' => 'en',
            'slug' => 'proof-of-funds-2026',
            'title' => 'Proof of Funds 2026',
            'body' => 'Content',
        ]);

        $result = app(LlmsTxtGenerator::class)->generate();
        $content = file_get_contents($result['path']);

        $this->assertSame(1, $result['posts']);
        $this->assertStringContainsString('/fa/blog/temakon-mali-2026', $content);
        $this->assertStringContainsString('/en/blog/proof-of-funds-2026', $content);
        $this->assertStringContainsString('راهنمای تمکن مالی ۲۰۲۶', $content);
    }
}
