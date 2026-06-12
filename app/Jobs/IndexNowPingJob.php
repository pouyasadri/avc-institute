<?php

namespace App\Jobs;

use App\Services\IndexNowService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * IndexNow Ping Job
 *
 * Dispatched by IndexNowService when async mode is enabled.
 * Runs in the background so IndexNow pings never slow down HTTP responses.
 *
 * Configured via config/indexnow.php:
 *   - queue_name:       Which queue to use (default: 'indexnow')
 *   - queue_connection: Queue driver (default: app default)
 *   - max_retries:      How many times to retry on failure (default: 3)
 *   - retry_after:      Seconds before retry (default: 60)
 */
class IndexNowPingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var array<string> */
    public array $urls;

    public int $tries;

    public int $backoff;

    /**
     * @param  array<string>  $urls  URLs to submit to all IndexNow engines.
     */
    public function __construct(array $urls)
    {
        $this->urls = $urls;
        $this->tries = config('indexnow.max_retries', 3);
        $this->backoff = config('indexnow.retry_after', 60);

        // Route to the dedicated 'indexnow' queue to keep pings isolated
        // from other business-critical jobs.
        $this->onQueue(config('indexnow.queue_name', 'indexnow'));

        if ($connection = config('indexnow.queue_connection')) {
            $this->onConnection($connection);
        }
    }

    /**
     * Execute the job — submit all URLs to all enabled IndexNow engines.
     */
    public function handle(IndexNowService $service): void
    {
        if (empty($this->urls)) {
            return;
        }

        $results = $service->submitBatchToAllEngines($this->urls);

        // Log summary only if any engine failed (to avoid log noise on success)
        $failed = array_filter($results, fn ($r) => $r['status'] === null || ! in_array($r['status'], [200, 202]));

        if (! empty($failed)) {
            Log::warning('IndexNowPingJob: Some engines returned errors', [
                'urls_count' => count($this->urls),
                'failures' => $failed,
            ]);
        }
    }

    /**
     * Handle a job failure after all retries are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('IndexNowPingJob: Failed after all retries — '.$exception->getMessage(), [
            'urls' => array_slice($this->urls, 0, 10),
        ]);
    }
}
