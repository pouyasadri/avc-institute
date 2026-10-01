<?php

namespace App\Http\Controllers;

use App\Services\BlogCategoryService;
use App\Services\SeoService;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function __construct(protected BlogCategoryService $service) {}

    /**
     * Public read-only category listing (SEO).
     * Mutations live under /admin/blog-categories.
     */
    public function index(): View
    {
        $locale = app()->getLocale();
        $categories = $this->service->getAllCategories();

        app(SeoService::class)->forBlogCategories($locale);

        return view('blog.categories.index', compact('categories', 'locale'));
    }
}
