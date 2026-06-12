<?php

namespace App\Services;

use App\Jobs\IndexNowPingJob;
use App\Models\Blog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * IndexNow Service
 *
 * Submits URLs to IndexNow-compatible search engines for instant re-indexing.
 * Supports all 5 participating engines, ordered by Iranian audience relevance:
 *   1. Microsoft Bing (powers DuckDuckGo + Copilot)
 *   2. Yandex (popular in Iran when Google is restricted)
 *   3. Yep
 *   4. Naver
 *   5. Seznam.cz
 *
 * Per the IndexNow spec, submitting to one endpoint propagates to all others.
 * We ping all engines independently for maximum speed and reliability.
 *
 * Usage:
 *   // Single URL (auto-dispatches async job in production):
 *   app(IndexNowService::class)->ping('https://applyvipconseil.com/fa/blog/my-post');
 *
 *   // Multiple URLs (batch POST):
 *   app(IndexNowService::class)->pingBatch([...]);
 *
 *   // Force synchronous (e.g. in Artisan commands):
 *   app(IndexNowService::class)->pingSync('https://...');
 *
 * @see https://www.indexnow.org/documentation
 */
class IndexNowService
{
    protected string $key;

    protected string $keyLocation;

    protected string $host;

    /** @var array<int, array{name: string, endpoint: string, enabled: bool, priority: int}> */
    protected array $engines;

    protected bool $logResponses;

    public function __construct()
    {
        $this->key = config('indexnow.key');
        $this->keyLocation = config('indexnow.key_location');
        $this->host = parse_url(config('app.url'), PHP_URL_HOST) ?? 'applyvipconseil.com';
        $this->engines = collect(config('indexnow.engines', []))
            ->where('enabled', true)
            ->sortBy('priority')
            ->values()
            ->toArray();
        $this->logResponses = (bool) config('indexnow.log_responses', false);
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Submit a single URL to all enabled IndexNow engines.
     * Dispatches a queued job unless INDEXNOW_ASYNC=false.
     */
    public function ping(string $url): void
    {
        if (config('indexnow.async', true)) {
            dispatch(new IndexNowPingJob([$url]));

            return;
        }

        $this->pingSync($url);
    }

    /**
     * Submit multiple URLs in a batch to all enabled engines.
     * Dispatches a queued job unless INDEXNOW_ASYNC=false.
     *
     * @param  array<string>  $urls
     */
    public function pingBatch(array $urls): void
    {
        if (empty($urls)) {
            return;
        }

        $chunks = array_chunk($urls, config('indexnow.max_urls_per_batch', 500));

        foreach ($chunks as $chunk) {
            if (config('indexnow.async', true)) {
                dispatch(new IndexNowPingJob($chunk));
            } else {
                $this->submitBatchToAllEngines($chunk);
            }
        }
    }

    /**
     * Submit a single URL synchronously (blocking).
     * Useful in Artisan commands and tests.
     */
    public function pingSync(string $url): array
    {
        return $this->submitBatchToAllEngines([$url]);
    }

    /**
     * Build all public site URLs — mirrors SitemapController exactly.
     *
     * Uses the same config sources as the sitemap so the IndexNow submission
     * always covers 100% of indexed pages. If the sitemap adds a new page type,
     * add it here too to keep them in sync.
     *
     * @return array<string> All URLs to submit
     */
    public function buildAllSiteUrls(): array
    {
        // Use the same locale source as SitemapController (array_keys of seo.locales)
        $locales = array_keys(config('seo.locales', ['en' => [], 'fr' => [], 'fa' => []]));
        $baseUrl = rtrim(config('app.url'), '/');

        // Use the same config keys as SitemapController
        $cities = config('site_structure.cities', []);
        $universities = config('site_structure.universities', []);
        $services = config('site_structure.service_slugs', []);
        $staticPages = ['consult', 'contactUs'];

        $urls = [];

        foreach ($locales as $locale) {
            // Homepages
            $urls[] = "{$baseUrl}/{$locale}";

            // Blog index
            $urls[] = "{$baseUrl}/{$locale}/blog";

            // Cities index + individual city pages
            $urls[] = "{$baseUrl}/{$locale}/cities";
            foreach ($cities as $city) {
                $urls[] = "{$baseUrl}/{$locale}/cities/{$city}";
            }

            // Universities index + individual university pages
            $urls[] = "{$baseUrl}/{$locale}/universities";
            foreach ($universities as $university) {
                $urls[] = "{$baseUrl}/{$locale}/universities/{$university}";
            }

            // Services index + individual service pages
            $urls[] = "{$baseUrl}/{$locale}/services";
            foreach ($services as $service) {
                $urls[] = "{$baseUrl}/{$locale}/services/{$service}";
            }

            // Static pages (consult, contactUs)
            foreach ($staticPages as $page) {
                $urls[] = "{$baseUrl}/{$locale}/{$page}";
            }
        }

        // Blog posts — use $blog->id (not slug) to match the sitemap URL pattern
        // SitemapController: "{$baseUrl}/{$locale}/blog/{$blog->id}"
        try {
            $blogs = Blog::published()
                ->select('id', 'updated_at')
                ->get();

            foreach ($blogs as $blog) {
                foreach ($locales as $locale) {
                    $urls[] = "{$baseUrl}/{$locale}/blog/{$blog->id}";
                }
            }
        } catch (\Throwable $e) {
            Log::warning('IndexNow: could not load blog posts — '.$e->getMessage());
        }

        return array_values(array_unique($urls));
    }

    // -------------------------------------------------------------------------
    // Internal — Engine Communication
    // -------------------------------------------------------------------------

    /**
     * POST a batch of URLs to each enabled engine endpoint.
     *
     * @param  array<string>  $urls
     * @return array<string, array{engine: string, status: int|null, error: string|null}>
     */
    public function submitBatchToAllEngines(array $urls): array
    {
        $results = [];

        foreach ($this->engines as $engine) {
            $results[$engine['name']] = $this->postToEngine($engine, $urls);
        }

        return $results;
    }

    /**
     * POST a batch of URLs to a single engine via the IndexNow JSON API.
     *
     * @param  array{name: string, endpoint: string}  $engine
     * @param  array<string>  $urls
     * @return array{engine: string, status: int|null, error: string|null}
     */
    protected function postToEngine(array $engine, array $urls): array
    {
        $payload = [
            'host' => $this->host,
            'key' => $this->key,
            'keyLocation' => $this->keyLocation,
            'urlList' => array_values($urls),
        ];

        try {
            $response = Http::timeout(10)
                ->withHeaders(['Content-Type' => 'application/json; charset=utf-8'])
                ->post($engine['endpoint'], $payload);

            $status = $response->status();

            if ($this->logResponses) {
                Log::info("IndexNow [{$engine['name']}]: HTTP {$status} for ".count($urls).' URL(s)', [
                    'engine' => $engine['name'],
                    'status' => $status,
                    'urls' => $urls,
                ]);
            }

            // 200 = OK, 202 = Accepted (key validation pending — both are success)
            if (! in_array($status, [200, 202])) {
                Log::warning("IndexNow [{$engine['name']}]: Unexpected HTTP {$status}", [
                    'engine' => $engine['name'],
                    'status' => $status,
                    'body' => $response->body(),
                    'urls' => array_slice($urls, 0, 5), // Only log first 5 to avoid huge logs
                ]);
            }

            return ['engine' => $engine['name'], 'status' => $status, 'error' => null];

        } catch (\Throwable $e) {
            Log::warning("IndexNow [{$engine['name']}]: Request failed — ".$e->getMessage(), [
                'engine' => $engine['name'],
                'urls' => array_slice($urls, 0, 5),
            ]);

            return ['engine' => $engine['name'], 'status' => null, 'error' => $e->getMessage()];
        }
    }
}
