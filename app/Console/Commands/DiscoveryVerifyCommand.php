<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Verify GEO / agent discovery surfaces (HTTP + optional DNS checks).
 *
 * Usage:
 *   php artisan discovery:verify
 *   php artisan discovery:verify --base-url=https://applyvipconseil.com
 *   php artisan discovery:verify --skip-dns
 */
class DiscoveryVerifyCommand extends Command
{
    protected $signature = 'discovery:verify
                            {--base-url= : Base URL to probe (defaults to APP_URL)}
                            {--skip-dns  : Skip external DNS TYPE65 / DNSSEC checks}';

    protected $description = 'Verify llms.txt, agent index, api-catalog, discovery headers, and DNS-AID records';

    public function handle(): int
    {
        $base = rtrim($this->option('base-url') ?: config('app.url'), '/');
        $failed = 0;

        $this->newLine();
        $this->line('  <fg=cyan;options=bold>Agent Discovery Verification</>');
        $this->line("  Target: <comment>{$base}</comment>");
        $this->newLine();

        $failed += $this->checkHttpEndpoint("{$base}/llms.txt", 'text/plain', 'Primary Audience') ? 0 : 1;
        $this->warnIfMissing("{$base}/llms.txt", 'Canonical name: A.V.C Institute', 'llms.txt brand card (Phase 1)');
        $failed += $this->checkHttpEndpoint("{$base}/.well-known/agents-index.json", 'application/json', '"documentation"') ? 0 : 1;
        $failed += $this->checkHttpEndpoint("{$base}/.well-known/api-catalog", 'application/linkset+json', 'service-doc') ? 0 : 1;
        $failed += $this->checkHttpEndpoint("{$base}/sitemap.xml", 'xml', '<urlset') ? 0 : 1;
        $failed += $this->checkHomepageDiscoveryHeaders("{$base}/fa") ? 0 : 1;

        if (! $this->option('skip-dns')) {
            $host = parse_url($base, PHP_URL_HOST) ?: 'applyvipconseil.com';
            $failed += $this->checkDnsHttpsRecord("_index._agents.{$host}") ? 0 : 1;
            $failed += $this->checkDnsHttpsRecord("_a2a._agents.{$host}") ? 0 : 1;
            $failed += $this->checkDnssec($host) ? 0 : 1;
        }

        $this->newLine();
        if ($failed === 0) {
            $this->info('  All discovery checks passed.');

            return self::SUCCESS;
        }

        $this->error("  {$failed} discovery check(s) failed.");

        return self::FAILURE;
    }

    protected function warnIfMissing(string $url, string $needle, string $label): void
    {
        try {
            $response = Http::timeout(15)->withHeaders([
                'User-Agent' => 'AVC-DiscoveryVerify/1.0',
            ])->get($url);

            if ($response->successful() && ! str_contains($response->body(), $needle)) {
                $this->line("  <comment>!</comment> Optional: {$label} not live yet at {$url}");
            }
        } catch (\Throwable) {
            // optional warning only
        }
    }

    protected function checkHttpEndpoint(string $url, string $expectedTypeFragment, string $bodyNeedle): bool
    {
        try {
            $response = Http::timeout(15)->withHeaders([
                'User-Agent' => 'AVC-DiscoveryVerify/1.0',
            ])->get($url);

            $contentType = $response->header('Content-Type') ?? '';
            $body = $response->body();
            $ok = $response->successful()
                && str_contains($contentType, $expectedTypeFragment)
                && str_contains($body, $bodyNeedle);

            $this->line($ok
                ? "  <info>✓</info> HTTP {$url}"
                : "  <fg=red>✗</> HTTP {$url} (status={$response->status()}, type={$contentType})");

            return $ok;
        } catch (\Throwable $e) {
            $this->line("  <fg=red>✗</> HTTP {$url} — ".$e->getMessage());

            return false;
        }
    }

    protected function checkHomepageDiscoveryHeaders(string $url): bool
    {
        try {
            $response = Http::timeout(15)->withHeaders([
                'User-Agent' => 'AVC-DiscoveryVerify/1.0',
            ])->get($url);

            $link = $response->header('Link') ?? '';
            $required = [
                '</llms.txt>; rel="help"',
                '</.well-known/agents-index.json>; rel="index"',
                '</.well-known/api-catalog>; rel="api-catalog"',
                '</sitemap.xml>; rel="sitemap"',
            ];

            $missing = array_filter($required, fn (string $needle) => ! str_contains($link, $needle));
            $ok = $response->successful() && $missing === [];

            $this->line($ok
                ? "  <info>✓</info> Link headers on {$url}"
                : '  <fg=red>✗</> Link headers missing: '.implode(', ', $missing));

            return $ok;
        } catch (\Throwable $e) {
            $this->line('  <fg=red>✗</> Link headers — '.$e->getMessage());

            return false;
        }
    }

    /**
     * Query Cloudflare DoH for HTTPS (TYPE65) agent discovery records.
     */
    protected function checkDnsHttpsRecord(string $name): bool
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders(['Accept' => 'application/dns-json'])
                ->get('https://cloudflare-dns.com/dns-query', [
                    'name' => $name,
                    'type' => 'HTTPS',
                ]);

            $data = $response->json();
            $answers = $data['Answer'] ?? [];
            $ok = $response->successful() && ! empty($answers);

            $detail = $ok ? ($answers[0]['data'] ?? 'present') : 'NXDOMAIN / empty';
            $this->line($ok
                ? "  <info>✓</info> DNS HTTPS {$name} → {$detail}"
                : "  <fg=red>✗</> DNS HTTPS {$name} → {$detail}");

            return $ok;
        } catch (\Throwable $e) {
            $this->line("  <fg=red>✗</> DNS HTTPS {$name} — ".$e->getMessage());

            return false;
        }
    }

    protected function checkDnssec(string $host): bool
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders(['Accept' => 'application/dns-json'])
                ->get('https://cloudflare-dns.com/dns-query', [
                    'name' => $host,
                    'type' => 'DS',
                ]);

            $data = $response->json();
            $ok = $response->successful()
                && ! empty($data['Answer'] ?? [])
                && ($data['AD'] ?? false) === true;

            $this->line($ok
                ? "  <info>✓</info> DNSSEC validated for {$host}"
                : "  <fg=red>✗</> DNSSEC not validated for {$host}");

            return $ok;
        } catch (\Throwable $e) {
            $this->line('  <fg=red>✗</> DNSSEC — '.$e->getMessage());

            return false;
        }
    }
}
