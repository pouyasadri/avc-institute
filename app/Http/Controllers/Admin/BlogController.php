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
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected BlogCategoryService $categoryService,
        protected IndexNowService $indexNow,
    ) {}

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

        $this->indexNow->notifyBlogChanged($blog);

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

        $blog->refresh();
        $this->indexNow->notifyBlogChanged($blog);

        return redirect()
            ->route('admin.blog.index')
            ->with('success', __('messages.blog_updated'));
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        // Capture URLs before the post is soft-deleted
        $urls = $this->indexNow->buildBlogPostUrls($blog);

        $this->blogService->deleteBlog($blog);

        $this->indexNow->notifyBlogRemoved($urls);

        return redirect()
            ->route('admin.blog.index')
            ->with('success', __('messages.blog_deleted'));
    }
}
