<?php

namespace App\Console\Commands;

use App\Models\Vefa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanVefaRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vefa:clean {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Vefa records where image files are missing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $vefas = Vefa::all();
        $deletedCount = 0;
        $dryRun = $this->option('dry-run');

        $this->info('Checking Vefa records for missing image files...');

        foreach ($vefas as $vefa) {
            if ($vefa->image_path) {
                $fullPath = storage_path('app/public/' . $vefa->image_path);

                if (!file_exists($fullPath)) {
                    if ($dryRun) {
                        $this->warn("Would delete: ID {$vefa->id} - {$vefa->title} (Missing: {$vefa->image_path})");
                    } else {
                        $this->info("Deleting: ID {$vefa->id} - {$vefa->title} (Missing: {$vefa->image_path})");
                        $vefa->delete();
                    }
                    $deletedCount++;
                } else {
                    $this->line("OK: ID {$vefa->id} - {$vefa->title}");
                }
            } else {
                if ($dryRun) {
                    $this->warn("Would delete: ID {$vefa->id} - {$vefa->title} (No image path)");
                } else {
                    $this->info("Deleting: ID {$vefa->id} - {$vefa->title} (No image path)");
                    $vefa->delete();
                }
                $deletedCount++;
            }
        }

        if ($dryRun) {
            $this->info("Dry run complete. Would delete {$deletedCount} records.");
        } else {
            $this->info("Cleanup complete. Deleted {$deletedCount} records.");
        }

        return 0;
    }
}
