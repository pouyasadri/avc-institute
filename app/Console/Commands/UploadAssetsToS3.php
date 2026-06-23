<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UploadAssetsToS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:upload-assets {--disk=s3 : The storage disk to upload to} {--force : Force upload without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload all public assets (including build) to the specified storage disk (N0C/S3)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $disk = $this->option('disk');

        if (! config("filesystems.disks.{$disk}")) {
            $this->error("Disk '{$disk}' is not configured in filesystems.php.");

            return Command::FAILURE;
        }

        $directories = [
            'assets' => public_path('assets'),
            'build' => public_path('build'),
        ];

        // Filter out non-existing directories
        $directories = array_filter($directories, function ($path) {
            return File::isDirectory($path);
        });

        if (empty($directories)) {
            $this->error('No assets or build directories found to upload.');

            return Command::FAILURE;
        }

        $files = [];
        foreach ($directories as $folderName => $path) {
            foreach (File::allFiles($path) as $file) {
                // Skip hidden files like .DS_Store
                if (str_starts_with($file->getFilename(), '.')) {
                    continue;
                }

                $files[] = [
                    'absolute_path' => $file->getPathname(),
                    'relative_path' => $folderName.'/'.$file->getRelativePathname(),
                ];
            }
        }

        $fileCount = count($files);
        if ($fileCount === 0) {
            $this->info('No files found to upload.');

            return Command::SUCCESS;
        }

        if (! $this->option('force')) {
            if (! $this->confirm("Are you sure you want to upload {$fileCount} files to disk '{$disk}'?")) {
                $this->info('Upload cancelled.');

                return Command::SUCCESS;
            }
        }

        $this->info("Starting upload of {$fileCount} files to disk '{$disk}'...");

        $bar = $this->output->createProgressBar($fileCount);
        $bar->start();

        $successCount = 0;
        $failedFiles = [];

        foreach ($files as $fileInfo) {
            try {
                $stream = fopen($fileInfo['absolute_path'], 'r');
                $uploaded = Storage::disk($disk)->put(
                    $fileInfo['relative_path'],
                    $stream,
                    ['visibility' => 'public']
                );

                if (is_resource($stream)) {
                    fclose($stream);
                }

                if ($uploaded) {
                    $successCount++;
                } else {
                    $failedFiles[] = $fileInfo['relative_path'];
                }
            } catch (\Exception $e) {
                $failedFiles[] = $fileInfo['relative_path'].' ('.$e->getMessage().')';
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Successfully uploaded {$successCount}/{$fileCount} files.");

        if (count($failedFiles) > 0) {
            $this->error('The following files failed to upload:');
            foreach ($failedFiles as $failed) {
                $this->line("- {$failed}");
            }

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
