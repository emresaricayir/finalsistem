<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\Due;
use App\Mail\OverdueDuesReminder;
use App\Services\EmailService;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendOverdueDuesReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:send-overdue-reminders {--months=3 : Number of months to check for overdue dues}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send overdue dues reminder emails to members who haven\'t paid for specified months';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $months = (int) $this->option('months');
        $this->info("Checking for members with overdue dues for {$months} months...");

        // Get members with overdue dues
        $membersWithOverdueDues = $this->getMembersWithOverdueDues($months);

        if ($membersWithOverdueDues->isEmpty()) {
            $this->info('No members found with overdue dues.');
            return 0;
        }

        $this->info("Found {$membersWithOverdueDues->count()} members with overdue dues.");

        $sentCount = 0;
        $failedCount = 0;

        foreach ($membersWithOverdueDues as $member) {
            try {
                $overdueAmount = $this->calculateOverdueAmount($member, $months);

                if ($overdueAmount > 0) {
                    // Use dynamic email template
                    $overdueDues = collect([
                        (object) ['month_name' => 'Örnek Ay', 'year' => date('Y'), 'amount' => $overdueAmount],
                    ]);
                    EmailService::sendOverdueDuesReminder($member, $overdueDues);

                    // Log the sent reminder
                    $this->logReminderSent($member, $overdueAmount, $months);

                    $sentCount++;
                    $this->info("✓ Reminder sent to {$member->name} {$member->surname} ({$member->email}) - Amount: €{$overdueAmount}");
                }
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("✗ Failed to send reminder to {$member->name} {$member->surname}: {$e->getMessage()}");
            }
        }

        $this->info("\nSummary:");
        $this->info("- Total members checked: {$membersWithOverdueDues->count()}");
        $this->info("- Reminders sent successfully: {$sentCount}");
        $this->info("- Failed to send: {$failedCount}");

        return 0;
    }

    /**
     * Get members with overdue dues
     */
    private function getMembersWithOverdueDues($months)
    {
        $cutoffDate = Carbon::now()->subMonths($months);

        return Member::where('status', 'active')
            ->where('application_status', 'approved')
            ->whereHas('dues', function ($query) use ($cutoffDate) {
                $query->where('due_date', '<', $cutoffDate)
                      ->where('status', '!=', 'paid');
            })
            ->orWhereDoesntHave('dues', function ($query) use ($cutoffDate) {
                $query->where('due_date', '>=', $cutoffDate);
            })
            ->get();
    }

    /**
     * Calculate overdue amount for a member
     */
    private function calculateOverdueAmount($member, $months)
    {
        // Seçilen ay sayısı kadar gecikmiş aidat hesapla
        return $member->monthly_dues * $months;
    }

    /**
     * Log that a reminder was sent
     */
    private function logReminderSent($member, $amount, $months)
    {
        // Create notification for admin
        try {
            \App\Models\Notification::create([
                'title' => 'Aidat Hatırlatması Gönderildi',
                'message' => "{$member->name} {$member->surname} adlı üyeye {$months} aylık aidat hatırlatması gönderildi. Tutar: €{$amount}",
                'type' => 'info',
                'icon' => 'fa-envelope'
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the command
            \Log::error('Failed to create notification for overdue reminder: ' . $e->getMessage());
        }

        // Log to file
        \Log::info("Overdue dues reminder sent to member {$member->id} ({$member->email}) for €{$amount}");
    }
}
