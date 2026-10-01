<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class BlogPublicWriteRoutesRemovedTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_blog_write_paths_are_not_routable_as_create(): void
    {
        // /{locale}/blog/create is no longer a write form — it is treated as a slug show/redirect
        $response = $this->get('/en/blog/create');

        // Either redirect to blog index (slug not found) or 404 — never a create form
        $this->assertTrue(in_array($response->status(), [302, 404], true));
        $this->assertFalse(
            str_contains($response->getContent() ?? '', 'name="translations'),
            'Public create form must not be served'
        );
    }

    public function test_public_category_index_is_available_for_seo(): void
    {
        $response = $this->get('/en/blog/categories');

        $response->assertOk();
        $response->assertSee('Blog Categories', false);
    }

    public function test_named_public_write_routes_do_not_exist(): void
    {
        $this->assertFalse(Route::has('blog.create'));
        $this->assertFalse(Route::has('blog.store'));
        $this->assertFalse(Route::has('blog.edit'));
        $this->assertFalse(Route::has('blog.update'));
        $this->assertFalse(Route::has('blog.delete'));
        $this->assertFalse(Route::has('blog.categories.create'));
        $this->assertFalse(Route::has('blog.categories.store'));
        $this->assertTrue(Route::has('blog.categories.index'));
        $this->assertTrue(Route::has('admin.blog.categories.index'));
    }
}
