<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use App\Services\PropertyService;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected PropertyService $propertyService
    ) {
        //
    }

    /**
     * Display the main page with latest blogs and properties.
     */
    public function index(): View
    {
        // Fetch the latest 3 published blog posts at SQL level (avoid loading the full collection)
        $blogs = $this->blogService->getRecentPublishedBlogs(app()->getLocale(), 3);

        // PROPERTIES FEATURE DISABLED - COMING SOON
        // Original: $properties = $this->propertyService->getPublishedProperties()->take(4);
        // To re-enable: Uncomment the line above and remove the line below
        // See PROPERTIES_DISABLED.md for full restoration guide
        $properties = collect(); // Empty collection prevents errors in view

        return view('pages.home', compact('blogs', 'properties'));
    }
}
