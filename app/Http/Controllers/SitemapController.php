<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Property;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

        // Static content last-modified date — use a fixed date; avoid now() which
        // makes every crawl think content changed and wastes crawl budget.
        $staticLastmod = '2025-01-01T00:00:00+00:00';

        // PROPERTIES FEATURE DISABLED - COMING SOON
        // Original: $properties = Property::published()->with('translations')->get();
        // To re-enable: Uncomment the line above and uncomment the URL generation below
        // See PROPERTIES_DISABLED.md for full restoration guide
        $properties = collect(); // Empty collection prevents errors

        // Static pages (cities and universities)
        $cities = ['paris', 'strasbourg', 'nice', 'toulouse', 'lyon'];
        $universities = [
            'paris-saclay-university',
            'sorbonne-paris-nord',
            'paris-cite',
            'paris-4-sorbonne',
            'paris-3',
            'paris-2',
            'lyon-3',
            'lyon-2',
            'lyon-1',
            'pantheon-sorbonne',
            'cote-d-azure',
            'toulouse',
            'strasbourg',
            'universite-psl',
            'ip-paris',
            'universite-grenoble-alpes',
            'aix-marseille-university',
            'universite-de-bordeaux',
            'universite-de-lille',
            'sciences-po',
        ];
        $services = [
            'residence-permit',
            'resume-lettre-motivation',
            'arrival-support',
            'certified-translation',
            'educational-counseling',
            'housing-assistance',
            'university-application',
            'administrative-advocacy',
            'legal-support',
        ];

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

            // Add x-default pointing to the configured default locale
            $defaultLocale = config('seo.default_locale', 'fa');
            $alternates[] = [
                'hreflang' => 'x-default',
                'href' => "{$baseUrl}/{$defaultLocale}{$path}",
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
                'loc'        => "{$baseUrl}/{$locale}",
                'lastmod'    => $staticLastmod,
                'changefreq' => config('seo.sitemap.changefreq.homepage', 'daily'),
                'priority'   => $homePriority,
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
                $urls[] = [
                    'loc' => "{$baseUrl}/{$locale}/blog/{$blog->id}",
                    'lastmod' => $blog->updated_at->toAtomString(),
                    'changefreq' => config('seo.sitemap.changefreq.blog_post', 'weekly'),
                    'priority' => config('seo.sitemap.priorities.blog_post', 0.8),
                    'alternates' => $generateAlternates("/blog/{$blog->id}"),
                ];
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
                'loc'        => "{$baseUrl}/{$locale}/cities",
                'lastmod'    => $staticLastmod,
                'changefreq' => 'monthly',
                'priority'   => 0.8,
                'alternates' => $generateAlternates('/cities'),
            ];

            // Individual cities
            foreach ($cities as $city) {
                $urls[] = [
                    'loc'        => "{$baseUrl}/{$locale}/cities/{$city}",
                    'lastmod'    => $staticLastmod,
                    'changefreq' => config('seo.sitemap.changefreq.city', 'monthly'),
                    'priority'   => config('seo.sitemap.priorities.city', 0.8),
                    'alternates' => $generateAlternates("/cities/{$city}"),
                ];
            }
        }

        // Universities for each locale
        foreach ($locales as $locale) {
            // Universities index
            $urls[] = [
                'loc'        => "{$baseUrl}/{$locale}/universities",
                'lastmod'    => $staticLastmod,
                'changefreq' => 'monthly',
                'priority'   => 0.8,
                'alternates' => $generateAlternates('/universities'),
            ];

            // Individual universities
            foreach ($universities as $university) {
                $urls[] = [
                    'loc'        => "{$baseUrl}/{$locale}/universities/{$university}",
                    'lastmod'    => $staticLastmod,
                    'changefreq' => config('seo.sitemap.changefreq.university', 'monthly'),
                    'priority'   => config('seo.sitemap.priorities.university', 0.75),
                    'alternates' => $generateAlternates("/universities/{$university}"),
                ];
            }
        }

        // Services for each locale
        foreach ($locales as $locale) {
            // Services index
            $urls[] = [
                'loc'        => "{$baseUrl}/{$locale}/services",
                'lastmod'    => $staticLastmod,
                'changefreq' => 'monthly',
                'priority'   => 0.9,
                'alternates' => $generateAlternates('/services'),
            ];

            // Individual services
            foreach ($services as $service) {
                $urls[] = [
                    'loc'        => "{$baseUrl}/{$locale}/services/{$service}",
                    'lastmod'    => $staticLastmod,
                    'changefreq' => 'monthly',
                    'priority'   => 0.85,
                    'alternates' => $generateAlternates("/services/{$service}"),
                ];
            }
        }

        // Other static pages for each locale
        $staticPages = ['consult', 'contactUs'];
        foreach ($locales as $locale) {
            foreach ($staticPages as $page) {
                $urls[] = [
                    'loc'        => "{$baseUrl}/{$locale}/{$page}",
                    'lastmod'    => $staticLastmod,
                    'changefreq' => config('seo.sitemap.changefreq.static_page', 'monthly'),
                    'priority'   => config('seo.sitemap.priorities.static_page', 0.6),
                    'alternates' => $generateAlternates("/{$page}"),
                ];
            }
        }

        // Determine cache freshness from the newest blog post
        $latestBlogDate = $blogs->max('updated_at');
        $lastModified   = $latestBlogDate
            ? $latestBlogDate->format('D, d M Y H:i:s') . ' GMT'
            : gmdate('D, d M Y H:i:s') . ' GMT';

        $etag = md5($lastModified . count($urls));

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600, s-maxage=3600')
            ->header('Last-Modified', $lastModified)
            ->header('ETag', $etag);
    }
}
