<?php

namespace App\Console\Commands;

use App\Services\IndexNowService;
use Illuminate\Console\Command;

/**
 * IndexNow Submit Artisan Command
 *
 * Submits all public site URLs to all enabled IndexNow search engines.
 * Useful for the initial submission after deployment, or after bulk content changes.
 *
 * Usage:
 *   # Dry-run (list URLs without sending):
 *   php artisan indexnow:submit --dry-run
 *
 *   # Submit all URLs to all engines:
 *   php artisan indexnow:submit
 *
 *   # Submit a single specific URL:
 *   php artisan indexnow:submit --url=https://applyvipconseil.com/fa/blog/my-post
 *
 *   # Submit with verbose response logging:
 *   php artisan indexnow:submit --verbose
 *
 *   # Submit to a specific engine only:
 *   php artisan indexnow:submit --engine=bing
 */
class IndexNowSubmitCommand extends Command
{
    protected $signature = 'indexnow:submit
                            {--dry-run   : List URLs that would be submitted without sending}
                            {--url=      : Submit only this specific URL}
                            {--engine=   : Filter to a specific engine name (partial match)}';

    protected $description = 'Submit all site URLs (or a specific URL) to IndexNow search engines (Bing, Yandex, Yep, Naver, Seznam)';

    public function handle(IndexNowService $service): int
    {
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>IndexNow Submission</>');
        $this->line('  Engines: <comment>Bing · Yandex · Yep · Naver · Seznam.cz</comment>');
        $this->newLine();

        // ------ Build URL list -----------------------------------------------
        if ($specificUrl = $this->option('url')) {
            $urls = [$specificUrl];
            $this->line("  Mode: <info>Single URL</info> → {$specificUrl}");
        } else {
            $this->line('  Mode: <info>Full-site submission</info> (building URL list…)');
            $urls = $service->buildAllSiteUrls();
            $this->line('  Found <info>'.count($urls).'</info> URLs to submit.');
        }

        $this->newLine();

        // ------ Dry-run -------------------------------------------------------
        if ($this->option('dry-run')) {
            $this->warn('  DRY-RUN — no requests will be sent.');
            $this->newLine();
            $headers = ['#', 'URL'];
            $rows = array_map(
                fn ($i, $url) => [$i + 1, $url],
                array_keys($urls),
                $urls
            );
            $this->table($headers, $rows);

            return self::SUCCESS;
        }

        // ------ Batch & submit ------------------------------------------------
        $maxPerBatch = config('indexnow.max_urls_per_batch', 500);
        $chunks = array_chunk($urls, $maxPerBatch);
        $engines = collect(config('indexnow.engines', []))->where('enabled', true)->sortBy('priority');

        // Apply engine filter if requested
        if ($engineFilter = $this->option('engine')) {
            $engines = $engines->filter(
                fn ($e) => stripos($e['name'], $engineFilter) !== false
            );

            if ($engines->isEmpty()) {
                $this->error("  No engine found matching '{$engineFilter}'. Available engines:");
                collect(config('indexnow.engines', []))->each(fn ($e) => $this->line("    - {$e['name']}"));

                return self::FAILURE;
            }
        }

        $this->line('  Submitting <info>'.count($urls).'</info> URLs in <info>'.count($chunks).'</info> batch(es)…');
        $this->newLine();

        $overallSuccess = true;

        foreach ($chunks as $batchIndex => $chunk) {
            $batchNum = $batchIndex + 1;
            $this->line("  <fg=yellow>Batch {$batchNum}/".count($chunks).'</> ('.count($chunk).' URLs)');

            $batchResults = $service->submitBatchToAllEngines($chunk);

            foreach ($engines as $engine) {
                $this->output->write("    → <comment>{$engine['name']}</comment>… ");

                $result = $batchResults[$engine['name']] ?? null;

                if ($result === null) {
                    $this->line('<error> SKIPPED </error>');

                    continue;
                }

                $status = $result['status'];
                $error = $result['error'];

                if ($status === null) {
                    $this->line("<error> FAILED </error> {$error}");
                    $overallSuccess = false;
                } elseif (in_array($status, [200, 202])) {
                    $label = $status === 200 ? ' OK 200 ' : ' ACCEPTED 202 ';
                    $this->line("<fg=green>{$label}</>");
                } elseif ($status === 429) {
                    $this->line('<error> 429 TOO MANY REQUESTS </error> — wait and retry later.');
                    $overallSuccess = false;
                } elseif ($status === 403) {
                    $detail = $error ? " ({$error})" : ' — check key file is accessible.';
                    $this->line("<error> 403 FORBIDDEN </error>{$detail}");
                    $overallSuccess = false;
                } else {
                    $detail = $error ? " ({$error})" : '';
                    $this->line("<error> HTTP {$status} </error>{$detail}");
                    $overallSuccess = false;
                }
            }

            $this->newLine();
        }

        if ($overallSuccess) {
            $this->info('  ✅ All URLs submitted successfully.');
        } else {
            $this->warn('  ⚠️  Some submissions failed. Review the output above.');
        }

        $this->newLine();

        return $overallSuccess ? self::SUCCESS : self::FAILURE;
    }
}
