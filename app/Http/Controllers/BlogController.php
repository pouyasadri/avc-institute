<?php

namespace App\Http\Controllers;

use App\Models\BlogPostTranslation;
use App\Services\BlogService;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(protected BlogService $blogService) {}

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

        // Build candidate slug variations to handle percent-decoding, Persian digits, and Arabic character variants
        $decoded = urldecode($blog);
        $rawDecoded = rawurldecode($blog);

        $faDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $enDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $toEnDigits = fn (string $s): string => str_replace(array_merge($faDigits, $arDigits), array_merge($enDigits, $enDigits), $s);
        $toFaDigits = fn (string $s): string => str_replace($enDigits, $faDigits, $s);
        $normalizeChars = fn (string $s): string => str_replace(['ي', 'ك', 'ة', 'ى'], ['ی', 'ک', 'ه', 'ی'], $s);

        $candidates = array_unique(array_filter([
            $blog,
            $decoded,
            $rawDecoded,
            $toEnDigits($decoded),
            $toFaDigits($decoded),
            $normalizeChars($decoded),
            $normalizeChars($toEnDigits($decoded)),
            $normalizeChars($toFaDigits($decoded)),
        ]));

        $translation = BlogPostTranslation::whereIn('slug', $candidates)
            ->where('locale', $locale)
            ->first();

        if (! $translation) {
            return redirect()->route('blog.index', ['locale' => $locale])
                ->with('error', __('messages.blog_not_found'));
        }

        // If the requested slug was a non-canonical variation (e.g. Arabic digits or alternative character form),
        // redirect 301 to the canonical slug to prevent keyword cannibalization and consolidate indexing signals
        $canonicalSlug = $translation->slug;
        if ($blog !== $canonicalSlug && $blog !== rawurlencode($canonicalSlug)) {
            return redirect()->route('blog.show', ['locale' => $locale, 'blog' => $canonicalSlug], 301);
        }

        $blog = $translation->post;
        $blog->load(['translations', 'category', 'category.translations', 'author']);

        $nextBlog = $this->blogService->getNextBlog($blog);
        $prevBlog = $this->blogService->getPreviousBlog($blog);

        // Fetch 3 most recent published blogs (localized) for the sidebar via SQL LIMIT
        $recentBlogs = $this->blogService->getRecentPublishedBlogs($locale, 3, $blog->id);

        return view('blog.show', compact('blog', 'translation', 'locale', 'nextBlog', 'prevBlog', 'recentBlogs'));
    }
}
