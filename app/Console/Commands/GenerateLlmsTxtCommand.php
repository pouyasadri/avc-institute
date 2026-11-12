<?php

namespace App\Console\Commands;

use App\Services\Discovery\LlmsTxtGenerator;
use Illuminate\Console\Command;

/**
 * Regenerate public/llms.txt and public/llms-full.txt from base templates + latest blogs.
 *
 * Usage:
 *   php artisan llms:generate
 *   php artisan llms:generate --limit=10
 *   php artisan llms:generate --dry-run
 */
class GenerateLlmsTxtCommand extends Command
{
    protected $signature = 'llms:generate
                            {--limit=10 : Number of recent blog posts to include}
                            {--dry-run : Print paths without writing files}';

    protected $description = 'Regenerate llms.txt + llms-full.txt with brand card + recent blog updates for AI agents';

    public function handle(LlmsTxtGenerator $generator): int
    {
        $limit = max(1, (int) $this->option('limit'));

        $this->newLine();
        $this->line('  <fg=cyan;options=bold>llms.txt Generator</>');
        $this->line("  Limit: <comment>{$limit}</comment> recent posts");
        $this->newLine();

        if ($this->option('dry-run')) {
            $this->line('  Index base:  '.$generator->basePath());
            $this->line('  Index out:   '.$generator->outputPath());
            $this->line('  Full base:   '.$generator->fullBasePath());
            $this->line('  Full out:    '.$generator->fullOutputPath());
            $this->warn('  Dry-run only — files not written. Remove --dry-run to generate.');

            return self::SUCCESS;
        }

        try {
            $result = $generator->generate($limit);
        } catch (\Throwable $e) {
            $this->error('  Failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info("  Wrote {$result['bytes']} bytes → {$result['path']}");
        $this->info("  Wrote {$result['full_bytes']} bytes → {$result['full_path']}");
        $this->line("  Included <info>{$result['posts']}</info> published blog post(s).");
        $this->newLine();
        $this->comment('  Tip: purge Cloudflare cache for /llms.txt, /llms-full.txt, /.well-known/*, and homepage after deploy.');

        return self::SUCCESS;
    }
}
