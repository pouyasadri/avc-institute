<?php

namespace App\Services;

use App\Jobs\IndexNowPingJob;
use App\Models\Blog;
use App\Services\Seo\SiteUrlBuilder;
use Illuminate\Support\Facades\Cache;
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

    public function __construct(protected SiteUrlBuilder $siteUrls)
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
     * Build public URLs for one blog post across all supported locales,
     * plus each locale's blog index (so listings get re-crawled).
     *
     * @return array<string>
     */
    public function buildBlogPostUrls(Blog $blog): array
    {
        return $this->siteUrls->blogPostUrls($blog, includeIndexes: true);
    }

    /**
     * Ping IndexNow for a blog change and clear the sitemap blogs cache.
     */
    public function notifyBlogChanged(Blog $blog): void
    {
        $urls = $this->buildBlogPostUrls($blog);

        if ($urls !== []) {
            $this->pingBatch($urls);
        }

        Cache::forget('sitemap:blogs');
    }

    /**
     * Ping IndexNow for removed blog URLs and clear the sitemap blogs cache.
     *
     * @param  array<string>  $urls  URLs captured before soft-delete
     */
    public function notifyBlogRemoved(array $urls): void
    {
        if ($urls !== []) {
            $this->pingBatch($urls);
        }

        Cache::forget('sitemap:blogs');
    }

    /**
     * Build all public site URLs — delegates to SiteUrlBuilder (shared with sitemap).
     *
     * @return array<string> All URLs to submit
     */
    public function buildAllSiteUrls(): array
    {
        return $this->siteUrls->buildAllAbsoluteUrls();
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
            $body = trim($response->body());

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
                    'body' => $body,
                    'urls' => array_slice($urls, 0, 5), // Only log first 5 to avoid huge logs
                ]);

                return ['engine' => $engine['name'], 'status' => $status, 'error' => $body ?: null];
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
