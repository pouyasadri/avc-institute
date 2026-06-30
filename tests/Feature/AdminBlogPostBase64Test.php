<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBlogPostBase64Test extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_blog_with_base64_encoded_body(): void
    {
        // 1. Create an admin user and authenticate
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password',
        ]);

        $this->actingAs($admin);

        // 2. Create a category
        $category = BlogCategory::create();
        $category->translations()->create([
            'locale' => 'en',
            'name' => 'Technology',
            'slug' => 'technology',
        ]);

        // 3. Prepare blog post data with base64-encoded HTML body
        $rawHtmlBody = '<p>Hello <strong>World</strong>! This is a test HTML body with <script>alert("unsafe");</script> tags.</p>';
        $base64Body = base64_encode($rawHtmlBody);

        $payload = [
            'category_id' => (string) $category->id,
            'is_pinned' => 0,
            'published_at' => now()->format('Y-m-d\TH:i'),
            'translations' => [
                'en' => [
                    'locale' => 'en',
                    'title' => 'My Base64 Blog Post',
                    'excerpt' => 'This is a short excerpt.',
                    'body' => $base64Body,
                ],
            ],
        ];

        // 4. Send POST request to store the blog post
        $response = $this->post(route('admin.blog.store'), $payload);

        // 5. Assert successful redirect
        $response->assertRedirect(route('admin.blog.index'));

        // 6. Assert blog post is saved and body is properly decoded (and cleaned by the service/clean helper)
        $blog = Blog::first();
        $this->assertNotNull($blog);

        $translation = $blog->translations()->where('locale', 'en')->first();
        $this->assertNotNull($translation);

        // Note: clean() is called in the BlogService which removes unsafe <script> tag but keeps the remaining HTML structure.
        // Let's assert that the decoded strong tags are preserved.
        $this->assertStringContainsString('Hello <strong>World</strong>!', $translation->body);
        $this->assertStringNotContainsString('alert("unsafe")', $translation->body);
    }

    public function test_admin_can_update_blog_with_base64_encoded_body(): void
    {
        // 1. Create an admin user and authenticate
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password',
        ]);

        $this->actingAs($admin);

        // 2. Create a category
        $category = BlogCategory::create();
        $category->translations()->create([
            'locale' => 'en',
            'name' => 'Technology',
            'slug' => 'technology',
        ]);

        // 3. Create an existing blog
        $blog = Blog::create([
            'category_id' => (string) $category->id,
            'author_id' => $admin->id,
            'published_at' => now(),
        ]);
        $blog->translations()->create([
            'locale' => 'en',
            'title' => 'Old Title',
            'slug' => 'old-title',
            'body' => 'Old Body Content',
        ]);

        // 4. Prepare update data with base64-encoded body
        $newRawHtmlBody = '<div>Updated content with <em>formatting</em>.</div>';
        $newBase64Body = base64_encode($newRawHtmlBody);

        $payload = [
            'category_id' => (string) $category->id,
            'is_pinned' => 0,
            'published_at' => now()->format('Y-m-d\TH:i'),
            'translations' => [
                'en' => [
                    'locale' => 'en',
                    'title' => 'Updated Title',
                    'body' => $newBase64Body,
                ],
            ],
        ];

        // 5. Send PUT request to update the blog post
        $response = $this->put(route('admin.blog.update', $blog->id), $payload);

        // 6. Assert successful redirect
        $response->assertRedirect(route('admin.blog.index'));

        // 7. Verify database contains decoded content
        $blog->refresh();
        $translation = $blog->translations()->where('locale', 'en')->first();
        $this->assertNotNull($translation);
        $this->assertEquals('Updated Title', $translation->title);
        $this->assertStringContainsString('Updated content with <em>formatting</em>.', $translation->body);
    }
}
