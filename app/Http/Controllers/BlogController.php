<?php

namespace App\Http\Controllers;

use App\Services\Blog\BlogSlugResolver;
use App\Services\BlogService;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected BlogSlugResolver $slugResolver,
    ) {}

    public function index(Request $request): View
    {
        $includeTrashed = $request->query('trashed') === 'true' && auth()->check();
        $perPage = 9;
        $locale = app()->getLocale();
        $blogs = $this->blogService->getPaginatedBlogs($perPage, $locale, $includeTrashed);

        app(SeoService::class)->forBlogIndex($locale);

        return view('blog.index', compact('blogs', 'locale', 'includeTrashed'));
    }

    public function show(string $locale, string $blog): View|RedirectResponse
    {
        $locale = app()->getLocale();

        $translation = $this->slugResolver->resolve($locale, $blog);

        if (! $translation) {
            return redirect()->route('blog.index', ['locale' => $locale])
                ->with('error', __('messages.blog_not_found'));
        }

        if ($this->slugResolver->needsCanonicalRedirect($blog, $translation->slug)) {
            return redirect()->route('blog.show', ['locale' => $locale, 'blog' => $translation->slug], 301);
        }

        $blog = $translation->post;
        $blog->load(['translations', 'category', 'category.translations', 'author']);

        $nextBlog = $this->blogService->getNextBlog($blog);
        $prevBlog = $this->blogService->getPreviousBlog($blog);
        $recentBlogs = $this->blogService->getRecentPublishedBlogs($locale, 3, $blog->id);

        return view('blog.show', compact('blog', 'translation', 'locale', 'nextBlog', 'prevBlog', 'recentBlogs'));
    }
}
