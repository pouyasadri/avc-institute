<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_store_blog_via_admin(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'password' => 'password',
        ]);

        $category = BlogCategory::create();
        $category->translations()->create([
            'locale' => 'en',
            'name' => 'General',
            'slug' => 'general',
        ]);

        $response = $this->actingAs($user)->post(route('admin.blog.store'), [
            'category_id' => (string) $category->id,
            'published_at' => now()->format('Y-m-d\TH:i'),
            'translations' => [
                'en' => [
                    'locale' => 'en',
                    'title' => 'Unauthorized Post',
                    'body' => 'Should not be created',
                ],
            ],
        ]);

        // AdminMiddleware returns 403 for authenticated non-admins
        $response->assertForbidden();
        $this->assertDatabaseCount('blog_posts', 0);
    }

    public function test_guest_is_redirected_from_admin_blog_store(): void
    {
        $response = $this->post(route('admin.blog.store'), []);

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_open_blog_create_form(): void
    {
        $admin = User::factory()->admin()->create([
            'password' => 'password',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.blog.create'));

        $response->assertOk();
    }

    public function test_non_admin_cannot_access_admin_blog_categories(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'password' => 'password',
        ]);

        $response = $this->actingAs($user)->get(route('admin.blog.categories.index'));

        $response->assertForbidden();
    }

    public function test_blog_policy_denies_non_admin_mutations(): void
    {
        $user = new User;
        $user->is_admin = false;

        $admin = new User;
        $admin->is_admin = true;

        $this->assertFalse($user->can('create', Blog::class));
        $this->assertTrue($admin->can('create', Blog::class));
    }
}
