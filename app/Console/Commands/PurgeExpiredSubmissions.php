<?php

namespace App\Console\Commands;

use App\Models\ConsultingSubmission;
use App\Models\ContactSubmission;
use App\Models\QuestionSubmission;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PurgeExpiredSubmissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gdpr:purge {--dry-run : Show what would be purged without deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GDPR Art. 5(1)(e): Soft-delete submissions older than retention_days, then permanently delete grace-period records.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $retentionDays = config('gdpr.retention_days', 730);
        $gracePeriodDays = 30; // soft-deleted records kept 30 days before permanent deletion
        $ipRetentionDays = config('gdpr.technical_retention_days', 90);

        $retentionCutoff = Carbon::now()->subDays($retentionDays);
        $graceCutoff = Carbon::now()->subDays($gracePeriodDays);
        $ipCutoff = Carbon::now()->subDays($ipRetentionDays);

        $this->info('GDPR Purge — '.now()->toDateTimeString());
        $this->info("Retention cutoff  : {$retentionCutoff->toDateString()} ({$retentionDays} days)");
        $this->info("IP cutoff         : {$ipCutoff->toDateString()} ({$ipRetentionDays} days)");
        $this->info("Grace period end  : {$graceCutoff->toDateString()} ({$gracePeriodDays} days after soft-delete)");
        $isDryRun && $this->warn('[DRY RUN] No changes will be made.');
        $this->newLine();

        $models = [
            'contact_submissions' => ContactSubmission::class,
            'consulting_submissions' => ConsultingSubmission::class,
            'question_submissions' => QuestionSubmission::class,
        ];

        $totalSoftDeleted = 0;
        $totalHardDeleted = 0;
        $totalIpAnonymised = 0;

        foreach ($models as $table => $modelClass) {
            // 1. Soft-delete records older than retention window (not yet soft-deleted)
            $toSoftDelete = $modelClass::where('created_at', '<', $retentionCutoff)
                ->whereNull('deleted_at')
                ->count();

            $this->line("<fg=cyan>{$table}</> — {$toSoftDelete} records to soft-delete");

            if (! $isDryRun && $toSoftDelete > 0) {
                $modelClass::where('created_at', '<', $retentionCutoff)
                    ->whereNull('deleted_at')
                    ->delete(); // Triggers SoftDeletes
            }

            $totalSoftDeleted += $toSoftDelete;

            // 2. Permanently delete records that are soft-deleted AND past the grace period
            $toHardDelete = $modelClass::withTrashed()
                ->where('deleted_at', '<', $graceCutoff)
                ->whereNotNull('deleted_at')
                ->count();

            $this->line("<fg=cyan>{$table}</> — {$toHardDelete} soft-deleted records to permanently purge");

            if (! $isDryRun && $toHardDelete > 0) {
                $modelClass::withTrashed()
                    ->where('deleted_at', '<', $graceCutoff)
                    ->whereNotNull('deleted_at')
                    ->forceDelete();
            }

            $totalHardDeleted += $toHardDelete;

            // 3. Anonymise IP / user_agent on records older than 90 days
            $toAnonymise = $modelClass::where('created_at', '<', $ipCutoff)
                ->where(function ($q) {
                    $q->whereNotNull('ip_address')->orWhereNotNull('user_agent');
                })
                ->count();

            $this->line("<fg=cyan>{$table}</> — {$toAnonymise} records to anonymise (IP/UA)");

            if (! $isDryRun && $toAnonymise > 0) {
                $modelClass::where('created_at', '<', $ipCutoff)
                    ->where(function ($q) {
                        $q->whereNotNull('ip_address')->orWhereNotNull('user_agent');
                    })
                    ->update([
                        'ip_address' => null,
                        'user_agent' => null,
                    ]);
            }

            $totalIpAnonymised += $toAnonymise;
            $this->newLine();
        }

        $summary = "[GDPR Purge] Soft-deleted: {$totalSoftDeleted} | Permanently purged: {$totalHardDeleted} | IP anonymised: {$totalIpAnonymised}".
                   ($isDryRun ? ' [DRY RUN]' : '');

        $this->info($summary);

        if (! $isDryRun) {
            Log::info($summary);
        }

        return Command::SUCCESS;
    }
}
