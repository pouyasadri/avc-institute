<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogPostRequest;
use App\Models\Blog;
use App\Models\BlogPostTranslation;
use App\Services\BlogCategoryService;
use App\Services\BlogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    protected BlogService $blogService;

    protected BlogCategoryService $categoryService;

    public function __construct(BlogService $blogService, BlogCategoryService $categoryService)
    {
        $this->blogService = $blogService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request): View
    {
        $includeTrashed = $request->query('trashed') === 'true' && auth()->check();
        $perPage = 9;
        $blogs = $this->blogService->getPaginatedBlogs($perPage, app()->getLocale(), $includeTrashed);
        $locale = app()->getLocale();

        return view('blog.index', compact('blogs', 'locale', 'includeTrashed'));
    }

    public function show(string $locale, string $blog): View|RedirectResponse
    {
        $locale = app()->getLocale();
        $translation = BlogPostTranslation::where('slug', $blog)
            ->where('locale', $locale)
            ->first();

        if (! $translation) {
            return redirect()->route('blog.index', ['locale' => $locale]);
        }

        $blog = $translation->post;
        $blog->load(['translations', 'category', 'category.translations', 'author']);

        $nextBlog = $this->blogService->getNextBlog($blog);
        $prevBlog = $this->blogService->getPreviousBlog($blog);

        // Fetch 3 most recent published blogs (localized) for the sidebar via SQL LIMIT
        $recentBlogs = $this->blogService->getRecentPublishedBlogs($locale, 3, $blog->id);

        return view('blog.show', compact('blog', 'translation', 'locale', 'nextBlog', 'prevBlog', 'recentBlogs'));
    }

    public function create(): View
    {
        $this->authorize('create', Blog::class);

        $categories = $this->categoryService->getAllCategories();
        $locales = config('localization.supported_locales', ['en', 'fr', 'fa']);

        return view('blog.create', compact('categories', 'locales'));
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $this->authorize('create', Blog::class);

        $blog = $this->blogService->storeBlog($request->validated());

        return redirect()
            ->route('blog.show', ['locale' => app()->getLocale(), 'blog' => $blog->getTranslation(app()->getLocale())->slug])
            ->with('success', __('messages.blog_saved'));
    }

    public function edit(string $locale, Blog $blog): View
    {
        $this->authorize('update', $blog);

        $blog->load(['translations', 'category']);
        $categories = $this->categoryService->getAllCategories();
        $locales = config('localization.supported_locales', ['en', 'fr', 'fa']);

        return view('blog.edit', compact('blog', 'categories', 'locales'));
    }

    public function update(string $locale, UpdateBlogPostRequest $request, Blog $blog): RedirectResponse
    {
        $this->authorize('update', $blog);

        $this->blogService->updateBlog($blog, $request->validated());

        return redirect()
            ->route('blog.show', ['locale' => app()->getLocale(), 'blog' => $blog->getTranslation(app()->getLocale())->slug])
            ->with('success', __('messages.blog_updated'));
    }

    public function destroy(string $locale, Blog $blog): RedirectResponse
    {
        $this->authorize('delete', $blog);

        $this->blogService->deleteBlog($blog);

        return redirect()
            ->route('index', ['locale' => app()->getLocale()])
            ->with('success', __('messages.blog_deleted'));
    }

    public function restore(string $locale, string $id): RedirectResponse
    {
        $this->authorize('restore', Blog::class);

        $blog = $this->blogService->restoreBlog($id);

        if ($blog) {
            return redirect()
                ->route('blog.show', ['locale' => app()->getLocale(), 'blog' => $blog->getTranslation(app()->getLocale())->slug])
                ->with('success', __('messages.blog_restored'));
        }

        return redirect()
            ->route('index', ['locale' => app()->getLocale()])
            ->with('error', __('messages.blog_not_found'));
    }
}
