<?php

namespace App\Console\Commands;

use App\Models\EmailLog;
use Illuminate\Console\Command;

class CleanEmailLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-logs:clean {--days=30 : Number of days to keep logs} {--all : Delete all logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old email logs to keep database size manageable';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            // Delete all logs
            $deletedCount = EmailLog::count();
            EmailLog::truncate();

            $this->info("All {$deletedCount} email log entries have been deleted.");
        } else {
            $days = $this->option('days');
            $cutoffDate = now()->subDays($days);

            $this->info("Cleaning email logs older than {$days} days...");

            $deletedCount = EmailLog::where('created_at', '<', $cutoffDate)->delete();

            $this->info("Deleted {$deletedCount} old email log entries.");
        }

        // Show current log count
        $totalLogs = EmailLog::count();
        $this->info("Current email logs count: {$totalLogs}");

        return 0;
    }
}
