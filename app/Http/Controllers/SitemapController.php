<?php

namespace App\Http\Controllers;

use App\Services\Seo\SiteUrlBuilder;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SitemapController extends Controller
{
    public function __construct(protected SiteUrlBuilder $siteUrls) {}

    /**
     * Generate dynamic XML sitemap with hreflang annotations.
     */
    public function index(): Response
    {
        $locales = $this->siteUrls->locales();
        $baseUrl = $this->siteUrls->baseUrl();
        $defaultLocale = config('seo.default_locale', 'fa');
        $blogs = $this->siteUrls->publishedBlogs();
        $cities = $this->siteUrls->cities();
        $universities = $this->siteUrls->universities();
        $services = $this->siteUrls->services();

        $getLastmod = function (string $viewPath, array $translationPaths = []): string {
            $timestamps = [];

            $viewFullPath = resource_path('views/'.str_replace('.', '/', $viewPath).'.blade.php');
            if (file_exists($viewFullPath)) {
                $timestamps[] = filemtime($viewFullPath);
            }

            foreach ($translationPaths as $langPath) {
                $langFullPath = resource_path('lang/'.$langPath);
                if (file_exists($langFullPath)) {
                    $timestamps[] = filemtime($langFullPath);
                }
            }

            if (! empty($timestamps)) {
                return Carbon::createFromTimestamp(max($timestamps))->toAtomString();
            }

            return '2026-06-01T00:00:00+00:00';
        };

        $generateAlternates = function (string $path) use ($locales, $baseUrl): array {
            $alternates = [];
            foreach ($locales as $locale) {
                $alternates[] = [
                    'hreflang' => $locale,
                    'href' => "{$baseUrl}/{$locale}{$path}",
                ];
            }

            $alternates[] = [
                'hreflang' => 'x-default',
                'href' => "{$baseUrl}/en{$path}",
            ];

            return $alternates;
        };

        $urls = [];

        foreach ($locales as $locale) {
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

        foreach ($locales as $locale) {
            $urls[] = [
                'loc' => "{$baseUrl}/{$locale}/blog",
                'lastmod' => $blogs->max('updated_at')?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => 0.9,
                'alternates' => $generateAlternates('/blog'),
            ];

            $urls[] = [
                'loc' => "{$baseUrl}/{$locale}/blog/categories",
                'lastmod' => $blogs->max('updated_at')?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => 0.7,
                'alternates' => $generateAlternates('/blog/categories'),
            ];

            foreach ($blogs as $blog) {
                $translation = $blog->getTranslation($locale);
                if (! $translation || ! $translation->slug) {
                    continue;
                }

                $encodedSlug = $this->siteUrls->encodeSlug($translation->slug);

                $blogAlternates = [];
                foreach ($locales as $altLocale) {
                    $altTrans = $blog->getTranslation($altLocale);
                    if ($altTrans && $altTrans->slug) {
                        $encodedAltSlug = $this->siteUrls->encodeSlug($altTrans->slug);
                        $blogAlternates[] = [
                            'hreflang' => $altLocale,
                            'href' => "{$baseUrl}/{$altLocale}/blog/{$encodedAltSlug}",
                        ];

                        if ($altLocale === 'en') {
                            $blogAlternates[] = [
                                'hreflang' => 'x-default',
                                'href' => "{$baseUrl}/en/blog/{$encodedAltSlug}",
                            ];
                        }
                    }
                }

                $entry = [
                    'loc' => "{$baseUrl}/{$locale}/blog/{$encodedSlug}",
                    'lastmod' => $blog->updated_at->toAtomString(),
                    'changefreq' => config('seo.sitemap.changefreq.blog_post', 'weekly'),
                    'priority' => config('seo.sitemap.priorities.blog_post', 0.8),
                    'alternates' => $blogAlternates,
                ];

                if ($blog->main_image) {
                    $entry['image'] = [
                        'loc' => Storage::url($blog->main_image),
                        'title' => $translation->title,
                    ];
                }

                $urls[] = $entry;
            }
        }

        foreach ($locales as $locale) {
            $urls[] = [
                'loc' => "{$baseUrl}/{$locale}/cities",
                'lastmod' => $getLastmod('pages.cities.index', ["{$locale}/cities.php"]),
                'changefreq' => 'monthly',
                'priority' => 0.8,
                'alternates' => $generateAlternates('/cities'),
            ];

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

        foreach ($locales as $locale) {
            $urls[] = [
                'loc' => "{$baseUrl}/{$locale}/universities",
                'lastmod' => $getLastmod('pages.universities.index', ["{$locale}/universities.php"]),
                'changefreq' => 'monthly',
                'priority' => 0.8,
                'alternates' => $generateAlternates('/universities'),
            ];

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

        foreach ($locales as $locale) {
            $urls[] = [
                'loc' => "{$baseUrl}/{$locale}/services",
                'lastmod' => $getLastmod('pages.services.index', ["{$locale}/services.php"]),
                'changefreq' => 'monthly',
                'priority' => 0.9,
                'alternates' => $generateAlternates('/services'),
            ];

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

        foreach ($locales as $locale) {
            foreach ($this->siteUrls->staticPages() as $page) {
                $viewName = $page === 'contactUs' ? 'pages.contact' : "pages.{$page}";
                $pagePriority = $page === 'calculator' ? 0.85 : ($page === 'consult' ? 0.8 : config('seo.sitemap.priorities.static_page', 0.6));
                $pageFreq = $page === 'calculator' ? 'weekly' : config('seo.sitemap.changefreq.static_page', 'monthly');

                $urls[] = [
                    'loc' => "{$baseUrl}/{$locale}/{$page}",
                    'lastmod' => $getLastmod($viewName, ["{$locale}/".($page === 'contactUs' ? 'contact' : $page).'.php']),
                    'changefreq' => $pageFreq,
                    'priority' => $pagePriority,
                    'alternates' => $generateAlternates("/{$page}"),
                ];
            }
        }

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
