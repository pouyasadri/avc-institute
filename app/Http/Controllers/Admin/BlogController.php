<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Locale;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogPostRequest;
use App\Models\Blog;
use App\Services\BlogCategoryService;
use App\Services\BlogService;
use App\Services\IndexNowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class BlogController extends Controller
{
    protected BlogService $blogService;

    protected BlogCategoryService $categoryService;

    protected IndexNowService $indexNow;

    public function __construct(
        BlogService $blogService,
        BlogCategoryService $categoryService,
        IndexNowService $indexNow,
    ) {
        $this->blogService = $blogService;
        $this->categoryService = $categoryService;
        $this->indexNow = $indexNow;
    }

    public function index(Request $request): View
    {
        $includeTrashed = true; // Admin sees all
        $perPage = 20;
        $blogs = $this->blogService->getPaginatedBlogs($perPage, null, $includeTrashed);
        $locale = app()->getLocale();

        return view('admin.blog.index', compact('blogs', 'locale', 'includeTrashed'));
    }

    public function create(): View
    {
        $categories = $this->categoryService->getAllCategories();
        $locales = config('localization.supported_locales', Locale::values());

        return view('admin.blog.create', compact('categories', 'locales'));
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $blog = $this->blogService->storeBlog($request->validated());

        // Notify all IndexNow engines about the new blog post
        $this->pingBlogUrls($blog);

        // Clear sitemap cache so the new post appears immediately
        Cache::forget('sitemap:blogs');

        return redirect()
            ->route('admin.blog.index')
            ->with('success', __('messages.blog_saved'));
    }

    public function edit(Blog $blog): View
    {
        $blog->load(['translations', 'category']);
        $categories = $this->categoryService->getAllCategories();
        $locales = config('localization.supported_locales', Locale::values());

        return view('admin.blog.edit', compact('blog', 'categories', 'locales'));
    }

    public function update(UpdateBlogPostRequest $request, Blog $blog): RedirectResponse
    {
        $this->blogService->updateBlog($blog, $request->validated());

        // Notify all IndexNow engines that the post has changed
        $blog->refresh();
        $this->pingBlogUrls($blog);

        // Clear sitemap cache so updates reflect immediately
        Cache::forget('sitemap:blogs');

        return redirect()
            ->route('admin.blog.index')
            ->with('success', __('messages.blog_updated'));
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        // Capture URLs before the post is soft-deleted
        $urls = $this->buildBlogUrls($blog);

        $this->blogService->deleteBlog($blog);

        // Notify engines so they can remove the page from their index
        if (! empty($urls)) {
            $this->indexNow->pingBatch($urls);
        }

        // Clear sitemap cache
        Cache::forget('sitemap:blogs');

        return redirect()
            ->route('admin.blog.index')
            ->with('success', __('messages.blog_deleted'));
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Build all locale-prefixed URLs for a blog post and ping IndexNow.
     */
    private function pingBlogUrls(Blog $blog): void
    {
        $urls = $this->buildBlogUrls($blog);

        if (! empty($urls)) {
            $this->indexNow->pingBatch($urls);
        }
    }

    /**
     * Return all locale-prefixed public URLs for a blog post.
     *
     * @return array<string>
     */
    private function buildBlogUrls(Blog $blog): array
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $locales = config('localization.supported_locales', Locale::values());
        $urls = [];

        foreach ($locales as $locale) {
            $urls[] = "{$baseUrl}/{$locale}/blog/{$blog->slug}";
        }

        // Also ping the blog index page so it gets re-crawled with the latest post listing
        foreach ($locales as $locale) {
            $urls[] = "{$baseUrl}/{$locale}/blog";
        }

        return $urls;
    }
}
