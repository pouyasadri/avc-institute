<?php

namespace Tests\Unit;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogPostTranslation;
use App\Models\User;
use App\Services\Blog\BlogSlugResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogSlugResolverTest extends TestCase
{
    use RefreshDatabase;

    public function test_resolves_persian_digit_variant_to_canonical_slug(): void
    {
        $author = User::factory()->create(['password' => 'password']);
        $category = BlogCategory::create();
        $blog = Blog::create([
            'author_id' => $author->id,
            'category_id' => $category->id,
            'published_at' => now(),
        ]);
        $blog->translations()->create([
            'locale' => 'fa',
            'slug' => 'ویزا-2024',
            'title' => 'عنوان',
            'body' => 'متن',
        ]);

        // Arabic-Indic digits variant of 2024
        $raw = 'ویزا-٢٠٢٤';
        $translation = app(BlogSlugResolver::class)->resolve('fa', $raw);

        $this->assertInstanceOf(BlogPostTranslation::class, $translation);
        $this->assertSame('ویزا-2024', $translation->slug);
        $this->assertTrue(app(BlogSlugResolver::class)->needsCanonicalRedirect($raw, $translation->slug));
    }
}
