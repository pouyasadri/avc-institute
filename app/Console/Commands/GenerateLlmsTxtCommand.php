<?php

namespace App\Console\Commands;

use App\Services\Discovery\LlmsTxtGenerator;
use Illuminate\Console\Command;

/**
 * Regenerate public/llms.txt from the base template + latest published blogs.
 *
 * Usage:
 *   php artisan llms:generate
 *   php artisan llms:generate --limit=10
 *   php artisan llms:generate --dry-run
 */
class GenerateLlmsTxtCommand extends Command
{
    protected $signature = 'llms:generate
                            {--limit=8 : Number of recent blog posts to include}
                            {--dry-run : Print the generated content without writing the file}';

    protected $description = 'Regenerate public/llms.txt with brand card + recent blog updates for AI agents';

    public function handle(LlmsTxtGenerator $generator): int
    {
        $limit = max(1, (int) $this->option('limit'));

        $this->newLine();
        $this->line('  <fg=cyan;options=bold>llms.txt Generator</>');
        $this->line("  Limit: <comment>{$limit}</comment> recent posts");
        $this->newLine();

        if ($this->option('dry-run')) {
            $base = file_get_contents($generator->basePath());
            $this->line('  Base template: '.$generator->basePath());
            $this->line('  Output path:   '.$generator->outputPath());
            $this->line('  Base bytes:    '.strlen((string) $base));
            $this->warn('  Dry-run only — file not written. Remove --dry-run to generate.');

            return self::SUCCESS;
        }

        try {
            $result = $generator->generate($limit);
        } catch (\Throwable $e) {
            $this->error('  Failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info("  Wrote {$result['bytes']} bytes → {$result['path']}");
        $this->line("  Included <info>{$result['posts']}</info> published blog post(s).");
        $this->newLine();
        $this->comment('  Tip: purge Cloudflare cache for /llms.txt after deploy.');

        return self::SUCCESS;
    }
}
