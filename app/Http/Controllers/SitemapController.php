<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap with hreflang annotations
     */
    public function index(): Response
    {
        $locales = array_keys(config('seo.locales', ['en', 'fr', 'fa']));
        $baseUrl = rtrim(config('app.url', 'https://applyvipconseil.com'), '/');
        $defaultLocale = config('seo.default_locale', 'fa');

        // Cache blog queries for 1 hour — sitemap is crawled frequently by Googlebot
        $blogs = Cache::remember('sitemap:blogs', 3600, function () {
            return Blog::published()->with('translations')->get();
        });

        // Static content last-modified dates — dynamically determined by checking
        // the view template and corresponding language files.
        $getLastmod = function (string $viewPath, array $translationPaths = []): string {
            $timestamps = [];

            // 1. Check Blade view file
            $viewFullPath = resource_path('views/'.str_replace('.', '/', $viewPath).'.blade.php');
            if (file_exists($viewFullPath)) {
                $timestamps[] = filemtime($viewFullPath);
            }

            // 2. Check translation files
            foreach ($translationPaths as $langPath) {
                $langFullPath = resource_path('lang/'.$langPath);
                if (file_exists($langFullPath)) {
                    $timestamps[] = filemtime($langFullPath);
                }
            }

            if (! empty($timestamps)) {
                return Carbon::createFromTimestamp(max($timestamps))->toAtomString();
            }

            // Fallback baseline date
            return '2026-06-01T00:00:00+00:00';
        };

        // Static content source of truth
        $cities = config('site_structure.cities', []);
        $universities = config('site_structure.universities', []);
        $services = config('site_structure.service_slugs', []);

        // Build URLs with hreflang alternates
        $urls = [];

        // Helper function to generate hreflang alternates
        $generateAlternates = function ($path) use ($locales, $baseUrl) {
            $alternates = [];
            foreach ($locales as $locale) {
                $alternates[] = [
                    'hreflang' => $locale,
                    'href' => "{$baseUrl}/{$locale}{$path}",
                ];
            }

            // x-default points to English — the universal fallback for unrecognized locales
            // (consistent with hreflang.blade.php — do NOT point to /fa/ here)
            $alternates[] = [
                'hreflang' => 'x-default',
                'href' => "{$baseUrl}/en{$path}",
            ];

            return $alternates;
        };

        // Homepage for each locale
        foreach ($locales as $locale) {
            // Only the default locale homepage gets priority 1.0
            $homePriority = ($locale === $defaultLocale)
                ? config('seo.sitemap.priorities.homepage', 1.0)
                : 0.9;

            $urls[] = [
                'loc' => "{$baseUrl}/{$locale}",
                'lastmod' => $getLastmod('pages.home', ["{$locale}/index.php"]),
                'changefreq' => config('seo.sitemap.changefreq.homepage', 'daily'),
                'priority' => $homePriority,
                'alternates' => $generateAlternates(''),
            ];
        }

        // Blog index and posts for each locale
        foreach ($locales as $locale) {
            // Blog index
            $urls[] = [
                'loc' => "{$baseUrl}/{$locale}/blog",
                'lastmod' => $blogs->max('updated_at')?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => 0.9,
                'alternates' => $generateAlternates('/blog'),
            ];

            // Individual blog posts
            foreach ($blogs as $blog) {
                $entry = [
                    'loc' => "{$baseUrl}/{$locale}/blog/{$blog->id}",
                    'lastmod' => $blog->updated_at->toAtomString(),
                    'changefreq' => config('seo.sitemap.changefreq.blog_post', 'weekly'),
                    'priority' => config('seo.sitemap.priorities.blog_post', 0.8),
                    'alternates' => $generateAlternates("/blog/{$blog->id}"),
                ];

                // Add image metadata for Google Images indexing
                if ($blog->main_image) {
                    $translation = $blog->getTranslation($locale);
                    $entry['image'] = [
                        'loc' => rtrim(config('app.url'), '/').'/storage/'.$blog->main_image,
                        'title' => $translation?->title ?? $blog->id,
                    ];
                }

                $urls[] = $entry;
            }
        }

        /*
        ================================================================================
        PROPERTIES FEATURE DISABLED - COMING SOON
        ================================================================================
        Property URLs removed from sitemap to prevent indexing during development.
        To re-enable: Uncomment this section and see PROPERTIES_DISABLED.md
        ================================================================================

        // Property index and listings for each locale
        foreach ($locales as $locale) {
            // Properties index
            $urls[] = [
                'loc' => "{$baseUrl}/{$locale}/properties",
                'lastmod' => $properties->max('updated_at')?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => 0.9,
                'alternates' => $generateAlternates('/properties'),
            ];

            // Individual properties
            foreach ($properties as $property) {
                $urls[] = [
                    'loc' => "{$baseUrl}/{$locale}/properties/{$property->id}",
                    'lastmod' => $property->updated_at->toAtomString(),
                    'changefreq' => config('seo.sitemap.changefreq.property', 'weekly'),
                    'priority' => config('seo.sitemap.priorities.property', 0.8),
                    'alternates' => $generateAlternates("/properties/{$property->id}"),
                ];
            }
        }

        ================================================================================
        END PROPERTIES FEATURE DISABLED
        ================================================================================
        */

        // Cities for each locale
        foreach ($locales as $locale) {
            // Cities index
            $urls[] = [
                'loc' => "{$baseUrl}/{$locale}/cities",
                'lastmod' => $getLastmod('pages.cities.index', ["{$locale}/cities.php"]),
                'changefreq' => 'monthly',
                'priority' => 0.8,
                'alternates' => $generateAlternates('/cities'),
            ];

            // Individual cities
            foreach ($cities as $city) {
                $urls[] = [
                    'loc' => "{$baseUrl}/{$locale}/cities/{$city}",
                    'lastmod' => $getLastmod("city.{$city}", ["{$locale}/city/{$city}.php"]),
                    'changefreq' => config('seo.sitemap.changefreq.city', 'monthly'),
                    'priority' => config('seo.sitemap.priorities.city', 0.8),
                    'alternates' => $generateAlternates("/cities/{$city}"),
                ];
            }
        }

        // Universities for each locale
        foreach ($locales as $locale) {
            // Universities index
            $urls[] = [
                'loc' => "{$baseUrl}/{$locale}/universities",
                'lastmod' => $getLastmod('pages.universities.index', ["{$locale}/universities.php"]),
                'changefreq' => 'monthly',
                'priority' => 0.8,
                'alternates' => $generateAlternates('/universities'),
            ];

            // Individual universities
            foreach ($universities as $university) {
                $urls[] = [
                    'loc' => "{$baseUrl}/{$locale}/universities/{$university}",
                    'lastmod' => $getLastmod("university.{$university}", ["{$locale}/university/{$university}.php"]),
                    'changefreq' => config('seo.sitemap.changefreq.university', 'monthly'),
                    'priority' => config('seo.sitemap.priorities.university', 0.75),
                    'alternates' => $generateAlternates("/universities/{$university}"),
                ];
            }
        }

        // Services for each locale
        foreach ($locales as $locale) {
            // Services index
            $urls[] = [
                'loc' => "{$baseUrl}/{$locale}/services",
                'lastmod' => $getLastmod('pages.services.index', ["{$locale}/services.php"]),
                'changefreq' => 'monthly',
                'priority' => 0.9,
                'alternates' => $generateAlternates('/services'),
            ];

            // Individual services
            foreach ($services as $service) {
                $urls[] = [
                    'loc' => "{$baseUrl}/{$locale}/services/{$service}",
                    'lastmod' => $getLastmod('pages.services.show', ["{$locale}/services.php"]),
                    'changefreq' => 'monthly',
                    'priority' => 0.85,
                    'alternates' => $generateAlternates("/services/{$service}"),
                ];
            }
        }

        // Other static pages for each locale
        $staticPages = ['consult', 'contactUs', 'legal'];
        foreach ($locales as $locale) {
            foreach ($staticPages as $page) {
                $viewName = $page === 'contactUs' ? 'pages.contact' : "pages.{$page}";
                $urls[] = [
                    'loc' => "{$baseUrl}/{$locale}/{$page}",
                    'lastmod' => $getLastmod($viewName, ["{$locale}/".($page === 'contactUs' ? 'contact' : $page).'.php']),
                    'changefreq' => config('seo.sitemap.changefreq.static_page', 'monthly'),
                    'priority' => config('seo.sitemap.priorities.static_page', 0.6),
                    'alternates' => $generateAlternates("/{$page}"),
                ];
            }
        }

        // Determine cache freshness from the newest blog post
        $latestBlogDate = $blogs->max('updated_at');
        $lastModified = $latestBlogDate
            ? $latestBlogDate->format('D, d M Y H:i:s').' GMT'
            : gmdate('D, d M Y H:i:s').' GMT';

        $etag = md5($lastModified.count($urls));

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600, s-maxage=3600')
            ->header('Last-Modified', $lastModified)
            ->header('ETag', $etag);
    }
}
